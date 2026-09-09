<?php
/**
 * Search form (replaces the WordPress default markup so it can
 * carry utility classes).
 *
 * @package ron-ulrich
 */

?>
<form role="search" method="get" class="search-form flex flex-row flex-wrap items-center" action="<?php echo esc_url(home_url('/')); ?>">
    <label class="mb-4 py-2.5 font-normal">
        <span class="screen-reader-text"><?php esc_html_e('Search for:', 'ron-ulrich'); ?></span>
        <input type="search" class="search-field block w-full rounded border border-solid border-line-strong bg-white px-3 py-1.5 text-base text-ink-700" placeholder="<?php esc_attr_e('Search &hellip;', 'ron-ulrich'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" name="s">
    </label>
    <input type="submit" class="search-submit ml-5 inline-block rounded border border-solid border-ink-600 bg-ink-600 px-3 py-1.5 text-base leading-normal text-white hover:border-ink-700 hover:bg-ink-700" value="<?php esc_attr_e('Search', 'ron-ulrich'); ?>">
</form>
