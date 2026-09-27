<?php
/**
 * Post teaser text: ACF description if set, otherwise the excerpt.
 * The link duplicates the title link, so it is hidden from keyboard and screen readers.
 *
 * @package ron-ulrich
 */

?>
<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
    <?php if ($description = get_field('description')) { ?>
        <p><?php echo wp_kses_post($description); ?></p>
    <?php } else { ?>
        <?php the_excerpt(); ?>
    <?php } ?>
</a>
