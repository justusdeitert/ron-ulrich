<?php
/**
 * Theme header: doctype, <head>, site header.
 *
 * @package ron-ulrich
 */

?>
<!doctype html>
<html <?php language_attributes(); ?> class="h-full">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="<?php echo esc_url(theme_public_url('images/favicon.ico')); ?>" rel="shortcut icon">
    <link href="<?php echo esc_url(theme_public_url('images/touch-icon.png')); ?>" rel="apple-touch-icon-precomposed">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons&display=swap" rel="stylesheet">

    <meta name="google-site-verification" content="EBS4XKei4WioxC3QXiRKkBuV8cAOzt0TgetW2twkO4w" />

    <meta property="og:locale" content="de_DE"/>
    <meta name="application-name" content="ron-ulrich" />

    <?php theme_social_meta(); ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class('flex h-full flex-col font-sans text-base leading-normal text-ink'); ?>>
<?php wp_body_open(); ?>

<header class="mt-10 mb-20">
    <div class="container flex">
        <a class="flex" href="<?php echo esc_url(home_url('/')); ?>">
            <?php $brand_image = get_field('brand_image', 'option'); ?>
            <?php $brand_image_url = theme_image_url($brand_image, 'medium_large'); ?>
            <?php if ($brand_image_url) { ?>
                <?php // Fixed square, matches the height of the h1 + h2 brand text block. ?>
                <div class="brand-image mr-5 hidden size-[102px] shrink-0 bg-cover bg-center sm:block" style="background-image: url(<?php echo esc_url($brand_image_url); ?>)"></div>
            <?php } ?>
            <div>
                <h1><?php bloginfo('name'); ?></h1>
                <h2><?php bloginfo('description'); ?></h2>
            </div>
        </a>
        <nav class="ml-auto hidden sm:block">
            <?php
                wp_nav_menu([
                    'theme_location' => 'header_navigation',
                    'menu_class' => 'm-0 flex min-w-24 list-none flex-col items-end gap-2.5 p-0 lg:min-w-0 lg:flex-row lg:items-start lg:gap-10',
                ]);
            ?>
        </nav>
        <span id="toggle-sidebar" class="ml-auto cursor-pointer sm:hidden">
            <i class="material-icons !text-6xl">menu</i>
        </span>
        <div id="sidebar" class="fixed top-0 -left-72 z-[3000] h-full w-72 overflow-y-auto bg-white transition-[left] duration-300 [.sidebar-open_&]:left-0 [.sidebar-open_&]:shadow-sidebar">
            <div id="sidebar-wrapper" class="flex h-full items-center justify-center text-center">
                <?php
                    wp_nav_menu([
                        'theme_location' => 'header_navigation',
                        'menu_class' => 'm-0 flex list-none flex-col items-center gap-6 p-0 [&_a]:text-2xl',
                    ]);
                ?>
            </div>
        </div>
    </div>
</header>

<main class="container flex-[1_0_auto]" role="document">
    <div class="content">
