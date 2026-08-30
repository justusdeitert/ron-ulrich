<?php
/**
 * Theme footer.
 *
 * @package ron-ulrich
 */

?>
    </div>
</main>

<footer class="pt-[70px] pb-10">
    <div class="container">
        <hr class="mb-5">
        <div class="flex w-full">
            <div>
                <?php if (get_field('copyright', 'option')) : ?>
                    <span><?php echo esc_html(get_field('copyright', 'option')); ?></span>
                <?php endif; ?>
            </div>
            <?php if (has_nav_menu('footer_navigation')) : ?>
                <?php
                    wp_nav_menu([
                        'theme_location' => 'footer_navigation',
                        'menu_class' => 'my-0 ml-auto flex list-none gap-10 p-0',
                        'container' => '',
                    ]);
                ?>
            <?php endif; ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
