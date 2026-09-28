<?php
/**
 * Search result item.
 *
 * @package ron-ulrich
 */

?>
<article <?php post_class('mt-10 md:mt-14'); ?> data-reveal>
    <h2 class="mb-3"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php get_template_part('template-parts/content-description'); ?>
    <hr class="[article:last-of-type_&]:hidden">
</article>
