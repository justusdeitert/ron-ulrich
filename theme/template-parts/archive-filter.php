<?php
/**
 * Category chips shown above the post list on the blog overview
 * and on tag/date archives.
 *
 * @package ron-ulrich
 */

$posts_page_url = theme_posts_page_url();
$current_tag_id = is_tag() ? (int) get_queried_object_id() : 0;

$filters = [[
    'label' => __('Alle', 'ron-ulrich'),
    'url' => $posts_page_url,
    'active' => $current_tag_id === 0,
]];

foreach (get_tags() as $tag) {
    $filters[] = [
        'label' => $tag->name,
        'url' => get_tag_link($tag->term_id),
        'active' => $current_tag_id === (int) $tag->term_id,
    ];
}
?>
<div class="view-transition-archive-filter">
    <hr>
    <?php // Inline chips instead of flex, so text-wrap: balance can spread them evenly across rows. ?>
    <nav class="-mb-2 py-4 text-balance" aria-label="<?php esc_attr_e('Kategorien', 'ron-ulrich'); ?>">
        <?php foreach ($filters as $filter) { ?>
            <a class="<?php echo $filter['active'] ? 'chip-active' : 'chip'; ?> mb-2 mr-1 align-top" href="<?php echo esc_url($filter['url']); ?>"<?php echo $filter['active'] ? ' aria-current="page"' : ''; ?>>
                <?php echo esc_html($filter['label']); ?>
            </a>
        <?php } ?>
    </nav>
</div>
