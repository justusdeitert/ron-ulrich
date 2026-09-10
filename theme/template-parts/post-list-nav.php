<?php
/**
 * Bottom bar of a post list: pagination plus the year archive select.
 *
 * @package ron-ulrich
 */

$show_archive = theme_is_archive_list();

$links = paginate_links([
    'echo' => false,
    'prev_text' => '<i class="material-icons">arrow_back</i>',
    'next_text' => '<i class="material-icons">arrow_forward</i>',
]);

if (! $links && ! $show_archive) {
    return;
}

$posts_page_url = theme_posts_page_url();
?>
<div class="flex flex-col gap-3 py-4 sm:flex-row sm:items-center">
    <?php if ($links) { ?>
        <div class="pagination flex items-center">
            <?php
                // paginate_links() returns safe HTML
                echo $links;
            ?>
        </div>
    <?php } ?>

    <?php if ($show_archive) { ?>
        <label class="flex items-center gap-2 sm:ml-auto">
            <span class="kicker"><?php esc_html_e('Archiv', 'ron-ulrich'); ?></span>
            <select name="archive-year" class="archive-select">
                <option value="<?php echo esc_url($posts_page_url); ?>"><?php esc_html_e('Alle Jahre', 'ron-ulrich'); ?></option>
                <?php
                    wp_get_archives([
                        'type' => 'yearly',
                        'format' => 'option',
                        'show_post_count' => 1,
                    ]);
                ?>
            </select>
        </label>
    <?php } ?>
</div>
<hr>
