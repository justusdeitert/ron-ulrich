<?php

/**
 * 404 template.
 *
 * @package ron-ulrich
 */

get_header();

get_template_part('template-parts/page-header');

get_template_part('template-parts/content', 'none', [
    'message' => __('Sorry, but the page you were trying to view does not exist.', 'ron-ulrich'),
]);

get_footer();
