#!/bin/bash
set -e

export WP_CLI_PHP_ARGS="-d memory_limit=512M"

/usr/local/bin/wait-for-it.sh mysql:3306 --timeout=30 --strict -- echo "Database is up"

if ! command -v wp &> /dev/null; then
    echo "wp-cli could not be found."
    exit 1
fi

if [ ! -f wp-config.php ]; then
    echo "wp core download"
    wp core download --version="$WORDPRESS_VERSION" --allow-root

    echo "wp config create"
    wp config create --dbname="$MYSQL_DATABASE" --dbuser="$MYSQL_USER" --dbhost="$MYSQL_HOST" --dbpass="$MYSQL_PASSWORD" --allow-root

    echo "wp core install"
    wp core install --url="$WORDPRESS_URL" --title="$WORDPRESS_TITLE" --admin_user="$WORDPRESS_ADMIN_USER" --admin_password="$WORDPRESS_ADMIN_PASSWORD" --admin_email="$WORDPRESS_ADMIN_EMAIL" --allow-root

    while IFS=: read -r slug version action; do
        [ -z "$slug" ] && continue
        if [ "$action" = "activate" ]; then
            wp plugin install "$slug" --version="$version" --activate --allow-root
        else
            wp plugin install "$slug" --version="$version" --allow-root
        fi
    done < /usr/local/etc/plugins.txt

    echo "Activating theme $WORDPRESS_THEME"
    wp theme activate "$WORDPRESS_THEME" --allow-root

    wp plugin delete hello-dolly akismet --allow-root 2>/dev/null || true
fi
