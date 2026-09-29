# shellcheck shell=bash
#
# Shared helpers for sync-to-env.sh and sync-from-env.sh. Source it, don't run it.
#
# Expects ENV_NAME ("staging" | "production") to be set by the caller and sets:
#   LOCAL_DOMAIN, ENV_DOMAIN, OTHER_DOMAIN, SSH_HOST
#
# resolve_deployment additionally sets PROJECT, PHP_CONTAINER and UPLOADS_PATH.

step() { printf '\n==> %s\n' "$*"; }
info() { printf '    %s\n' "$*"; }
fail() { printf 'error: %s\n' "$*" >&2; exit 1; }

repo_root=$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)
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

case "$ENV_NAME" in
    staging)
        ENV_DOMAIN="$STAGING_DOMAIN"
        OTHER_DOMAIN="$PRODUCTION_DOMAIN"
        SSH_HOST="${STAGING_SSH_HOST:-$(read_env STAGING_SSH_HOST)}"
        SSH_HOST="${SSH_HOST:-hetzner}"
        ;;
    production)
        ENV_DOMAIN="$PRODUCTION_DOMAIN"
        OTHER_DOMAIN="$STAGING_DOMAIN"
        SSH_HOST="${PRODUCTION_SSH_HOST:-$(read_env PRODUCTION_SSH_HOST)}"
        [ -n "$SSH_HOST" ] || fail "PRODUCTION_SSH_HOST must be set (in .env or shell) for production sync"
        ;;
    *) fail "environment must be \"staging\" or \"production\" (got \"$ENV_NAME\")" ;;
esac

# Other sites share the server, so the deployment is resolved from the
# Coolify compose project whose nginx serves ENV_DOMAIN.
resolve_deployment() {
    step "Locating the deployment serving $ENV_DOMAIN on $SSH_HOST"
    PROJECT=$(ssh "$SSH_HOST" "docker ps --filter label=com.docker.compose.service=nginx --format '{{.Label \"com.docker.compose.project\"}} {{.Labels}}'" \
        | { grep -F "Host(\`$ENV_DOMAIN\`)" || true; } | awk '{print $1}' | sort -u)
    [ -n "$PROJECT" ] || fail "no running deployment serves $ENV_DOMAIN on $SSH_HOST"
    [ "$(printf '%s\n' "$PROJECT" | wc -l)" -eq 1 ] || fail "multiple deployments serve $ENV_DOMAIN: $PROJECT"

    PHP_CONTAINER=$(ssh "$SSH_HOST" "docker ps --filter label=com.docker.compose.project=$PROJECT --filter label=com.docker.compose.service=php --format '{{.Names}}'")
    [ -n "$PHP_CONTAINER" ] || fail "no running php container in $PROJECT"
    [ "$(printf '%s\n' "$PHP_CONTAINER" | wc -l)" -eq 1 ] || fail "multiple php containers in $PROJECT: $PHP_CONTAINER"

    UPLOADS_PATH=$(ssh "$SSH_HOST" "docker volume inspect ${PROJECT}_uploads --format '{{.Mountpoint}}'") \
        || fail "remote volume ${PROJECT}_uploads not found"

    info "project: $PROJECT"
    info "php:     $PHP_CONTAINER"
    info "uploads: $UPLOADS_PATH"
}

remote_wp() { ssh "$SSH_HOST" "docker exec -i $PHP_CONTAINER wp --allow-root $1"; }
