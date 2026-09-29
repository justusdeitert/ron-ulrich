<?php
/**
 * Single post content.
 *
 * @package ron-ulrich
 */

?>

<hr>

<?php get_template_part('template-parts/post-actions'); ?>

<article <?php post_class('mt-8 md:mt-10'); ?>>
    <div class="mb-3">
        <div class="kicker mb-3">
            <?php get_template_part('template-parts/post-meta', null, ['reading_time' => true]); ?>
        </div>

        <h1 class="mb-0 lg:w-[85%]"><?php the_title(); ?></h1>

        <?php if (has_excerpt()) { ?>
            <p class="lead mt-3 mb-0 lg:w-[85%]"><?php echo wp_kses_post(get_the_excerpt()); ?></p>
        <?php } ?>
    </div>

    <?php the_content(); ?>
</article>

<?php // Short posts fit on a screen or two, where the buttons above are still in reach. ?>
<?php if (theme_reading_time() >= 3) { ?>
    <hr class="mt-10 md:mt-14">

    <?php get_template_part('template-parts/post-actions'); ?>
<?php } ?>
