<?php

/**
 * Single post template.
 *
 * @package ron-ulrich
 */

get_header();

get_template_part('template-parts/loop', null, [
    'content_part' => 'single',
]);

get_footer();
