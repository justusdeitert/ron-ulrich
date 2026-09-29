<?php
/**
 * Post teaser used in the blog overview.
 *
 * @package ron-ulrich
 */

$has_thumbnail = has_post_thumbnail();
// The first teaser image is the LCP candidate; everything below it can wait.
$is_first = $GLOBALS['wp_query']->current_post === 0;
?>
<article <?php post_class('mt-10 md:mt-14'); ?> data-reveal>
    <div class="grid gap-6 pb-10 sm:grid-cols-3 md:gap-8 md:pb-14">
        <?php if ($has_thumbnail) { ?>
            <a class="relative block overflow-clip bg-paper-sunken max-sm:h-56" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php
                    // Duplicates the title link, hence hidden from keyboard and screen readers.
                    // Absolutely positioned: the text column sizes the box, so the image never shifts layout.
                    the_post_thumbnail('large', [
                        'class' => 'scroll-zoom absolute inset-0 size-full object-cover',
                        'sizes' => '(min-width: 992px) 283px, (min-width: 768px) 203px, (min-width: 576px) 30vw, calc(100vw - 40px)',
                        'loading' => $is_first ? 'eager' : 'lazy',
                        'fetchpriority' => $is_first ? 'high' : 'auto',
                    ]);
                ?>
            </a>
        <?php } ?>

        <div class="sm:col-span-2">
            <div class="kicker mb-3">
                <?php get_template_part('template-parts/post-meta'); ?>
            </div>

            <a href="<?php the_permalink(); ?>">
                <h2 class="mb-3<?php echo $has_thumbnail ? '' : ' md:w-[85%]'; ?>"><?php the_title(); ?></h2>
            </a>

            <div class="mb-4 [&_p]:mb-0">
                <?php get_template_part('template-parts/content-description'); ?>
            </div>

            <a class="chip inline-flex items-center gap-1" href="<?php the_permalink(); ?>">
                <span><?php esc_html_e('weiterlesen', 'ron-ulrich'); ?><span class="sr-only">: <?php the_title(); ?></span></span>
                <span class="i-lucide-arrow-right size-5" aria-hidden="true"></span>
            </a>
        </div>
    </div>
    <hr class="[article:last-of-type_&]:hidden">
</article>
