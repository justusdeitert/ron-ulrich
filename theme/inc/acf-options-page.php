<?php

/**
 * ACF options page (ported from app/acf-options-page.php).
 */

if (! defined('ABSPATH')) {
    exit;
}

if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => __('Info', 'ron-ulrich'),
        'menu_title' => __('Info', 'ron-ulrich'),
        'menu_slug' => 'information',
        'capability' => 'edit_posts',
        'position' => 50.2,
        'icon_url' => 'dashicons-admin-customizer',
    ]);
}
