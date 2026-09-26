#!/bin/bash
set -e

case "${TARGET:=production}" in
    staging) REMOTE_DOMAIN=$STAGING_DOMAIN ;;
    production) REMOTE_DOMAIN=$PRODUCTION_DOMAIN ;;
    *) echo "TARGET must be 'staging' or 'production' (got '$TARGET')" >&2; exit 1 ;;
esac
: "${REMOTE_DOMAIN:?domain for $TARGET is not set in .env}"

wp db reset --yes --allow-root
wp db import "$DB_IMPORT_FILE" --allow-root
# Remote sites run on https, the local one on http
wp search-replace "https://$REMOTE_DOMAIN" "http://$LOCAL_DOMAIN" --allow-root
wp search-replace "$REMOTE_DOMAIN" "$LOCAL_DOMAIN" --allow-root
