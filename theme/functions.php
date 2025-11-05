<?php

/**
 * Theme bootstrap: enqueues, shared helpers.
 */

if (! defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/vite.php';
require_once get_template_directory() . '/inc/theme-setup.php';
require_once get_template_directory() . '/inc/acf-options-page.php';
require_once get_template_directory() . '/inc/template-functions.php';

/**
 * Enqueue theme assets.
 *
 * In development (theme/assets/ does not exist) the Vite dev server on
 * http://localhost:5173 is used. In production the hashed files from
 * theme/assets/ are loaded via the Vite manifest.
 */
function theme_enqueue_assets(): void {
    $assets_dir = get_template_directory() . '/assets';

    if (! file_exists($assets_dir . '/.vite/manifest.json')) {
        // Dev mode: load from Vite dev server
        wp_enqueue_script_module('vite-client', 'http://localhost:5174/@vite/client', [], null);
        wp_enqueue_script_module('theme-main', 'http://localhost:5174/ts/main.ts', [], null);

        return;
    }

    $manifest = json_decode(file_get_contents($assets_dir . '/.vite/manifest.json'), true);
    $entry = $manifest['ts/main.ts'] ?? null;

    if (! $entry) {
        return;
    }

    $base = get_template_directory_uri() . '/assets/';

    if (! empty($entry['css'])) {
        foreach ($entry['css'] as $i => $css) {
            wp_enqueue_style('theme-main-' . $i, $base . $css, [], null);
        }
    }

    wp_enqueue_script('theme-main', $base . $entry['file'], [], null, true);

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'theme_enqueue_assets', 100);
