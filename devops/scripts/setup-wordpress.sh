#!/bin/bash
set -e

if [ ! -f wp-includes/version.php ]; then
    wp core download --version="$WORDPRESS_VERSION" --allow-root
fi

if ! wp core is-installed --allow-root 2>/dev/null; then
    wp core install --url="$WORDPRESS_URL" --title="$WORDPRESS_TITLE" --admin_user="$WORDPRESS_ADMIN_USER" --admin_password="$WORDPRESS_ADMIN_PASSWORD" --admin_email="$WORDPRESS_ADMIN_EMAIL" --allow-root

    while IFS=: read -r slug version action; do
        [ -z "$slug" ] && continue
        wp plugin install "$slug" --version="$version" ${action:+--activate} --allow-root
    done < /devops/plugins.txt

    wp theme activate "$WORDPRESS_THEME" --allow-root

    wp plugin delete hello-dolly akismet --allow-root 2>/dev/null || true
fi