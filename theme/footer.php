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
            <?php if ($copyright = get_field('copyright', 'option')) { ?>
                <div>
                    <span><?php echo esc_html($copyright); ?></span>
                </div>
            <?php } ?>
            <?php if (has_nav_menu('footer_navigation')) { ?>
                <?php
                    wp_nav_menu([
                        'theme_location' => 'footer_navigation',
                        'menu_class' => 'my-0 ml-auto flex list-none gap-10 p-0',
                        'container' => false,
                    ]);
                ?>
            <?php } ?>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
