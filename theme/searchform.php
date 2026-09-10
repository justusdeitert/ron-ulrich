<?php
/**
 * Search form (replaces the WordPress default markup so it can
 * carry utility classes).
 *
 * @package ron-ulrich
 */

?>
<form role="search" method="get" class="search-form flex flex-wrap items-center gap-3" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="min-w-0 flex-1 font-normal">
        <span class="screen-reader-text"><?php esc_html_e('Search for:', 'ron-ulrich'); ?></span>
        <input type="search" class="search-field block w-full rounded-sm border border-solid border-line-strong bg-paper-raised px-3 py-2 text-base text-ink-800 transition-colors focus:border-ink-900 focus:outline-none" placeholder="<?php esc_attr_e('Search &hellip;', 'ron-ulrich'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s">
    </label>
    <input type="submit" class="search-submit inline-block cursor-pointer rounded-sm border border-solid border-ink-900 bg-ink-900 px-5 py-2 text-xs font-semibold uppercase tracking-[0.08em] text-paper-raised transition-colors hover:border-accent hover:bg-accent" value="<?php esc_attr_e('Search', 'ron-ulrich'); ?>">
</form>
