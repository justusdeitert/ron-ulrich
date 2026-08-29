<?php
/**
 * 404 template.
 *
 * @package ron-ulrich
 */

get_header();
?>

<div class="mb-[60px]">
    <h2><?php echo wp_kses_post(theme_page_title()); ?></h2>
    <hr>
</div>

<div class="alert-warning">
    <?php esc_html_e('Sorry, but the page you were trying to view does not exist.', 'ron-ulrich'); ?>
</div>

<?php get_search_form(); ?>

<?php
get_footer();
