<?php
/**
 * Pagination (wraps paginate_links()).
 *
 * @package ron-ulrich
 */

if (! paginate_links()) {
    return;
}
?>
<hr>
<div class="pagination my-[30px] flex">
    <?php
        echo paginate_links([
            'prev_text' => '<i class="material-icons">arrow_left</i>',
            'next_text' => '<i class="material-icons">arrow_right</i>',
        ]);
    ?>
</div>
