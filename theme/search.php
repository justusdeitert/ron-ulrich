<?php

/**
 * Search results template.
 *
 * @package ron-ulrich
 */

get_header();

get_template_part('template-parts/page-header');

if (have_posts()) {
    while (have_posts()) {
        the_post();
        get_template_part('template-parts/content', 'search');
    }

    get_template_part('template-parts/post-list-nav');
} else {
    get_template_part('template-parts/content', 'none');
}

get_footer();
