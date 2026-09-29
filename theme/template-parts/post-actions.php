<?php
/**
 * Back button and share icon, above and below single posts.
 * back-link.ts and share.ts pick them up by class.
 *
 * @package ron-ulrich
 */

?>
<div class="flex items-center justify-between gap-4 py-4">
    <a class="back-link chip inline-flex items-center gap-1" href="<?php echo esc_url(theme_posts_page_url()); ?>">
        <span class="i-lucide-arrow-left size-5" aria-hidden="true"></span>
        <span><?php esc_html_e('zurück', 'ron-ulrich'); ?></span>
    </a>
    <button class="share-button group inline-flex size-[34px] shrink-0 cursor-pointer items-center justify-end bg-transparent text-ink-600 transition-colors hover:text-accent" type="button" title="<?php esc_attr_e('Teilen', 'ron-ulrich'); ?>" data-url="<?php the_permalink(); ?>" data-title="<?php the_title_attribute(); ?>" data-copied="<?php esc_attr_e('Link kopiert', 'ron-ulrich'); ?>">
        <span class="i-lucide-share-2 size-4.5 group-[.copied]:hidden" aria-hidden="true"></span>
        <span class="i-lucide-check hidden size-4.5 group-[.copied]:inline-block" aria-hidden="true"></span>
        <span class="sr-only" aria-live="polite"><?php esc_html_e('Teilen', 'ron-ulrich'); ?></span>
    </button>
</div>
