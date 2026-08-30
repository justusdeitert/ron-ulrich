<?php
/**
 * Single post content.
 *
 * @package ron-ulrich
 */

$published_in = get_field('published_in');
?>
<hr>

<div class="flex py-5">
    <?php if (have_rows('share_icons', 'option')) : ?>
        <span><?php esc_html_e('Teilen auf:', 'ron-ulrich'); ?></span>

        <ul class="m-0 flex list-none gap-[15px] p-0">
            <?php
                while (have_rows('share_icons', 'option')) :
                    the_row();
                    ?>
                <?php if (get_sub_field('show')) : ?>
                    <?php $share_icon_url = theme_image_url(get_sub_field('icon'), 'full'); ?>
                    <?php $share_icon = get_sub_field('icon'); ?>
                    <?php $share_icon_name = is_array($share_icon) ? ($share_icon['name'] ?? '') : ''; ?>
                    <li>
                        <a href="<?php global $wp;
                        echo esc_url(get_sub_field('url') . home_url($wp->request) . '/?job=' . get_post()->post_name); ?>" target="_blank" rel="noopener">
                            <img src="<?php echo esc_url($share_icon_url); ?>" alt="<?php echo esc_attr($share_icon_name); ?>">
                        </a>
                    </li>
                <?php endif; ?>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>

    <?php if (! empty($published_in['activate'])) : ?>
        <a class="ml-auto flex items-center" href="<?php echo esc_url($published_in['url']); ?>">
            <i class="material-icons mr-[5px]">link</i>
            <span><?php echo esc_html($published_in['name']); ?></span>
        </a>
    <?php endif; ?>
</div>

<hr>

<article <?php post_class('mt-[70px]'); ?>>
    <div class="mb-[30px]">
        <?php if (get_the_tags()) : ?>
            <div class="row">
                <div class="col mb-5">
                    <?php foreach (get_the_tags() as $tag) : ?>
                        <span class="tag"><?php echo esc_html($tag->name); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="mb-5 text-[22px] font-light leading-8">
            <span><?php echo esc_html(get_the_date('j. F Y')); ?></span>
            <?php if (! empty($published_in['activate'])) : ?>
                <?php echo ' / '; ?>
                <?php esc_html_e('published in:', 'ron-ulrich'); ?>
                <a href="<?php echo esc_url($published_in['url']); ?>"><?php echo esc_html($published_in['name']); ?></a>
            <?php endif; ?>
        </div>

        <h2 class="mb-5 lg:w-[70%]"><?php the_title(); ?></h2>

        <?php if (get_field('description')) : ?>
            <h3><?php echo wp_kses_post(get_field('description')); ?></h3>
        <?php endif; ?>
    </div>

    <?php the_content(); ?>
</article>

<a class="mt-2.5 flex items-center" href="javascript:history.go(-1)">
    <i class="material-icons text-[28px]">arrow_left</i>
    <span class="text-xl"><?php esc_html_e('zurück', 'ron-ulrich'); ?></span>
</a>
