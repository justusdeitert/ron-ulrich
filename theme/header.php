<?php
/**
 * Theme header: doctype, <head>, site header.
 *
 * @package ron-ulrich
 */

$blog_description = get_field('blog_description', 'option');
$blog_share_image = get_field('blog_share_image', 'option');
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
            <meta property="og:image" content="<?php echo esc_url($blog_share_image['url']); ?>" />
            <meta name="twitter:image" content="<?php echo esc_url($blog_share_image['url']); ?>" />
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
            <?php if ($brand_image) : ?>
                <div class="brand-image d-none d-sm-block" style="background-image: url(<?php echo esc_url($brand_image['sizes']['medium_large']); ?>)"></div>
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
