<?php

/**
 * Theme setup: theme supports, menus, editor styles.
 */

if (! defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', function (): void {
    // Let WordPress manage the document title
    add_theme_support('title-tag');

    register_nav_menus([
        'header_navigation' => __('Header Navigation', 'ron-ulrich'),
        'footer_navigation' => __('Footer Navigation', 'ron-ulrich'),
    ]);

    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form', 'script', 'style']);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('editor-styles');
    add_theme_support('responsive-embeds');

    load_theme_textdomain('ron-ulrich', get_template_directory() . '/lang');
}, 20);

add_action('admin_menu', function (): void {
    // Keep admin pages reachable for administrators; hide them from editors.
    if (current_user_can('manage_options')) {
        return;
    }

    remove_menu_page('edit-comments.php');
    remove_menu_page('tools.php');
    remove_menu_page('users.php');
    remove_menu_page('plugins.php');
});

// The SEO Framework rewrites robots.txt wholesale at priority 10, which drops the
// Sitemap line core adds at priority 0. Sitemaps are core's here, so put it back.
add_filter('robots_txt', function (string $output): string {
    $sitemap = home_url('/wp-sitemap.xml');

    if (str_contains($output, $sitemap)) {
        return $output;
    }

    return rtrim($output) . "\n\nSitemap: " . esc_url_raw($sitemap) . "\n";
}, 11);

// WordPress' default of 82 is tuned for JPEG; at that level GD's AVIF output is barely smaller than the JPEG.
add_filter('wp_editor_set_quality', function (int $quality, string $mime_type): int {
    return $mime_type === 'image/avif' ? 60 : $quality;
}, 10, 2);

// WordPress assumes content images can be as wide as the file; the content column is the `container` width.
add_filter('wp_calculate_image_sizes', function (string $sizes, array $size): string {
    return $size[0] >= 912 ? '(min-width: 992px) 912px, (min-width: 768px) 672px, calc(100vw - 40px)' : $sizes;
}, 10, 2);
