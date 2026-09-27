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
?>

<div class="flex flex-wrap items-center gap-x-4 gap-y-2 py-4">
    <?php if ($share_icons) { ?>
        <span class="kicker"><?php esc_html_e('Teilen auf:', 'ron-ulrich'); ?></span>

        <ul class="m-0 flex list-none items-center gap-4 p-0">
            <?php foreach ($share_icons as $row) { ?>
                <?php $share_icon_url = theme_image_url($row['icon'] ?? null, 'full'); ?>
                <?php $share_icon_name = is_array($row['icon'] ?? null) ? ($row['icon']['name'] ?? '') : ''; ?>
                <li class="flex">
                    <a href="<?php echo esc_url($row['url'] . theme_share_url()); ?>" target="_blank" rel="noopener">
                        <img class="h-5 w-auto opacity-70 transition-opacity hover:opacity-100" src="<?php echo esc_url($share_icon_url ?? ''); ?>" alt="<?php echo esc_attr($share_icon_name); ?>">
                    </a>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>

    <?php if (! empty($published_in['activate'])) { ?>
        <a class="kicker ml-auto flex items-center gap-1 transition-colors hover:text-accent" href="<?php echo esc_url($published_in['url']); ?>">
            <span class="i-lucide-link size-4.5" aria-hidden="true"></span>
            <span><?php echo esc_html($published_in['name']); ?></span>
        </a>
    <?php } ?>
</div>

<hr>

<article <?php post_class('mt-10 md:mt-14'); ?>>
    <div class="mb-8 md:mb-10">
        <div class="kicker mb-4">
            <?php get_template_part('template-parts/post-meta', null, ['published_in' => $published_in]); ?>
        </div>

        <h2 class="mb-4 lg:w-[85%]"><?php the_title(); ?></h2>

        <?php if ($description = get_field('description')) { ?>
            <h3 class="mb-0 lg:w-[85%]"><?php echo wp_kses_post($description); ?></h3>
        <?php } ?>

        <hr class="mt-4">
    </div>

    <?php the_content(); ?>
</article>

<?php get_template_part('template-parts/back-link'); ?>
