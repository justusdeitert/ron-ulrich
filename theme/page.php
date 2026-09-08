<?php

/**
 * Page template.
 *
 * @package ron-ulrich
 */

get_header();

get_template_part('template-parts/page-header');

while (have_posts()) {
    the_post();
    get_template_part('template-parts/content', 'page');
}

get_footer();
