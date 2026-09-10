<?php
/**
 * "Back" link band under single posts and pages. back-link.ts intercepts
 * the click to go back in history when the visitor came from this site.
 *
 * @package ron-ulrich
 */

$back_url = theme_posts_page_url();
?>
<div class="mt-10 py-4 md:mt-14">
    <a class="back-link chip inline-flex items-center gap-1" href="<?php echo esc_url($back_url); ?>">
        <i class="material-icons !text-xl">arrow_back</i>
        <span><?php esc_html_e('zurück', 'ron-ulrich'); ?></span>
    </a>
</div>
<hr>
