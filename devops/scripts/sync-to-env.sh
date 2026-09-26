#!/usr/bin/env bash
#
# Sync the local development environment (DB + uploads) to a remote
# WordPress deployment running on Coolify (staging or production).
#
# Usage:
#   TARGET=staging    ./devops/scripts/sync-to-env.sh
#   TARGET=production ./devops/scripts/sync-to-env.sh
#
# Required env vars (read from .env unless overridden in the shell):
#   LOCAL_DOMAIN
#   STAGING_DOMAIN     | PRODUCTION_DOMAIN
#   STAGING_SSH_HOST   | PRODUCTION_SSH_HOST
#
# Steps:
#   1. Export the local DB through wp-cli with LOCAL_DOMAIN -> TARGET_DOMAIN
#      search-replace (handled by db-export.sh in the php container).
#   2. (production only) Back up the remote DB to /tmp on the server.
#   3. Reset the remote DB and stream the dump into `wp db import`.
#   4. Swap any remaining OTHER_DOMAIN URLs and force https for TARGET_DOMAIN.
#   5. Rsync ./uploads into the remote uploads volume (no --delete on prod).
#
# Safety:
#   - the remote deployment is resolved from the Coolify compose project
#     whose nginx serves TARGET_DOMAIN (other sites share the server).
#   - production runs require typing the production domain to confirm.
#   - production runs are non-destructive for uploads (no --delete).

set -euo pipefail

TARGET="${TARGET:-}"
case "$TARGET" in
    staging|production) ;;
    *) printf 'error: TARGET must be "staging" or "production" (got "%s")\n' "$TARGET" >&2; exit 1 ;;
esac

step() { printf '\n==> %s\n' "$*"; }
info() { printf '    %s\n' "$*"; }
fail() { printf 'error: %s\n' "$*" >&2; exit 1; }

repo_root=$(cd "$(dirname "$0")/../.." && pwd)
cd "$repo_root"

[ -f .env ] || fail ".env not found in $repo_root"

# A missing key yields an empty value instead of tripping set -e/pipefail
read_env() {
    { grep -E "^$1=" .env || true; } | head -n1 | cut -d= -f2- | sed -E 's/^"(.*)"$/\1/; s/^'\''(.*)'\''$/\1/'
}

LOCAL_DOMAIN=$(read_env LOCAL_DOMAIN)
STAGING_DOMAIN=$(read_env STAGING_DOMAIN)
PRODUCTION_DOMAIN=$(read_env PRODUCTION_DOMAIN)
[ -n "$LOCAL_DOMAIN" ]      || fail "LOCAL_DOMAIN must be set in .env"
[ -n "$STAGING_DOMAIN" ]    || fail "STAGING_DOMAIN must be set in .env"
[ -n "$PRODUCTION_DOMAIN" ] || fail "PRODUCTION_DOMAIN must be set in .env"

if [ "$TARGET" = "staging" ]; then
    TARGET_DOMAIN="$STAGING_DOMAIN"
    OTHER_DOMAIN="$PRODUCTION_DOMAIN"
    SSH_HOST="${STAGING_SSH_HOST:-$(read_env STAGING_SSH_HOST)}"
    SSH_HOST="${SSH_HOST:-hetzner}"
    RSYNC_DELETE="--delete"
else
    TARGET_DOMAIN="$PRODUCTION_DOMAIN"
    OTHER_DOMAIN="$STAGING_DOMAIN"
    SSH_HOST="${PRODUCTION_SSH_HOST:-$(read_env PRODUCTION_SSH_HOST)}"
    [ -n "$SSH_HOST" ] || fail "PRODUCTION_SSH_HOST must be set (in .env or shell) for production sync"
    RSYNC_DELETE=""
fi

DUMP_FILE="db/db-export.sql"

[ -d "./uploads" ] || fail "uploads/ directory not found at $repo_root/uploads"

# --- 0. Confirmation guard for production ----------------------------------
if [ "$TARGET" = "production" ]; then
    cat <<EOF

