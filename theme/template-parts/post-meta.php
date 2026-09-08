<?php
/**
 * Post meta: tag pills, date and "published in" line.
 *
 * @package ron-ulrich
 *
 * @param array $args {
 *     @type bool  $link_tags    Link tags to their archives (teaser) or render as spans (single).
 *     @type mixed $published_in Optional pre-fetched ACF field, avoids a duplicate lookup.
 * }
 */

$published_in = $args['published_in'] ?? get_field('published_in');
$link_tags = $args['link_tags'] ?? false;
$tags = get_the_tags();
?>
<?php if ($tags) : ?>
    <div class="mb-5 flex flex-wrap">
        <?php foreach ($tags as $tag) : ?>
            <?php if ($link_tags) : ?>
                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>">
                    <span class="tag"><?php echo esc_html($tag->name); ?></span>
                </a>
            <?php else : ?>
                <span class="tag"><?php echo esc_html($tag->name); ?></span>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<span><?php echo esc_html(get_the_date('j. F Y')); ?></span>
<?php if (! empty($published_in['activate'])) : ?>
    <?php echo ' / '; ?>
    <?php esc_html_e('published in:', 'ron-ulrich'); ?>
    <a href="<?php echo esc_url($published_in['url']); ?>"><?php echo esc_html($published_in['name']); ?></a>
<?php endif; ?>
