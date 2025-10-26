<?php
/**
 * Theme header: doctype, <head>, site header.
 *
 * @package ron-ulrich
 */

$scf_available   = function_exists('get_field');
$blog_description = $scf_available ? get_field('blog_description', 'option') : null;
$blog_share_image = $scf_available ? get_field('blog_share_image', 'option') : null;
?>
<!doctype html>
<html <?php language_attributes(); ?> class="<?php echo is_user_logged_in() ? 'logged-in' : ''; ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/favicon.ico'); ?>" rel="shortcut icon">
    <link href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/touch-icon.png'); ?>" rel="apple-touch-icon-precomposed">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <meta name="google-site-verification" content="EBS4XKei4WioxC3QXiRKkBuV8cAOzt0TgetW2twkO4w" />

    <meta property="og:locale" content="de_DE"/>
    <meta name="application-name" content="ron-ulrich" />
    <meta name="twitter:card" content="summary_large_image" />

    <?php if ($blog_description) : ?>
        <meta name="description" content="<?php echo esc_attr($blog_description); ?>" />
    <?php endif; ?>

    <?php if (! is_single()) : ?>
        <meta property="og:type" content="website" />
        <meta property="og:title" content="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>" />
        <meta property="og:description" content="<?php echo esc_attr($blog_description); ?>" />
        <meta property="og:url" content="<?php echo esc_url(home_url()); ?>" />
        <meta name="twitter:title" content="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>" />
        <meta name="twitter:description" content="<?php echo esc_attr($blog_description); ?>" />
        <meta name="twitter:card" content="summary_large_image" />

        <?php if ($blog_share_image) : ?>
            <?php $share_image_url = is_array($blog_share_image) ? ($blog_share_image['url'] ?? null) : $blog_share_image; ?>
            <?php if ($share_image_url) : ?>
                <meta property="og:image" content="<?php echo esc_url($share_image_url); ?>" />
                <meta name="twitter:image" content="<?php echo esc_url($share_image_url); ?>" />
            <?php endif; ?>
        <?php endif; ?>
    <?php else : ?>
        <meta property="og:type" content="website" />
        <meta property="og:title" content="<?php echo esc_attr(get_post()->post_title); ?>" />
        <meta property="og:description" content="<?php echo esc_attr(get_field('description', get_post()->ID)); ?>" />
        <meta property="og:url" content="<?php echo esc_url(get_permalink() . '?job=' . get_post()->post_name); ?>" />
        <meta name="twitter:title" content="<?php echo esc_attr(get_post()->post_title); ?>" />
        <meta name="twitter:description" content="<?php echo esc_attr(get_field('description', get_post()->ID)); ?>" />
        <meta name="twitter:card" content="summary_large_image" />

        <?php if (has_post_thumbnail()) : ?>
            <?php $feature_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true)[0]; ?>
            <meta property="og:image" content="<?php echo esc_url(home_url() . $feature_image_url); ?>" />
            <meta name="twitter:image" content="<?php echo esc_url(home_url() . $feature_image_url); ?>" />
        <?php endif; ?>
    <?php endif; ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header>
    <div class="container">
        <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
            <?php $brand_image = get_field('brand_image', 'option'); ?>
            <?php $brand_image_url = is_array($brand_image) ? ($brand_image['sizes']['medium_large'] ?? $brand_image['url'] ?? null) : $brand_image; ?>
            <?php if ($brand_image_url) : ?>
                <div class="brand-image d-none d-sm-block" style="background-image: url(<?php echo esc_url($brand_image_url); ?>)"></div>
            <?php endif; ?>
            <div class="brand-right">
                <h1><?php bloginfo('name'); ?></h1>
                <h2><?php bloginfo('description'); ?></h2>
            </div>
        </a>
        <nav class="nav-primary d-none d-sm-block">
            <?php
            wp_nav_menu([
                'theme_location' => 'header_navigation',
                'menu_class'     => 'nav-list',
            ]);
            ?>
        </nav>
        <span id="toggle-sidebar" class="sidebar-toggle d-block d-sm-none">
            <i class="material-icons">menu</i>
        </span>
        <div id="sidebar">
            <div id="sidebar-wrapper" class="sidebar-wrapper">
                <?php
                wp_nav_menu([
                    'theme_location' => 'header_navigation',
                    'menu_class'     => 'nav-list',
                ]);
                ?>
            </div>
        </div>
    </div>
</header>

<main class="container" role="document">
    <div class="content">