!!! WARNING: about to overwrite PRODUCTION (https://$TARGET_DOMAIN) !!!

  - Remote DB will be dropped, recreated, and replaced with your local DB.
  - Remote uploads will be merged with ./uploads (no deletion of remote-only files).
  - A backup of the remote DB will be written to /tmp on $SSH_HOST first.

To proceed, type the production domain exactly:
  expected: $TARGET_DOMAIN

EOF
    printf '> '
    read -r confirm
    [ "$confirm" = "$TARGET_DOMAIN" ] || fail "confirmation mismatch, aborting"
fi

# --- 0b. Resolve the Coolify deployment serving TARGET_DOMAIN -------------
step "Locating the deployment serving $TARGET_DOMAIN on $SSH_HOST"
PROJECT=$(ssh "$SSH_HOST" "docker ps --filter label=com.docker.compose.service=nginx --format '{{.Label \"com.docker.compose.project\"}} {{.Labels}}'" \
    | { grep -F "Host(\`$TARGET_DOMAIN\`)" || true; } | awk '{print $1}' | sort -u)
[ -n "$PROJECT" ] || fail "no running deployment serves $TARGET_DOMAIN on $SSH_HOST"
[ "$(printf '%s\n' "$PROJECT" | wc -l)" -eq 1 ] || fail "multiple deployments serve $TARGET_DOMAIN: $PROJECT"

PHP_CONTAINER=$(ssh "$SSH_HOST" "docker ps --filter label=com.docker.compose.project=$PROJECT --filter label=com.docker.compose.service=php --format '{{.Names}}'")
[ -n "$PHP_CONTAINER" ] || fail "no running php container in $PROJECT"
[ "$(printf '%s\n' "$PHP_CONTAINER" | wc -l)" -eq 1 ] || fail "multiple php containers in $PROJECT: $PHP_CONTAINER"
info "project: $PROJECT"
info "php:     $PHP_CONTAINER"

remote_wp() { ssh "$SSH_HOST" "docker exec -i $PHP_CONTAINER wp --allow-root $1"; }

# --- 1. Export local DB ----------------------------------------------------
step "Exporting local DB with $TARGET domain replacement ($LOCAL_DOMAIN -> $TARGET_DOMAIN)"
docker compose exec -e TARGET="$TARGET" php /devops/scripts/db-export.sh
[ -f "$DUMP_FILE" ] || fail "expected $DUMP_FILE after export"

# --- 2. Backup remote DB (production only) --------------------------------
if [ "$TARGET" = "production" ]; then
    BACKUP_REMOTE="/tmp/ron-ulrich-prod-backup-$(date +%Y%m%d-%H%M%S).sql"
    step "Backing up remote DB to $SSH_HOST:$BACKUP_REMOTE"
    # mariadb-dump is not covered by the image's --skip-ssl mariadb wrapper
    remote_wp "db export - --skip-ssl > $BACKUP_REMOTE"
fi

# --- 3. Import ------------------------------------------------------------
step "Replacing remote DB with $DUMP_FILE"
remote_wp "db reset --yes"
remote_wp "db import -" < "$DUMP_FILE"

# --- 4. Point the remote DB at TARGET_DOMAIN -------------------------------
step "Replacing $OTHER_DOMAIN with $TARGET_DOMAIN and forcing https"
remote_wp "search-replace '//$OTHER_DOMAIN' '//$TARGET_DOMAIN' --all-tables --skip-columns=guid --report-changed-only"
remote_wp "search-replace 'http://$TARGET_DOMAIN' 'https://$TARGET_DOMAIN' --all-tables --skip-columns=guid --report-changed-only"
remote_wp "cache flush" || true

# --- 5. Sync uploads ------------------------------------------------------
UPLOADS_PATH=$(ssh "$SSH_HOST" "docker volume inspect ${PROJECT}_uploads --format '{{.Mountpoint}}'") \
    || fail "remote volume ${PROJECT}_uploads not found"

step "Rsyncing local uploads/ to $SSH_HOST:$UPLOADS_PATH/"
# shellcheck disable=SC2086
rsync -az $RSYNC_DELETE --stats --human-readable \
  --exclude='cache/' \
  ./uploads/ "$SSH_HOST:$UPLOADS_PATH/"

# rsync keeps the local uid, which www-data cannot write to
ssh "$SSH_HOST" "docker exec $PHP_CONTAINER chown -R www-data:www-data wp-content/uploads"

step "Done. Local DB and uploads synced to $TARGET ($TARGET_DOMAIN)."
