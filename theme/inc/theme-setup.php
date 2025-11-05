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
    add_theme_support('align-wide');

    load_theme_textdomain('ron-ulrich', get_template_directory() . '/lang');
}, 20);

/**
 * Slim down wp-admin for editors (front-end only check preserved
 * from the original theme).
 */
if (! is_admin()) {
    // Hide ACF menu on the front-end
    add_filter('acf/settings/show_admin', '__return_false');
}

add_action('admin_menu', function (): void {
    remove_menu_page('edit-comments.php');
    remove_menu_page('tools.php');
    remove_menu_page('users.php');
    remove_menu_page('plugins.php');
});
