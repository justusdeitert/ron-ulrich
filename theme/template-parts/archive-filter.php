<?php
/**
 * Category chips shown above the post list on the blog overview
 * and on tag/date archives.
 *
 * @package ron-ulrich
 */

$posts_page_id = (int) get_option('page_for_posts');
$posts_page_url = $posts_page_id ? get_permalink($posts_page_id) : home_url('/');
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
<hr>
<?php // Row gap comes from the chips' mb-2 so archive-filter.ts can insert zero-height spacers to even out the rows. ?>
<div class="-mb-2 flex flex-wrap gap-x-2 py-4" data-chip-row>
    <?php foreach ($filters as $filter) { ?>
        <a class="<?php echo $filter['active'] ? 'chip-active' : 'chip'; ?> mb-2" href="<?php echo esc_url($filter['url']); ?>">
            <?php echo esc_html($filter['label']); ?>
        </a>
    <?php } ?>
</div>
<hr>
