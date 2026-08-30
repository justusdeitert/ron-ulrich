<?php
/**
 * Post teaser used in the blog overview.
 *
 * @package ron-ulrich
 */

$published_in = get_field('published_in');
?>
<article <?php post_class('mt-[60px]'); ?>>
    <div class="row pb-[60px]">
        <?php if (has_post_thumbnail()) : ?>
            <?php $feature_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large', true)[0]; ?>
            <div class="col-12 col-sm-4">
                <a href="<?php the_permalink(); ?>">
                    <div class="h-full bg-cover bg-center bg-no-repeat max-sm:mb-[30px] max-sm:h-[250px]" style="background-image: url(<?php echo esc_url($feature_image_url); ?>)"></div>
                </a>
            </div>
        <?php endif; ?>

        <div class="col">
            <div class="mb-5 hidden text-[22px] font-light leading-8 [body.more-info_&]:block">
                <?php if (get_the_tags()) : ?>
                    <div class="row">
                        <div class="col mb-5">
                            <?php foreach (get_the_tags() as $tag) : ?>
                                <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>">
                                    <span class="tag"><?php echo esc_html($tag->name); ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <span><?php echo esc_html(get_the_date('j. F Y')); ?></span>
                <?php if (! empty($published_in['activate'])) : ?>
                    <?php echo ' / '; ?>
                    <?php esc_html_e('published in:', 'ron-ulrich'); ?>
                    <a href="<?php echo esc_url($published_in['url']); ?>"><?php echo esc_html($published_in['name']); ?></a>
                <?php endif; ?>
            </div>

            <a href="<?php the_permalink(); ?>">
                <h2 class="mb-5<?php echo has_post_thumbnail() ? '' : ' md:w-[70%]'; ?>"><?php the_title(); ?></h2>
            </a>

            <a href="<?php the_permalink(); ?>">
                <?php if (get_field('description')) : ?>
                    <p><?php echo wp_kses_post(get_field('description')); ?></p>
                <?php else : ?>
                    <?php the_excerpt(); ?>
                <?php endif; ?>
            </a>

            <a href="<?php the_permalink(); ?>">
                <div class="flex items-center">
                    <i class="material-icons text-[28px]">arrow_right</i>
                    <span>weiterlesen</span>
                </div>
            </a>
        </div>
    </div>
    <hr class="[article:last-of-type_&]:hidden">
</article>
