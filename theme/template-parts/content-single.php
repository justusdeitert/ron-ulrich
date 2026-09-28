<?php
/**
 * Single post content.
 *
 * @package ron-ulrich
 */

?>

<?php get_template_part('template-parts/post-actions'); ?>

<hr>

<article <?php post_class('mt-10 md:mt-14'); ?>>
    <div class="mb-6">
        <div class="kicker mb-4">
            <?php get_template_part('template-parts/post-meta'); ?>
        </div>

        <h1 class="mb-0 lg:w-[85%]"><?php the_title(); ?></h1>

        <?php if (has_excerpt()) { ?>
            <p class="lead mt-4 mb-0 lg:w-[85%]"><?php echo wp_kses_post(get_the_excerpt()); ?></p>
        <?php } ?>
    </div>

    <?php the_content(); ?>
</article>

<div class="mt-10 md:mt-14">
    <?php get_template_part('template-parts/post-actions'); ?>
</div>
<hr>
