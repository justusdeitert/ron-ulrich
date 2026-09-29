<?php
/**
 * "Back" link band under single posts and pages. back-link.ts intercepts
 * the click to go back in history when the visitor came from this site.
 *
 * @package ron-ulrich
 */

$back_url = theme_posts_page_url();
?>
<hr class="mt-10 md:mt-14">
<div class="py-4">
    <a class="back-link chip inline-flex items-center gap-1" href="<?php echo esc_url($back_url); ?>">
        <span class="i-lucide-arrow-left size-5" aria-hidden="true"></span>
        <span><?php esc_html_e('zurück', 'ron-ulrich'); ?></span>
    </a>
</div>
