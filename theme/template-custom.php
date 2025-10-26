<?php
/**
 * Template Name: Custom Template
 *
 * @package ron-ulrich
 */

get_header();

while (have_posts()) :
    the_post();
    get_template_part('template-parts/page-header');
    get_template_part('template-parts/content', 'page');
endwhile;

get_footer();
