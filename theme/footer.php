<?php
/**
 * Theme footer.
 *
 * @package ron-ulrich
 */

?>
    </div>
</main>

<footer class="pt-12 pb-12 md:pt-16">
    <div class="container">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between md:gap-8">
            <a class="flex flex-col" href="<?php echo esc_url(home_url('/')); ?>">
                <span class="font-serif text-[1.75rem] leading-none tracking-[-0.02em] md:text-[2.25rem]"><?php bloginfo('name'); ?></span>
                <span class="mt-2 font-sans text-xs font-semibold uppercase tracking-[0.12em] text-ink-600 md:text-sm"><?php bloginfo('description'); ?></span>
            </a>

            <?php // Stacked on mobile the two blocks need a divider; side by side on desktop they do not. ?>
            <div class="flex flex-col gap-3 border-t border-solid border-line pt-6 md:items-end md:border-0 md:pt-0">
                <?php if (has_nav_menu('footer_navigation')) { ?>
                    <?php
                        wp_nav_menu([
                            'theme_location' => 'footer_navigation',
                            'menu_class' => 'my-0 flex list-none flex-wrap gap-x-8 gap-y-2 p-0 text-sm font-semibold uppercase tracking-[0.08em] text-ink-600 [&_a:hover]:text-accent',
                            'container' => false,
                        ]);
                    ?>
                <?php } ?>
                <span class="text-sm text-ink-500">
                    <?php printf('&copy; %s %s', esc_html(wp_date('Y')), esc_html(get_bloginfo('name'))); ?>
                </span>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
