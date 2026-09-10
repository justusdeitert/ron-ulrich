<?php
/**
 * The main template file.
 *
 * @package ron-ulrich
 */

get_header();

if (theme_is_archive_list()) {
    get_template_part('template-parts/archive-filter');
}

while (have_posts()) {
    the_post();
    get_template_part('template-parts/content', get_post_type());
}

get_template_part('template-parts/post-list-nav');

get_footer();
