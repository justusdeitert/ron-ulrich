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
 * Canonical post URL with the ?job= tracking parameter.
 */
function theme_share_url(): string {
    return add_query_arg('job', get_post()->post_name, get_permalink());
}

/**
 * Render Open Graph / Twitter Card meta tags for the current request.
 * Called from header.php inside <head>.
 */
function theme_social_meta(): void {
    $blog_description = get_field('blog_description', 'option');
    $blog_share_image = get_field('blog_share_image', 'option');

    if (! is_single()) {
        $title = get_bloginfo('name') . ' - ' . get_bloginfo('description');
        $description = $blog_description;
        $url = home_url();
        $image_url = $blog_share_image ? theme_image_url($blog_share_image) : null;
    } else {
        $post = get_post();
        $title = $post->post_title;
        $description = get_field('description', $post->ID);
        $url = theme_share_url();
        $image_url = theme_image_url(get_post_thumbnail_id(), 'large');
    }

    $description = (string) ($description ?? '');
    ?>
    <?php if ($description) : ?>
        <meta name="description" content="<?php echo esc_attr($description); ?>" />
    <?php endif; ?>
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo esc_attr($title); ?>" />
    <meta property="og:description" content="<?php echo esc_attr($description); ?>" />
    <meta property="og:url" content="<?php echo esc_url($url); ?>" />
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>" />
    <meta name="twitter:card" content="summary_large_image" />
    <?php if ($image_url) : ?>
        <meta property="og:image" content="<?php echo esc_url($image_url); ?>" />
        <meta name="twitter:image" content="<?php echo esc_url($image_url); ?>" />
    <?php endif; ?>
    <?php
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
        return sprintf(__('Search Results for %s', 'ron-ulrich'), get_search_query());
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
