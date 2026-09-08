<?php

/**
 * Single post template.
 *
 * @package ron-ulrich
 */

get_header();

while (have_posts()) {
    the_post();
    get_template_part('template-parts/content', 'single');
}

get_footer();
