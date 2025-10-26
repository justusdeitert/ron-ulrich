<?php
/**
 * 404 template.
 *
 * @package ron-ulrich
 */

get_header();

get_template_part('template-parts/page-header');
?>

<div class="alert alert-warning">
    <?php esc_html_e('Sorry, but the page you were trying to view does not exist.', 'ron-ulrich'); ?>
</div>

<div class="search-form-wrapper">
    <?php get_search_form(); ?>
</div>

<?php
get_footer();
