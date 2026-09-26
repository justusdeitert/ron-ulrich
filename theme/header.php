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

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons&display=swap" rel="stylesheet">

    <meta name="google-site-verification" content="EBS4XKei4WioxC3QXiRKkBuV8cAOzt0TgetW2twkO4w" />

    <meta name="application-name" content="ron-ulrich" />

    <?php // Titles, descriptions, Open Graph, canonicals and JSON-LD come from The SEO Framework. ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class('flex h-full flex-col bg-paper font-sans text-base leading-normal text-ink antialiased'); ?>>
<?php wp_body_open(); ?>

<header class="mb-8 pt-8 md:mb-12 md:pt-12">
    <div class="container flex items-start">
        <a class="flex items-center gap-4" href="<?php echo esc_url(home_url('/')); ?>">
            <?php $brand_image = get_field('brand_image', 'option'); ?>
            <?php $brand_image_url = theme_image_url($brand_image, 'medium_large'); ?>
            <?php if ($brand_image_url) { ?>
                <div class="brand-image hidden size-[76px] shrink-0 bg-cover bg-center sm:block md:size-[96px]" style="background-image: url(<?php echo esc_url($brand_image_url); ?>)"></div>
            <?php } ?>
            <div class="flex flex-col justify-center">
                <h1 class="mb-2 text-[2.25rem] leading-none tracking-[-0.02em] md:text-[3rem]"><?php bloginfo('name'); ?></h1>
                <h2 class="mb-0 font-sans text-sm font-semibold uppercase tracking-[0.12em] text-ink-600 md:text-base"><?php bloginfo('description'); ?></h2>
            </div>
        </a>
        <nav class="ml-auto hidden sm:block">
            <?php
                wp_nav_menu([
                    'theme_location' => 'header_navigation',
                    'menu_class' => 'm-0 flex min-w-24 list-none flex-col items-end gap-2 p-0 lg:min-w-0 lg:flex-row lg:items-baseline lg:gap-8 [&_a]:text-sm [&_a]:font-semibold [&_a]:uppercase [&_a]:tracking-[0.08em] [&_a]:text-ink-600 [&_a:hover]:text-accent [&_.current-menu-item>a]:text-ink-900',
                ]);
            ?>
        </nav>
        <button id="toggle-sidebar" type="button" class="ml-auto cursor-pointer border-0 bg-transparent p-0 leading-none text-ink-900 sm:hidden" aria-controls="sidebar" aria-expanded="false" aria-label="<?php esc_attr_e('Menü öffnen', 'ron-ulrich'); ?>">
            <i class="material-icons !text-4xl" aria-hidden="true">menu</i>
        </button>
        <div id="sidebar" class="fixed top-0 -left-72 z-[3000] flex h-full w-72 flex-col bg-paper-raised transition-[left] duration-300 sm:hidden [.sidebar-open_&]:left-0 [.sidebar-open_&]:shadow-sidebar">
            <!-- py-8 matches the header's pt-8, so the close icon lines up with the burger it replaces -->
            <div class="flex items-center justify-between border-0 border-b border-solid border-line px-6 py-8">
                <span class="kicker text-base text-ink-900"><?php bloginfo('name'); ?></span>
                <button id="close-sidebar" type="button" class="cursor-pointer border-0 bg-transparent p-0 leading-none text-ink-900" aria-label="<?php esc_attr_e('Menü schließen', 'ron-ulrich'); ?>">
                    <i class="material-icons !text-4xl" aria-hidden="true">close</i>
                </button>
            </div>
            <nav class="flex-1 overflow-y-auto">
                <?php
                    wp_nav_menu([
                        'theme_location' => 'header_navigation',
                        'menu_class' => 'm-0 flex list-none flex-col p-0 [&_li]:border-0 [&_li]:border-b [&_li]:border-solid [&_li]:border-line [&_li:last-child]:border-b-0 [&_a]:block [&_a]:px-6 [&_a]:py-4 [&_a]:font-serif [&_a]:text-xl [&_a]:text-ink-900 [&_.current-menu-item>a]:bg-accent-soft [&_.current-menu-item>a]:text-accent',
                    ]);
                ?>
            </nav>
        </div>
    </div>
</header>

<main class="container flex-[1_0_auto]" role="document">
    <div class="content">
