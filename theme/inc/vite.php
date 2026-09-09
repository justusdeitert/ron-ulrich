<?php

/**
 * Vite asset loading.
 *
 * In development (no build manifest) assets are served by the Vite dev
 * server with HMR. In production the hashed files from theme/assets/
 * are loaded via the Vite manifest.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Origin of the Vite dev server. Overridable via THEME_VITE_DEV_ORIGIN
 * (e.g. when accessing the Docker stack from another machine on the LAN).
 */
const THEME_VITE_DEV_ORIGIN = 'http://localhost:5174';

function theme_vite_dev_origin(): string {
    return defined('THEME_VITE_DEV_ORIGIN_OVERRIDE') ? THEME_VITE_DEV_ORIGIN_OVERRIDE : THEME_VITE_DEV_ORIGIN;
}

/**
 * Whether the theme runs in development mode: no build manifest, so
 * assets are served by the Vite dev server instead of theme/assets/.
 */
function theme_is_dev(): bool {
    static $is_dev = null;

    return $is_dev ??= ! file_exists(get_template_directory() . '/assets/.vite/manifest.json');
}

/**
 * URL for a static file in theme/public/ (copied verbatim into the
 * build output). Served by the Vite dev server in development.
 */
function theme_public_url(string $path): string {
    if (theme_is_dev()) {
        return theme_vite_dev_origin() . '/' . ltrim($path, '/');
    }

    return get_template_directory_uri() . '/assets/' . ltrim($path, '/');
}

function theme_enqueue_assets(): void {
    if (theme_is_dev()) {
        // Dev mode: load from Vite dev server
        wp_enqueue_script_module('vite-client', theme_vite_dev_origin() . '/@vite/client', [], null);
        wp_enqueue_script_module('theme-main', theme_vite_dev_origin() . '/ts/main.ts', [], null);

        return;
    }

    $manifest = json_decode(file_get_contents(get_template_directory() . '/assets/.vite/manifest.json'), true);
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

    wp_enqueue_script_module('theme-main', $base . $entry['file'], [], null);
}

add_action('wp_enqueue_scripts', function (): void {
    theme_enqueue_assets();

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}, 100);

// CF7 ships its CSS/JS on every page; only load them where a form exists.
add_filter('wpcf7_load_js', '__return_false');
add_filter('wpcf7_load_css', '__return_false');

add_action('wp_enqueue_scripts', function (): void {
    if (function_exists('wpcf7_enqueue_scripts') && is_singular() && has_shortcode(get_post()->post_content ?? '', 'contact-form-7')) {
        wpcf7_enqueue_scripts();
        wpcf7_enqueue_styles();
    }
}, 200);
