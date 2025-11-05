<?php
/**
 * Theme footer.
 *
 * @package ron-ulrich
 */

?>
    </div>
</main>

<footer>
    <div class="container">
        <hr>
        <div class="wrapper">
            <div class="copyright">
                <?php if (get_field('copyright', 'option')) : ?>
                    <span><?php echo esc_html(get_field('copyright', 'option')); ?></span>
                <?php endif; ?>
            </div>
            <?php if (has_nav_menu('footer_navigation')) : ?>
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer_navigation',
                    'menu_class' => 'footer-nav',
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
