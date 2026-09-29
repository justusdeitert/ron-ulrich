<?php
/**
 * Post meta line: "Tag · Tag · Datum", plus the reading time on single posts.
 *
 * @package ron-ulrich
 *
 * @param array $args {
 *     @type bool $reading_time Append the estimated reading time.
 * }
 */

?>
<div class="flex flex-wrap items-center gap-x-2 gap-y-1">
    <?php foreach (get_the_tags() ?: [] as $tag) { ?>
        <a class="text-ink-900 transition-colors hover:text-accent" href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>"><?php echo esc_html($tag->name); ?></a>
        <span class="text-ink-400" aria-hidden="true">&middot;</span>
    <?php } ?>
    <time class="text-ink-500" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('j. F Y')); ?></time>
    <?php if ($args['reading_time'] ?? false) { ?>
        <span class="text-ink-400" aria-hidden="true">&middot;</span>
        <span class="text-ink-500"><?php echo esc_html(sprintf(__('%d Min. Lesezeit', 'ron-ulrich'), theme_reading_time())); ?></span>
    <?php } ?>
</div>
