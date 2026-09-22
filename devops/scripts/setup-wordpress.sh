#!/bin/bash
set -e

export WP_CLI_PHP_ARGS="-d memory_limit=512M"

if [ ! -f wp-includes/version.php ]; then
    echo "wp core download"
    wp core download --version="$WORDPRESS_VERSION" --allow-root
fi

if [ ! -f wp-config.php ]; then
    echo "wp config create"
    wp config create --dbname="$MYSQL_DATABASE" --dbuser="$MYSQL_USER" --dbhost="$MYSQL_HOST" --dbpass="$MYSQL_PASSWORD" --allow-root --extra-php <<'PHP'
define( 'WP_ENVIRONMENT_TYPE', 'local' );
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_CACHE', false );

// Follow the request host so the site also works on LAN IPs. Trusts the Host header, dev only.
if ( isset( $_SERVER['HTTP_HOST'] ) ) {
	define( 'WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] );
	define( 'WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST'] );
}
PHP
fi

if ! wp core is-installed --allow-root 2>/dev/null; then
    echo "wp core install"
    wp core install --url="$WORDPRESS_URL" --title="$WORDPRESS_TITLE" --admin_user="$WORDPRESS_ADMIN_USER" --admin_password="$WORDPRESS_ADMIN_PASSWORD" --admin_email="$WORDPRESS_ADMIN_EMAIL" --allow-root

    while IFS=: read -r slug version action; do
        [ -z "$slug" ] && continue
        if [ "$action" = "activate" ]; then
            wp plugin install "$slug" --version="$version" --activate --allow-root
        else
            wp plugin install "$slug" --version="$version" --allow-root
        fi
    done < /devops/plugins.txt

    echo "Activating theme $WORDPRESS_THEME"
    wp theme activate "$WORDPRESS_THEME" --allow-root

    wp plugin delete hello-dolly akismet --allow-root 2>/dev/null || true
fi