<?php
/**
 * Pagination (wraps paginate_links()).
 *
 * @package ron-ulrich
 */

$links = paginate_links([
    'echo' => false,
    'prev_text' => '<i class="material-icons">arrow_left</i>',
    'next_text' => '<i class="material-icons">arrow_right</i>',
]);

if (! $links) {
    return;
}
?>
<hr>
<div class="pagination my-8 flex">
    <?php
        // paginate_links() returns safe HTML
        echo $links;
    ?>
</div>
