#!/bin/bash

cd /var/www/html

# Volume mounts can reset ownership on first boot
mkdir -p wp-content/uploads
chown -R www-data:www-data wp-content/uploads

# Subshell so a failed setup doesn't keep php-fpm from starting
(
    set -e
    if ! wp core is-installed --allow-root 2>/dev/null; then
        wp core install \
            --url="$WORDPRESS_URL" \
            --title="$WORDPRESS_TITLE" \
            --admin_user="$WORDPRESS_ADMIN_USER" \
            --admin_password="$WORDPRESS_ADMIN_PASSWORD" \
            --admin_email="$WORDPRESS_ADMIN_EMAIL" \
            --skip-email \
            --allow-root
        awk -F: '$3 == "activate" {print $1}' /usr/local/etc/plugins.txt | xargs wp plugin activate --allow-root
        wp plugin delete hello-dolly akismet --allow-root 2>/dev/null || true
    fi

    # Every boot, since a DB synced from another environment may have another theme active
    wp theme activate "$WORDPRESS_THEME" --allow-root >/dev/null
) || echo "WARNING: WordPress setup failed (see above). php-fpm will start anyway."

exec "$@"
