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
#   1. Export the local DB through wp-cli with LOCAL_DOMAIN -> ENV_DOMAIN
#      search-replace (handled by db-export.sh in the php container).
#   2. (production only) Back up the remote DB to /tmp on the server.
#   3. Reset the remote DB and stream the dump into `wp db import`.
#   4. Swap any remaining OTHER_DOMAIN URLs and force https for ENV_DOMAIN.
#   5. Rsync ./uploads into the remote uploads volume (no --delete on prod).
#
# Safety:
#   - the remote deployment is resolved from the Coolify compose project
#     whose nginx serves ENV_DOMAIN (other sites share the server).
#   - production runs require typing the production domain to confirm.
#   - production runs are non-destructive for uploads (no --delete).

set -euo pipefail

TARGET="${TARGET:-}"
ENV_NAME="$TARGET"
# shellcheck source=sync-common.sh
. "$(dirname "$0")/sync-common.sh"

if [ "$TARGET" = "staging" ]; then
    RSYNC_DELETE="--delete"
else
    RSYNC_DELETE=""
fi

DUMP_FILE="db/db-export.sql"

[ -d "./uploads" ] || fail "uploads/ directory not found at $repo_root/uploads"

# --- 0. Confirmation guard for production ----------------------------------
if [ "$TARGET" = "production" ]; then
    cat <<WARN

!!! WARNING: about to overwrite PRODUCTION (https://$ENV_DOMAIN) !!!

  - Remote DB will be dropped, recreated, and replaced with your local DB.
  - Remote uploads will be merged with ./uploads (no deletion of remote-only files).
  - A backup of the remote DB will be written to /tmp on $SSH_HOST first.

To proceed, type the production domain exactly:
  expected: $ENV_DOMAIN

WARN
    printf '> '
    read -r confirm
    [ "$confirm" = "$ENV_DOMAIN" ] || fail "confirmation mismatch, aborting"
fi

resolve_deployment

# --- 1. Export local DB ----------------------------------------------------
step "Exporting local DB with $TARGET domain replacement ($LOCAL_DOMAIN -> $ENV_DOMAIN)"
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

# --- 4. Point the remote DB at ENV_DOMAIN -------------------------------
step "Replacing $OTHER_DOMAIN with $ENV_DOMAIN and forcing https"
remote_wp "search-replace '//$OTHER_DOMAIN' '//$ENV_DOMAIN' --all-tables --skip-columns=guid --report-changed-only"
remote_wp "search-replace 'http://$ENV_DOMAIN' 'https://$ENV_DOMAIN' --all-tables --skip-columns=guid --report-changed-only"
remote_wp "cache flush" || true

# --- 5. Sync uploads ------------------------------------------------------
step "Rsyncing local uploads/ to $SSH_HOST:$UPLOADS_PATH/"
# shellcheck disable=SC2086
rsync -az $RSYNC_DELETE --stats --human-readable \
  --exclude='cache/' \
  ./uploads/ "$SSH_HOST:$UPLOADS_PATH/"

# rsync keeps the local uid, which www-data cannot write to
ssh "$SSH_HOST" "docker exec $PHP_CONTAINER chown -R www-data:www-data wp-content/uploads"

step "Done. Local DB and uploads synced to $TARGET ($ENV_DOMAIN)."
