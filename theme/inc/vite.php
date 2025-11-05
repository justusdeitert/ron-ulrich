<?php

/**
 * Small helper around the Vite dev-server check so other code can
 * branch on it if needed.
 */

if (! defined('ABSPATH')) {
    exit;
}

function theme_is_vite_dev(): bool {
    return ! file_exists(get_template_directory() . '/assets/.vite/manifest.json');
}
