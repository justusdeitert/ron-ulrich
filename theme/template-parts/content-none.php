<?php
/**
 * "No results" block: warning alert + search form.
 *
 * @package ron-ulrich
 *
 * @param array $args {
 *     @type string $message Message shown in the alert.
 * }
 */

$message = $args['message'] ?? __('Sorry, no results were found.', 'ron-ulrich');
?>
<div class="alert-warning">
    <?php echo esc_html($message); ?>
</div>

<?php get_search_form(); ?>
