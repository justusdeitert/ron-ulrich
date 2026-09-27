<?php

/**
 * Shared template helpers used across template parts.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * Normalize an image-ish ACF value that may come back as an array (new),
 * a plain URL string (old export), or a numeric attachment ID (old DB).
 */
function theme_image_url($value, string $size = 'large'): ?string {
    if (! $value) {
        return null;
    }

    if (is_array($value)) {
        return $value['sizes'][$size] ?? $value['url'] ?? null;
    }

    if (is_numeric($value)) {
        $src = wp_get_attachment_image_src((int) $value, $size);

        return $src ? $src[0] : null;
    }

    return (string) $value;
}

/**
 * Canonical post URL, used to build the share links on single posts.
 */
function theme_share_url(): string {
    return get_permalink();
}

/**
 * URL of the blog overview: the assigned posts page, or the front page
 * when the site shows posts there.
 */
function theme_posts_page_url(): string {
    $posts_page_id = (int) get_option('page_for_posts');

    return $posts_page_id ? get_permalink($posts_page_id) : home_url('/');
}

/**
 * Whether the current request is a post list that offers archive navigation:
 * the blog overview and the tag/date archives it links to.
 */
function theme_is_archive_list(): bool {
    return is_home() || is_tag() || is_date();
}

/**
 * Context-aware page title (ported from the Sage App controller).
 */
function theme_page_title(): string {
    if (is_home()) {
        if ($home = get_option('page_for_posts')) {
            return get_the_title($home);
        }

        return __('Latest Posts', 'ron-ulrich');
    }

    if (is_archive()) {
        return get_the_archive_title();
    }

    if (is_search()) {
        return sprintf(__('Search Results for %s', 'ron-ulrich'), get_search_query(false));
    }

    if (is_404()) {
        return __('Not Found', 'ron-ulrich');
    }

    return get_the_title();
}

/**
 * "…" appended to excerpts. The teaser wraps the whole excerpt in a
 * permalink anchor, so a separate "Continued" link would nest anchors.
 */
add_filter('excerpt_more', function (): string {
    return ' &hellip;';
});

// Pasted embeds (YouTube, Giphy, Spotify) often lack the title screen readers announce for a frame.
add_filter('the_content', function (string $content): string {
    $tags = new WP_HTML_Tag_Processor($content);

    while ($tags->next_tag('iframe')) {
        if (! $tags->get_attribute('title')) {
            $host = preg_replace('/^www\./', '', (string) wp_parse_url((string) $tags->get_attribute('src'), PHP_URL_HOST));
            /* translators: %s: host name of the embedded page, e.g. youtube.com */
            $tags->set_attribute('title', $host ? sprintf(__('Eingebetteter Inhalt von %s', 'ron-ulrich'), $host) : __('Eingebetteter Inhalt', 'ron-ulrich'));
        }
    }

    return $tags->get_updated_html();
}, 20);

/**
 * Add the page slug to the body classes.
 */
add_filter('body_class', function (array $classes): array {
    if (is_single() || (is_page() && ! is_front_page())) {
        $slug = get_post_field('post_name', get_post());

        if ($slug && ! in_array($slug, $classes, true)) {
            $classes[] = $slug;
        }
    }

    return array_filter($classes);
});
