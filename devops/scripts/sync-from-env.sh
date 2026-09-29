#!/usr/bin/env bash
#
# Pull the DB and uploads of a remote WordPress deployment running on
# Coolify (staging or production) into the local development environment.
#
# Usage:
#   SOURCE=staging    ./devops/scripts/sync-from-env.sh
#   SOURCE=production ./devops/scripts/sync-from-env.sh
#
# Required env vars (read from .env unless overridden in the shell):
#   LOCAL_DOMAIN
#   STAGING_DOMAIN     | PRODUCTION_DOMAIN
#   STAGING_SSH_HOST   | PRODUCTION_SSH_HOST
#
# Steps:
#   1. Back up the local DB to db/db-local-backup-<timestamp>.sql.
#   2. Dump the remote DB into db/db-import.sql.
#   3. Import it locally with SOURCE_DOMAIN -> LOCAL_DOMAIN search-replace
#      (handled by db-import.sh in the php container).
#   4. Rsync the remote uploads volume into ./uploads.
#
# Safety:
#   - read-only on the remote side.
#   - local-only uploads are kept (no --delete).

set -euo pipefail

SOURCE="${SOURCE:-}"
ENV_NAME="$SOURCE"
# shellcheck source=sync-common.sh
. "$(dirname "$0")/sync-common.sh"

DUMP_FILE="db/db-import.sql"
BACKUP_FILE="db/db-local-backup-$(date +%Y%m%d-%H%M%S).sql"

[ -d "./uploads" ] || fail "uploads/ directory not found at $repo_root/uploads"

# --- 1. Backup local DB (also fails early if the local stack is down) -----
step "Backing up local DB to $BACKUP_FILE"
# mariadb-dump is not covered by the image's --skip-ssl mariadb wrapper
docker compose exec -T php wp db export "/$BACKUP_FILE" --skip-ssl --allow-root

resolve_deployment

# --- 2. Dump remote DB ----------------------------------------------------
step "Dumping remote DB to $DUMP_FILE"
# Write to a temp file so a failed dump does not clobber the previous one
remote_wp "db export - --skip-ssl" > "$DUMP_FILE.tmp"
tail -n1 "$DUMP_FILE.tmp" | grep -q '^-- Dump completed' \
    || { rm -f "$DUMP_FILE.tmp"; fail "remote dump is incomplete"; }
mv "$DUMP_FILE.tmp" "$DUMP_FILE"

# --- 3. Import locally ----------------------------------------------------
step "Importing $DUMP_FILE with $SOURCE domain replacement ($ENV_DOMAIN -> $LOCAL_DOMAIN)"
docker compose exec -T -e TARGET="$SOURCE" php /devops/scripts/db-import.sh

# --- 4. Sync uploads ------------------------------------------------------
step "Rsyncing $SSH_HOST:$UPLOADS_PATH/ to local uploads/"
rsync -az --stats --human-readable \
  --exclude='cache/' \
  "$SSH_HOST:$UPLOADS_PATH/" ./uploads/

step "Done. $SOURCE ($ENV_DOMAIN) DB and uploads pulled to local."
