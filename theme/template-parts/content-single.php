<?php
/**
 * Single post content.
 *
 * @package ron-ulrich
 */

$published_in = get_field('published_in');
$share_icons = array_filter(
    get_field('share_icons', 'option') ?: [],
    fn (array $row): bool => ! empty($row['show']),
);
$posts_page_id = (int) get_option('page_for_posts');
$back_url = $posts_page_id ? get_permalink($posts_page_id) : home_url('/');
?>
<hr>

<div class="flex py-5">
    <?php if ($share_icons) { ?>
        <span><?php esc_html_e('Teilen auf:', 'ron-ulrich'); ?></span>

        <ul class="m-0 flex list-none gap-4 p-0">
            <?php foreach ($share_icons as $row) { ?>
                <?php $share_icon_url = theme_image_url($row['icon'] ?? null, 'full'); ?>
                <?php $share_icon_name = is_array($row['icon'] ?? null) ? ($row['icon']['name'] ?? '') : ''; ?>
                <li>
                    <a href="<?php echo esc_url($row['url'] . theme_share_url()); ?>" target="_blank" rel="noopener">
                        <img src="<?php echo esc_url($share_icon_url ?? ''); ?>" alt="<?php echo esc_attr($share_icon_name); ?>">
                    </a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

    <?php if (! empty($published_in['activate'])) { ?>
        <a class="ml-auto flex items-center" href="<?php echo esc_url($published_in['url']); ?>">
            <i class="material-icons mr-1">link</i>
            <span><?php echo esc_html($published_in['name']); ?></span>
        </a>
    <?php } ?>
</div>

<hr>

<article <?php post_class('mt-16'); ?>>
    <div class="mb-8">
        <div class="mb-5 text-xl font-light leading-8">
            <?php get_template_part('template-parts/post-meta', null, ['published_in' => $published_in]); ?>
        </div>

        <h2 class="mb-5 lg:w-[70%]"><?php the_title(); ?></h2>

        <?php if ($description = get_field('description')) { ?>
            <h3><?php echo wp_kses_post($description); ?></h3>
        <?php } ?>
    </div>

    <?php the_content(); ?>
</article>

<a class="back-link mt-2.5 flex items-center" href="<?php echo esc_url($back_url); ?>">
    <i class="material-icons text-3xl">arrow_left</i>
    <span class="text-xl"><?php esc_html_e('zurück', 'ron-ulrich'); ?></span>
</a>
