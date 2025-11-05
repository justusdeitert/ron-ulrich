<?php

/**
 * Page template.
 *
 * @package ron-ulrich
 */

get_header();

get_template_part('template-parts/loop', null, [
    'content_part' => 'page',
    'page_header' => true,
]);

get_footer();
