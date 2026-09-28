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
    add_theme_support('custom-logo');
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

// Keep staging out of search engines, whatever blog_public the synced DB carries
add_filter('pre_option_blog_public', fn ($value) => wp_get_environment_type() === 'staging' ? '0' : $value);

// Fluent Forms stamps its spam token with current_time() on first interaction; submitting within 5 s is a bot.
add_filter('fluentform/token_based_validation_result', fn ($valid, $timestamp) => $valid && current_time('timestamp') - (int) $timestamp >= 5, 10, 2);

// The SEO Framework rewrites robots.txt wholesale at priority 10, which drops the
// Sitemap line core adds at priority 0. Sitemaps are core's here, so put it back.
add_filter('robots_txt', function (string $output): string {
    $sitemap = home_url('/wp-sitemap.xml');

    if (str_contains($output, $sitemap)) {
        return $output;
    }

    return rtrim($output) . "\n\nSitemap: " . esc_url_raw($sitemap) . "\n";
}, 11);

// /llms.txt (llmstxt.org): a Markdown index of the site for language models, built live like robots.txt.
add_action('parse_request', function (WP $wp): void {
    if ($wp->request !== 'llms.txt') {
        return;
    }

    $plain = fn (string $text): string => trim(preg_replace('/\s+/', ' ', html_entity_decode(wp_strip_all_tags($text), ENT_QUOTES)));
    $lines = ['# ' . $plain(get_bloginfo('name')), '', '> ' . $plain(get_bloginfo('description'))];

    foreach (['post' => __('Artikel', 'ron-ulrich'), 'page' => __('Seiten', 'ron-ulrich')] as $post_type => $heading) {
        array_push($lines, '', '## ' . $heading);

        foreach (get_posts(['post_type' => $post_type, 'numberposts' => -1, 'has_password' => false]) as $post) {
            $summary = $plain(wp_trim_words(get_the_excerpt($post), 30));
            $lines[] = sprintf('- [%s](%s)%s', $plain(get_the_title($post)), get_permalink($post), $summary ? ': ' . $summary : '');
        }
    }

    header('Content-Type: text/plain; charset=utf-8');
    echo implode("\n", $lines) . "\n";
    exit;
});

// WordPress' default of 82 is tuned for JPEG; 50 is the AVIF default of sharp and Squoosh.
add_filter('wp_editor_set_quality', function (int $quality, string $mime_type): int {
    return $mime_type === 'image/avif' ? 50 : $quality;
}, 10, 2);

// WordPress assumes content images can be as wide as the file; the content column is the `container` width.
add_filter('wp_calculate_image_sizes', function (string $sizes, array $size): string {
    return $size[0] >= 912 ? '(min-width: 992px) 912px, (min-width: 768px) 672px, calc(100vw - 40px)' : $sizes;
}, 10, 2);
