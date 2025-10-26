#!/bin/bash
set -e

if ! command -v wp &> /dev/null; then
    echo "wp-cli could not be found."
    exit 1
fi

TARGET="${TARGET:-production}"

case "$TARGET" in
    staging)
        REMOTE_DOMAIN="$STAGING_DOMAIN"
        ;;
    production)
        REMOTE_DOMAIN="$PRODUCTION_DOMAIN"
        ;;
    *)
        echo "Unknown TARGET '$TARGET'. Use 'staging' or 'production'."
        exit 1
        ;;
esac

if [ -z "$REMOTE_DOMAIN" ]; then
    echo "Domain for target '$TARGET' is not set in .env."
    exit 1
fi

echo "Exporting the database to $DB_EXPORT_FILE (target: $TARGET, domain: $REMOTE_DOMAIN)..."
wp search-replace "$LOCAL_DOMAIN" "$REMOTE_DOMAIN" --export="$DB_EXPORT_FILE" --allow-root

if [ -f "$DB_EXPORT_FILE" ]; then
    echo "Database export completed successfully: $DB_EXPORT_FILE"
else
    echo "Database export failed."
    exit 1
fi
