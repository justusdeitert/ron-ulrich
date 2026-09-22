#!/bin/bash

cd /var/www/html

# Ensure the uploads directory exists and is writable by www-data
# (volume mount can reset ownership on first boot).
mkdir -p wp-content/uploads
chown -R www-data:www-data wp-content/uploads

# Install WordPress on first boot if no tables exist yet.
# Wrapped in a subshell so a failure here does not prevent php-fpm from starting.
(
    set -e
    if ! wp core is-installed --allow-root 2>/dev/null; then
        echo "WordPress not installed yet, running wp core install..."
        wp core install \
            --url="${WORDPRESS_URL}" \
            --title="${WORDPRESS_TITLE:-Ron Ulrich}" \
            --admin_user="${WORDPRESS_ADMIN_USER}" \
            --admin_password="${WORDPRESS_ADMIN_PASSWORD}" \
            --admin_email="${WORDPRESS_ADMIN_EMAIL}" \
            --skip-email \
            --allow-root

        # Activate plugins marked with :activate in plugins.txt
        while IFS=: read -r slug _version action; do
            [ "$action" = "activate" ] && wp plugin activate "$slug" --allow-root || true
        done < /usr/local/etc/plugins.txt

        wp theme activate "${WORDPRESS_THEME:-ron-ulrich-theme}" --allow-root || true

        wp plugin delete hello-dolly akismet --allow-root 2>/dev/null || true
    fi

    # Make sure the configured theme is active even on subsequent boots (after DB import etc.).
    wp theme activate "${WORDPRESS_THEME:-ron-ulrich-theme}" --allow-root >/dev/null 2>&1 || true

    # The theme uses header_navigation / footer_navigation; map the existing
    # "Header Menu" / "Footer Menu" onto them (idempotent, safe to re-run).
    HEADER_MENU=$(wp menu list --format=csv --allow-root 2>/dev/null | awk -F'\t' 'NR>1 && $2=="Header Menu" {print $1}')
    FOOTER_MENU=$(wp menu list --format=csv --allow-root 2>/dev/null | awk -F'\t' 'NR>1 && $2=="Footer Menu" {print $1}')
    if [ -n "$HEADER_MENU" ] && [ -n "$FOOTER_MENU" ]; then
        wp eval "set_theme_mod('nav_menu_locations', ['header_navigation' => (int) $HEADER_MENU, 'footer_navigation' => (int) $FOOTER_MENU]);" --allow-root >/dev/null 2>&1 || true
    fi

    # One-time content migration: old Bedrock stored media URLs as
    # /app/uploads/..., standard WordPress serves them from /wp-content/uploads/.
    # Idempotent (0 replacements once done).
    wp search-replace '/app/uploads/' '/wp-content/uploads/' --allow-root >/dev/null 2>&1 || true
) || echo "WARNING: WordPress setup failed (see above). php-fpm will start anyway."

exec "$@"
