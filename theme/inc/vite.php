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
        return 'http://localhost:5174/' . ltrim($path, '/');
    }

    return get_template_directory_uri() . '/assets/' . ltrim($path, '/');
}

function theme_enqueue_assets(): void {
    if (theme_is_dev()) {
        // Dev mode: load from Vite dev server
        wp_enqueue_script_module('vite-client', 'http://localhost:5174/@vite/client', [], null);
        wp_enqueue_script_module('theme-main', 'http://localhost:5174/ts/main.ts', [], null);

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
