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
    <div class="alert alert-warning">
        <?php esc_html_e('Sorry, no results were found.', 'ron-ulrich'); ?>
    </div>
    <div class="search-form-wrapper">
        <?php get_search_form(); ?>
    </div>
    <?php
endif;

while (have_posts()) :
    the_post();
    get_template_part('template-parts/content', 'search');
endwhile;

if (paginate_links()) :
    ?>
    <div class="pagination">
        <?php
        echo paginate_links([
            'prev_text' => '<i class="material-icons">arrow_left</i>',
            'next_text' => '<i class="material-icons">arrow_right</i>',
        ]);
        ?>
    </div>
    <?php
endif;

get_footer();
