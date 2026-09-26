#!/bin/bash
set -e

case "${TARGET:=production}" in
    staging) REMOTE_DOMAIN=$STAGING_DOMAIN ;;
    production) REMOTE_DOMAIN=$PRODUCTION_DOMAIN ;;
    *) echo "TARGET must be 'staging' or 'production' (got '$TARGET')" >&2; exit 1 ;;
esac
: "${REMOTE_DOMAIN:?domain for $TARGET is not set in .env}"

wp search-replace "$LOCAL_DOMAIN" "$REMOTE_DOMAIN" --export="$DB_EXPORT_FILE" --allow-root
