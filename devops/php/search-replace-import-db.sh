#!/bin/bash
set -e

if ! command -v wp &> /dev/null; then
    echo "wp-cli could not be found."
    exit 1
fi

if [ ! -f "$DB_IMPORT_FILE" ]; then
    echo "Database dump file '$DB_IMPORT_FILE' does not exist."
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

echo "Resetting the database..."
wp db reset --yes --allow-root

echo "Importing the database from $DB_IMPORT_FILE..."
wp db import "$DB_IMPORT_FILE" --allow-root

echo "Replacing '$REMOTE_DOMAIN' with '$LOCAL_DOMAIN' (target: $TARGET)..."
wp search-replace "$REMOTE_DOMAIN" "$LOCAL_DOMAIN" --allow-root

echo "Updating siteurl and home options..."
wp option update siteurl "http://$LOCAL_DOMAIN" --allow-root
wp option update home "http://$LOCAL_DOMAIN" --allow-root

echo "Database import completed successfully."
