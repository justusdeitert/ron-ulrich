<?php
/**
 * The main template file.
 *
 * @package ron-ulrich
 */

get_header();
?>

<?php if (is_home()) : ?>
    <hr>
    <div class="more-info">
        <div class="info-left">
            <i class="more material-icons">arrow_drop_down</i>
            <i class="less material-icons">arrow_drop_up</i>
            <span class="more"><?php esc_html_e('More Info', 'ron-ulrich'); ?></span>
            <span class="less"><?php esc_html_e('Less Info', 'ron-ulrich'); ?></span>
        </div>
        <div class="info-right">
            <div class="input-group">
                <select name="archive-dropdown" class="custom-select" onchange="document.location.href=this.options[this.selectedIndex].value;">
                    <option value="/"><?php esc_html_e('Select Month', 'ron-ulrich'); ?></option>
                    <?php
                    wp_get_archives([
                        'type'            => 'monthly',
                        'format'          => 'option',
                        'show_post_count' => 1,
                    ]);
                    ?>
                </select>
            </div>
            <div class="input-group">
                <select name="archive-dropdown" class="custom-select" onchange="document.location.href=this.options[this.selectedIndex].value;">
                    <option value="/"><?php esc_html_e('Select Category', 'ron-ulrich'); ?></option>
                    <?php foreach (get_tags() as $tag) : ?>
                        <option value="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"><?php echo esc_html($tag->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <hr>
<?php endif; ?>

<?php
while (have_posts()) :
    the_post();
    get_template_part('template-parts/content', get_post_type());
endwhile;
?>

<?php if (paginate_links()) : ?>
    <hr>
    <div class="pagination">
        <?php
        echo paginate_links([
            'prev_text' => '<i class="material-icons">arrow_left</i>',
            'next_text' => '<i class="material-icons">arrow_right</i>',
        ]);
        ?>
    </div>
<?php endif; ?>

<?php
get_footer();
