<?php
/**
 * Shared template helpers used across template parts.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Context-aware page title (ported from the Sage App controller).
 */
function theme_page_title(): string
{
    if (is_home()) {
        if ($home = get_option('page_for_posts', true)) {
            return get_the_title($home);
        }

        return __('Latest Posts', 'ron-ulrich');
    }

    if (is_archive()) {
        return get_the_archive_title();
    }

    if (is_search()) {
        return sprintf(__('Search Results for %s', 'ron-ulrich'), get_search_query());
    }

    if (is_404()) {
        return __('Not Found', 'ron-ulrich');
    }

    return get_the_title();
}

/**
 * "… Continued" link appended to excerpts.
 */
add_filter('excerpt_more', function (): string {
    return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'ron-ulrich') . '</a>';
});

/**
 * Add the page slug to the body classes.
 */
add_filter('body_class', function (array $classes): array {
    if (is_single() || (is_page() && ! is_front_page())) {
        if (! in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }

    return array_filter($classes);
});
