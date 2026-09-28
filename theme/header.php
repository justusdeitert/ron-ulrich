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

    <meta name="google-site-verification" content="EBS4XKei4WioxC3QXiRKkBuV8cAOzt0TgetW2twkO4w" />

    <meta name="application-name" content="ron-ulrich" />

    <?php // Titles, descriptions, Open Graph, canonicals and JSON-LD come from The SEO Framework. ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class('flex h-full flex-col bg-paper font-sans text-base leading-normal text-ink antialiased'); ?>>
<?php wp_body_open(); ?>

<a class="chip fixed -top-20 left-4 z-[4000] bg-paper-raised focus:top-4" href="#main"><?php esc_html_e('Zum Inhalt springen', 'ron-ulrich'); ?></a>

<header class="mb-8 pt-8 md:mb-12 md:pt-12 view-transition-site-header">
    <div class="container flex items-start">
        <a class="flex items-center gap-4" href="<?php echo esc_url(home_url('/')); ?>">
            <?php $brand_image_url = wp_get_attachment_image_url((int) get_theme_mod('custom_logo'), 'medium_large'); ?>
            <?php if ($brand_image_url) { ?>
                <div class="brand-image hidden size-[76px] shrink-0 bg-cover bg-center sm:block md:size-[96px]" style="background-image: url(<?php echo esc_url($brand_image_url); ?>)"></div>
            <?php } ?>
            <div class="flex flex-col justify-center">
                <?php
                    // Pages that render their own title as h1 demote the site name.
                    $site_title_tag = is_singular() || is_search() || is_404() ? 'p' : 'h1';
                ?>
                <<?php echo $site_title_tag; ?> class="mb-2 font-serif text-[2.25rem] font-medium leading-none tracking-[-0.02em] md:text-[3rem]"><?php bloginfo('name'); ?></<?php echo $site_title_tag; ?>>
                <p class="mb-0 font-sans text-sm font-semibold uppercase tracking-[0.12em] text-ink-600 md:text-base"><?php bloginfo('description'); ?></p>
            </div>
        </a>
        <nav class="ml-auto hidden sm:block">
            <?php
                wp_nav_menu([
                    'theme_location' => 'header_navigation',
                    'menu_class' => 'm-0 flex min-w-24 list-none flex-col items-end gap-2 p-0 lg:min-w-0 lg:flex-row lg:items-baseline lg:gap-8 [&_a]:text-sm [&_a]:font-semibold [&_a]:uppercase [&_a]:tracking-[0.08em] [&_a]:text-ink-600 [&_a:hover]:text-accent [&_.current-menu-item>a]:text-ink-900 [&_.current-menu-item>a]:underline [&_.current-menu-item>a]:decoration-accent [&_.current-menu-item>a]:decoration-2 [&_.current-menu-item>a]:underline-offset-[0.5em]',
                ]);
            ?>
        </nav>
        <button id="toggle-sidebar" type="button" class="ml-auto cursor-pointer border-0 bg-transparent p-0 text-ink-900 sm:hidden" aria-controls="sidebar" aria-expanded="false" aria-label="<?php esc_attr_e('Menü öffnen', 'ron-ulrich'); ?>">
            <span class="i-lucide-menu block size-9" aria-hidden="true"></span>
        </button>
    </div>
</header>

<?php // Outside the header: its transition name makes it a stacking context, which would trap the sidebar below the overlay. ?>
<div id="sidebar" class="fixed top-0 -left-72 z-[3000] flex h-full w-72 flex-col bg-paper-raised transition-[left,visibility] duration-300 invisible sm:hidden [.sidebar-open_&]:visible [.sidebar-open_&]:left-0 [.sidebar-open_&]:shadow-sidebar">
    <!-- py-8 matches the header's pt-8, so the close icon lines up with the burger it replaces -->
    <div class="flex items-center justify-between border-0 border-b border-solid border-line px-6 py-8">
        <span class="kicker text-base text-ink-900"><?php bloginfo('name'); ?></span>
        <button id="close-sidebar" type="button" class="cursor-pointer border-0 bg-transparent p-0 text-ink-900" aria-label="<?php esc_attr_e('Menü schließen', 'ron-ulrich'); ?>">
            <span class="i-lucide-x block size-9" aria-hidden="true"></span>
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

<main id="main" class="container flex-[1_0_auto]">
    <div class="content">
