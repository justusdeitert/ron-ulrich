<?php
/**
 * Search result item.
 *
 * @package ron-ulrich
 */

?>
<article <?php post_class('article-overview'); ?>>
    <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <a href="<?php the_permalink(); ?>">
        <?php if (get_field('description')) : ?>
            <p><?php echo wp_kses_post(get_field('description')); ?></p>
        <?php else : ?>
            <?php the_excerpt(); ?>
        <?php endif; ?>
    </a>
</article>

<hr>
