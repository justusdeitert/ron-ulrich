<?php
/**
 * Post teaser text: ACF description if set, otherwise the excerpt.
 *
 * @package ron-ulrich
 */

?>
<a href="<?php the_permalink(); ?>">
    <?php if ($description = get_field('description')) { ?>
        <p><?php echo wp_kses_post($description); ?></p>
    <?php } else { ?>
        <?php the_excerpt(); ?>
    <?php } ?>
</a>
