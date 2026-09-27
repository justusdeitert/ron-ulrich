<?php
/**
 * Post teaser text: the excerpt.
 * The link duplicates the title link, so it is hidden from keyboard and screen readers.
 *
 * @package ron-ulrich
 */

?>
<a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
    <?php the_excerpt(); ?>
</a>
