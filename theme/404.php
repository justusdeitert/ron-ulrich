<?php
/**
 * 404 template.
 *
 * @package ron-ulrich
 */

get_header();

get_template_part('template-parts/page-header');
?>

<div class="alert-warning">
    <?php esc_html_e('Sorry, but the page you were trying to view does not exist.', 'ron-ulrich'); ?>
</div>

<?php get_search_form(); ?>

<?php
get_footer();
