<?php
/**
 * Single post content.
 *
 * @package ron-ulrich
 */

$published_in = get_field('published_in');
?>
<hr>

<div class="share-container">
    <?php if (have_rows('share_icons', 'option')) : ?>
        <span><?php esc_html_e('Share on:', 'ron-ulrich'); ?></span>

        <ul>
            <?php
            while (have_rows('share_icons', 'option')) :
                the_row();
                ?>
                <?php if (get_sub_field('show')) : ?>
                    <?php $share_icon_url = theme_image_url(get_sub_field('icon'), 'full'); ?>
                    <?php $share_icon = get_sub_field('icon'); ?>
                    <?php $share_icon_name = is_array($share_icon) ? ($share_icon['name'] ?? '') : ''; ?>
                    <li>
                        <a class="share-clicker" href="<?php global $wp; echo esc_url(get_sub_field('url') . home_url($wp->request) . '/?job=' . get_post()->post_name); ?>" target="_blank" rel="noopener">
                            <img src="<?php echo esc_url($share_icon_url); ?>" alt="<?php echo esc_attr($share_icon_name); ?>">
                        </a>
                    </li>
                <?php endif; ?>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>

    <?php if (! empty($published_in['activate'])) : ?>
        <a class="go-to-article" href="<?php echo esc_url($published_in['url']); ?>">
            <i class="material-icons">link</i>
            <span><?php echo esc_html($published_in['name']); ?></span>
        </a>
    <?php endif; ?>
</div>

<hr>

<article <?php post_class('article-detail'); ?>>
    <div class="article-detail-header">
        <?php if (get_the_tags()) : ?>
            <div class="row">
                <div class="col tag-column">
                    <?php foreach (get_the_tags() as $tag) : ?>
                        <span class="tag"><?php echo esc_html($tag->name); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="post-info">
            <span class="post-date"><?php echo esc_html(get_the_date('j. F Y')); ?></span>
            <?php if (! empty($published_in['activate'])) : ?>
                <?php echo ' / '; ?>
                <?php esc_html_e('published in:', 'ron-ulrich'); ?>
                <a href="<?php echo esc_url($published_in['url']); ?>"><?php echo esc_html($published_in['name']); ?></a>
            <?php endif; ?>
        </div>

        <h2><?php the_title(); ?></h2>

        <?php if (get_field('description')) : ?>
            <h3><?php echo wp_kses_post(get_field('description')); ?></h3>
        <?php endif; ?>
    </div>

    <?php the_content(); ?>
</article>

<div class="bottom-links">
    <a class="go-to-article-bottom-link" href="javascript:history.go(-1)">
        <i class="material-icons">arrow_left</i>
        <span><?php esc_html_e('back', 'ron-ulrich'); ?></span>
    </a>
</div>
