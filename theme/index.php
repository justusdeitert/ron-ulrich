<?php
/**
 * The main template file.
 *
 * @package ron-ulrich
 */

get_header();
?>

<?php if (is_home()) { ?>
    <hr>
    <div class="more-info my-2.5 flex cursor-pointer items-center">
        <div class="info-left flex items-center">
            <i class="material-icons [body.more-info_&]:hidden">arrow_drop_down</i>
            <i class="material-icons hidden [body.more-info_&]:inline">arrow_drop_up</i>
            <span class="[body.more-info_&]:hidden"><?php esc_html_e('Mehr Info', 'ron-ulrich'); ?></span>
            <span class="hidden [body.more-info_&]:inline"><?php esc_html_e('Weniger Info', 'ron-ulrich'); ?></span>
        </div>
        <div class="ml-auto flex flex-col gap-2.5 sm:flex-row sm:gap-5">
            <select name="archive-month" class="archive-select">
                <option value="/"><?php esc_html_e('Monat auswählen', 'ron-ulrich'); ?></option>
                <?php
                    wp_get_archives([
                        'type' => 'monthly',
                        'format' => 'option',
                        'show_post_count' => 1,
                    ]);
                ?>
            </select>
            <select name="archive-tag" class="archive-select">
                <option value="/"><?php esc_html_e('Kategorie auswählen', 'ron-ulrich'); ?></option>
                <?php foreach (get_tags() as $tag) { ?>
                    <option value="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"><?php echo esc_html($tag->name); ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <hr>
<?php } ?>

<?php
while (have_posts()) {
    the_post();
    get_template_part('template-parts/content', get_post_type());
}

get_template_part('template-parts/pagination');

get_footer();
