<?php
/**
 * Post teaser used in the blog overview.
 *
 * @package ron-ulrich
 */

$has_thumbnail = has_post_thumbnail();
?>
<article <?php post_class('mt-10 md:mt-14'); ?>>
    <div class="grid gap-6 pb-10 sm:grid-cols-3 md:gap-8 md:pb-14">
        <?php if ($has_thumbnail) { ?>
            <?php $feature_image_url = theme_image_url(get_post_thumbnail_id(), 'large'); ?>
            <div>
                <a href="<?php the_permalink(); ?>">
                    <div class="h-full bg-paper-sunken bg-cover bg-center bg-no-repeat max-sm:h-56" style="background-image: url(<?php echo esc_url($feature_image_url ?? ''); ?>)"></div>
                </a>
            </div>
        <?php } ?>

        <div class="sm:col-span-2">
            <div class="kicker mb-3">
                <?php get_template_part('template-parts/post-meta', null, ['inline' => true]); ?>
            </div>

            <a href="<?php the_permalink(); ?>">
                <h2 class="mb-3<?php echo $has_thumbnail ? '' : ' md:w-[85%]'; ?>"><?php the_title(); ?></h2>
            </a>

            <div class="mb-4 [&_p]:mb-0">
                <?php get_template_part('template-parts/content-description'); ?>
            </div>

            <a class="chip inline-flex items-center gap-1" href="<?php the_permalink(); ?>">
                <span><?php esc_html_e('weiterlesen', 'ron-ulrich'); ?></span>
                <i class="material-icons !text-xl">arrow_right_alt</i>
            </a>
        </div>
    </div>
    <hr class="[article:last-of-type_&]:hidden">
</article>
