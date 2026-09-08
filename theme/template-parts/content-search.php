<?php
/**
 * Search result item.
 *
 * @package ron-ulrich
 */

?>
<article <?php post_class('mt-[60px]'); ?>>
    <h2 class="mb-5"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php get_template_part('template-parts/content-description'); ?>
</article>

<hr>
