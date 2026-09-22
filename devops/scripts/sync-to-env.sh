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
#      search-replace (handled by db-export.sh in the php
#      container).
#   2. (production only) Take a timestamped backup of the remote DB.
#   3. Locate the remote MariaDB container and import the dump (drop +
#      recreate the database first).
#   4. Run wp search-replace inside the remote PHP container to swap any
#      remaining domains (production <-> staging) so the deployment always
#      ends up pointing at TARGET_DOMAIN.
#   5. Rsync ./uploads into the remote uploads volume (no --delete on prod).
#
# Safety:
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
REMOTE_DUMP="/tmp/ron-ulrich-db-sync.sql"

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

# --- 1. Export local DB ----------------------------------------------------
step "Exporting local DB with $TARGET domain replacement ($LOCAL_DOMAIN -> $TARGET_DOMAIN)"
docker compose exec -e TARGET="$TARGET" php /devops/scripts/db-export.sh
[ -f "$DUMP_FILE" ] || fail "expected $DUMP_FILE after export"

# --- 2. Discover remote MariaDB container ----------------------------------
step "Locating remote MariaDB container on $SSH_HOST"
DB_CONTAINER=$(ssh "$SSH_HOST" "docker ps --filter ancestor=mariadb:10.11 --filter name=mysql- --format '{{.Names}}' | head -n1")
[ -n "$DB_CONTAINER" ] || fail "could not find remote MariaDB container on $SSH_HOST"
info "container: $DB_CONTAINER"

# --- 2b. Backup remote DB (production only) -------------------------------
if [ "$TARGET" = "production" ]; then
    BACKUP_REMOTE="/tmp/ron-ulrich-prod-backup-$(date +%Y%m%d-%H%M%S).sql"
    step "Backing up remote DB to $SSH_HOST:$BACKUP_REMOTE"
    ssh "$SSH_HOST" "docker exec $DB_CONTAINER sh -c 'mariadb-dump -u root -p\"\$MARIADB_ROOT_PASSWORD\" \"\$MARIADB_DATABASE\"' > $BACKUP_REMOTE && ls -lh $BACKUP_REMOTE"
fi

# --- 3. Push dump and import ----------------------------------------------
step "Copying $DUMP_FILE to $SSH_HOST:$REMOTE_DUMP"
scp "$DUMP_FILE" "$SSH_HOST:$REMOTE_DUMP"

step "Importing into remote DB (drop + recreate)"
ssh "$SSH_HOST" "docker exec -i $DB_CONTAINER sh -c 'mariadb -u root -p\"\$MARIADB_ROOT_PASSWORD\" -e \"DROP DATABASE IF EXISTS \\\`\$MARIADB_DATABASE\\\`; CREATE DATABASE \\\`\$MARIADB_DATABASE\\\` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\"' \
  && docker exec -i $DB_CONTAINER sh -c 'mariadb -u root -p\"\$MARIADB_ROOT_PASSWORD\" \"\$MARIADB_DATABASE\"' < $REMOTE_DUMP \
  && rm -f $REMOTE_DUMP"

# --- 3b. Replace stray cross-environment URLs with the target domain ------
step "Locating remote PHP container on $SSH_HOST"
PHP_CONTAINER=$(ssh "$SSH_HOST" "docker ps --filter name=php- --format '{{.Names}}' | head -n1")
[ -n "$PHP_CONTAINER" ] || fail "could not find remote PHP container on $SSH_HOST"
info "container: $PHP_CONTAINER"

step "Replacing $OTHER_DOMAIN with $TARGET_DOMAIN in remote DB"
ssh "$SSH_HOST" "docker exec $PHP_CONTAINER wp --allow-root search-replace 'https://$OTHER_DOMAIN' 'https://$TARGET_DOMAIN' --all-tables --skip-columns=guid"
ssh "$SSH_HOST" "docker exec $PHP_CONTAINER wp --allow-root search-replace '//$OTHER_DOMAIN'      '//$TARGET_DOMAIN'      --all-tables --skip-columns=guid"

step "Forcing https:// for $TARGET_DOMAIN in remote DB"
ssh "$SSH_HOST" "docker exec $PHP_CONTAINER wp --allow-root search-replace 'http://$TARGET_DOMAIN' 'https://$TARGET_DOMAIN' --all-tables --skip-columns=guid"

step "Stripping /wordpress/ subdir from remote DB (local installs WP under wordpress/, remote serves from root)"
ssh "$SSH_HOST" "docker exec $PHP_CONTAINER wp --allow-root search-replace '/wordpress/wp-content/' '/wp-content/' --all-tables --skip-columns=guid"

ssh "$SSH_HOST" "docker exec $PHP_CONTAINER wp --allow-root cache flush || true"

# --- 4. Sync uploads ------------------------------------------------------
step "Locating remote uploads volume on $SSH_HOST"
UPLOADS_VOLUME=$(ssh "$SSH_HOST" "docker volume ls --format '{{.Name}}' | grep -E '_uploads\$' | head -n1")
[ -n "$UPLOADS_VOLUME" ] || fail "could not find remote uploads volume"
info "volume: $UPLOADS_VOLUME"

UPLOADS_PATH=$(ssh "$SSH_HOST" "docker volume inspect $UPLOADS_VOLUME --format '{{.Mountpoint}}'")
[ -n "$UPLOADS_PATH" ] || fail "could not resolve uploads volume mountpoint"
info "path:   $UPLOADS_PATH"

step "Rsyncing local uploads/ to $SSH_HOST:$UPLOADS_PATH/"
# shellcheck disable=SC2086
rsync -az $RSYNC_DELETE --stats --human-readable \
  --exclude='cache/' \
  ./uploads/ "$SSH_HOST:$UPLOADS_PATH/"

step "Done. Local DB and uploads synced to $TARGET ($TARGET_DOMAIN)."
