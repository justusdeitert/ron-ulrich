<?php

/**
 * Search results template.
 *
 * @package ron-ulrich
 */

get_header();

get_template_part('template-parts/page-header');

if (! have_posts()) {
    get_template_part('template-parts/content', 'none');
}

while (have_posts()) :
    the_post();
    get_template_part('template-parts/content', 'search');
endwhile;

get_template_part('template-parts/pagination');

get_footer();
