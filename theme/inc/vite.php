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

/** Parsed Vite build manifest, or an empty array in development. */
function theme_vite_manifest(): array {
    static $manifest = null;

    return $manifest ??= theme_is_dev()
        ? []
        : json_decode(file_get_contents(get_template_directory() . '/assets/.vite/manifest.json'), true);
}

function theme_enqueue_assets(): void {
    if (theme_is_dev()) {
        // Vite injects the CSS from main.ts at runtime. Blocking rendering on the entry keeps
        // the first paint styled (no flash, view transitions see their CSS); `blocking` only
        // works in <head>, and classic themes print script modules in the footer.
        add_action('wp_head', function (): void {
            wp_print_script_tag(['type' => 'module', 'src' => theme_vite_dev_origin() . '/@vite/client']);
            wp_print_script_tag(['type' => 'module', 'src' => theme_vite_dev_origin() . '/ts/main.ts', 'blocking' => 'render']);
        });

        return;
    }

    $entry = theme_vite_manifest()['ts/main.ts'] ?? null;

    if (! $entry) {
        return;
    }

    // The stylesheet is small, so it is inlined to save a render-blocking request.
    foreach ($entry['css'] ?? [] as $i => $css) {
        wp_register_style('theme-main-' . $i, false);
        wp_enqueue_style('theme-main-' . $i);
        wp_add_inline_style('theme-main-' . $i, file_get_contents(get_template_directory() . '/assets/' . $css));
    }

    wp_enqueue_script_module('theme-main', get_template_directory_uri() . '/assets/' . $entry['file'], [], null);
}

// Preload the UI font (header tagline, category chips, meta lines) so it is ready for first paint.
add_filter('wp_preload_resources', function (array $resources): array {
    $font = theme_vite_manifest()['fonts/source-sans-3-normal-latin.woff2']['file'] ?? null;

    if ($font) {
        $resources[] = [
            'href' => get_template_directory_uri() . '/assets/' . $font,
            'as' => 'font',
            'type' => 'font/woff2',
            'crossorigin' => 'anonymous',
        ];
    }

    return $resources;
});

add_action('wp_enqueue_scripts', function (): void {
    theme_enqueue_assets();

    if (is_single() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}, 100);

/**
 * Theme-relative path of the built editor stylesheet, for add_editor_style().
 * Not hashed: WordPress inlines the file from disk, so there is nothing to
 * cache bust, and the editor keeps working while the dev server runs. It shows
 * the last `make build` result, since add_editor_style() has no dev equivalent.
 */
function theme_editor_style_path(): ?string {
    return file_exists(get_template_directory() . '/assets/css/editor.css') ? 'assets/css/editor.css' : null;
}

add_action('after_setup_theme', function (): void {
    if ($path = theme_editor_style_path()) {
        add_editor_style($path);
    }
}, 30);

add_action('enqueue_block_assets', function (): void {
    // add_editor_style() rewrites selectors to scope them to the canvas, which
    // would mangle @font-face rules, so the webfonts are enqueued directly.
    $path = get_template_directory() . '/assets/css/editor-fonts.css';

    if (is_admin() && file_exists($path)) {
        wp_enqueue_style('theme-fonts', get_template_directory_uri() . '/assets/css/editor-fonts.css', [], (string) filemtime($path));
    }
});

// Keep Fluent Forms' layout CSS but drop its default skin; main.scss styles the fields.
add_filter('fluentform/load_default_public', '__return_false');
