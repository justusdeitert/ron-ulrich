<?php
/**
 * Shared loop: renders the page header (optional) and the content
 * part for each post.
 *
 * @package ron-ulrich
 *
 * @param array $args {
 *     @type string $content_part Content part slug, e.g. 'page' or 'single'.
 *     @type bool   $page_header  Whether to render the page header.
 * }
 */

$content_part = $args['content_part'] ?? 'page';
$page_header = $args['page_header'] ?? false;

while (have_posts()) :
    the_post();

    if ($page_header) :
        ?>
        <div class="mb-[60px]">
            <h2><?php echo wp_kses_post(theme_page_title()); ?></h2>
            <hr>
        </div>
        <?php
    endif;

    get_template_part('template-parts/content', $content_part);
endwhile;
