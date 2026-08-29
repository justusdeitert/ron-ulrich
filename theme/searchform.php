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
        <input type="search" class="search-field block w-full rounded border border-[#ced4da] bg-white px-3 py-1.5 text-base text-[#495057]" placeholder="<?php esc_attr_e('Search &hellip;', 'ron-ulrich'); ?>" value="<?php echo get_search_query(); ?>" name="s">
    </label>
    <input type="submit" class="search-submit ml-5 inline-block rounded border border-[#6c757d] bg-[#6c757d] px-3 py-1.5 text-base leading-normal text-white hover:border-[#545b62] hover:bg-[#5a6268]" value="<?php esc_attr_e('Search', 'ron-ulrich'); ?>">
</form>
