<?php
/**
 * Single post content.
 *
 * @package ron-ulrich
 */

?>

<div class="py-4">
    <button class="share-button kicker flex cursor-pointer items-center gap-1 border-0 bg-transparent p-0 transition-colors hover:text-accent" type="button" data-url="<?php the_permalink(); ?>" data-title="<?php the_title_attribute(); ?>" data-copied="<?php esc_attr_e('Link kopiert', 'ron-ulrich'); ?>">
        <span class="i-lucide-share-2 size-4.5" aria-hidden="true"></span>
        <span aria-live="polite"><?php esc_html_e('Teilen', 'ron-ulrich'); ?></span>
    </button>
</div>

<hr>

<article <?php post_class('mt-10 md:mt-14'); ?>>
    <div class="mb-8 md:mb-10">
        <div class="kicker mb-4">
            <?php get_template_part('template-parts/post-meta'); ?>
        </div>

        <h1 class="mb-4 lg:w-[85%]"><?php the_title(); ?></h1>

        <?php if (has_excerpt()) { ?>
            <p class="lead mb-0 lg:w-[85%]"><?php echo wp_kses_post(get_the_excerpt()); ?></p>
        <?php } ?>

        <hr class="mt-4">
    </div>

    <?php the_content(); ?>
</article>

<?php get_template_part('template-parts/back-link'); ?>
