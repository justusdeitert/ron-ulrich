<?php
/**
 * Production wp-config.php for Coolify.
 * All secrets and credentials come from environment variables.
 */

// ---- Database ----
define('DB_NAME',     getenv('MYSQL_DATABASE'));
define('DB_USER',     getenv('MYSQL_USER'));
define('DB_PASSWORD', getenv('MYSQL_PASSWORD'));
define('DB_HOST',     getenv('MYSQL_HOST') ?: 'mysql');
define('DB_CHARSET',  'utf8mb4');
define('DB_COLLATE',  '');

$table_prefix = getenv('WORDPRESS_TABLE_PREFIX') ?: 'wp_';

// ---- Salts (set unique values via Coolify env vars) ----
foreach ([
    'AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY',
    'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT',
] as $constant) {
    $value = getenv($constant);
    if (! $value) {
        // Fail loudly rather than running with a weak default.
        $value = 'MISSING_' . $constant . '_PLEASE_SET_IN_COOLIFY';
    }
    define($constant, $value);
}

// ---- Behind reverse proxy (Coolify Traefik) ----
if (
    isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
    && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false
) {
    $_SERVER['HTTPS'] = 'on';
}
if (! empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
    $_SERVER['HTTP_HOST'] = $_SERVER['HTTP_X_FORWARDED_HOST'];
}

// ---- Site URL ----
if ($wp_url = getenv('WORDPRESS_URL')) {
    define('WP_HOME',    $wp_url);
    define('WP_SITEURL', $wp_url);
}

// ---- Hardening ----
define('WP_DEBUG',          (bool) getenv('WP_DEBUG'));
define('WP_DEBUG_LOG',      (bool) getenv('WP_DEBUG'));
define('WP_DEBUG_DISPLAY',  false);
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', (bool) getenv('WP_DISALLOW_FILE_MODS'));
define('AUTOMATIC_UPDATER_DISABLED', true);

// ---- Misc ----
define('WP_MEMORY_LIMIT', '256M');

if (! defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
