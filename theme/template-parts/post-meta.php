<?php
/**
 * Post meta: tags and date, plus the reading time on single posts.
 *
 * @package ron-ulrich
 *
 * @param array $args {
 *     @type bool $inline Render a single "Tag · Tag · Datum" line (teaser) instead of tag pills above the date and reading time.
 * }
 */

$inline = $args['inline'] ?? false;
$tags = get_the_tags() ?: [];
?>
<?php if ($inline) { ?>
    <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
        <?php foreach ($tags as $tag) { ?>
            <a class="text-ink-900 transition-colors hover:text-accent" href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"><?php echo esc_html($tag->name); ?></a>
            <span class="text-ink-400" aria-hidden="true">&middot;</span>
        <?php } ?>
        <time class="text-ink-500" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j. F Y')); ?></time>
    </div>
<?php } else { ?>
    <?php if ($tags) { ?>
        <div class="mb-3 flex flex-wrap gap-2">
            <?php foreach ($tags as $tag) { ?>
                <a class="chip" href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"><?php echo esc_html($tag->name); ?></a>
            <?php } ?>
        </div>
    <?php } ?>

    <div class="flex flex-wrap items-center gap-x-2">
        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j. F Y')); ?></time>
        <span class="text-ink-400" aria-hidden="true">&middot;</span>
        <span><?php echo esc_html(sprintf(__('%d Min. Lesezeit', 'ron-ulrich'), theme_reading_time())); ?></span>
    </div>
<?php } ?>
