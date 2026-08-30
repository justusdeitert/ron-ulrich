<?php
/**
 * Search results template.
 *
 * @package ron-ulrich
 */

get_header();

get_template_part('template-parts/page-header');

if (! have_posts()) :
    ?>
    <div class="alert-warning">
        <?php esc_html_e('Sorry, no results were found.', 'ron-ulrich'); ?>
    </div>
    <?php get_search_form(); ?>
    <?php
endif;

while (have_posts()) :
    the_post();
    get_template_part('template-parts/content', 'search');
endwhile;

get_template_part('template-parts/pagination');

get_footer();
