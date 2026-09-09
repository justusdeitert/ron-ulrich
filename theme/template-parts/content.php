<?php
/**
 * Post teaser used in the blog overview.
 *
 * @package ron-ulrich
 */

$has_thumbnail = has_post_thumbnail();
?>
<article <?php post_class('mt-16'); ?>>
    <div class="grid gap-8 pb-16 sm:grid-cols-3">
        <?php if ($has_thumbnail) { ?>
            <?php $feature_image_url = theme_image_url(get_post_thumbnail_id(), 'large'); ?>
            <div>
                <a href="<?php the_permalink(); ?>">
                    <div class="h-full bg-cover bg-center bg-no-repeat max-sm:h-64" style="background-image: url(<?php echo esc_url($feature_image_url ?? ''); ?>)"></div>
                </a>
            </div>
        <?php } ?>

        <div class="sm:col-span-2">
            <div class="mb-5 hidden text-xl font-light leading-8 [body.more-info_&]:block">
                <?php get_template_part('template-parts/post-meta', null, ['link_tags' => true]); ?>
            </div>

            <a href="<?php the_permalink(); ?>">
                <h2 class="mb-5<?php echo $has_thumbnail ? '' : ' md:w-[70%]'; ?>"><?php the_title(); ?></h2>
            </a>

            <?php get_template_part('template-parts/content-description'); ?>

            <a href="<?php the_permalink(); ?>">
                <div class="flex items-center">
                    <i class="material-icons text-3xl">arrow_right</i>
                    <span>weiterlesen</span>
                </div>
            </a>
        </div>
    </div>
    <hr class="[article:last-of-type_&]:hidden">
</article>
