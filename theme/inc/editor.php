<?php

/**
 * Block editor configuration: which blocks editors can insert.
 *
 * Visual parity with the frontend comes from theme.json (canvas width,
 * palette) and src/css/editor.scss (typography), both loaded elsewhere.
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * The blocks this theme actually styles, derived from what the existing
 * content uses. Anything else is hidden from the inserter; content that
 * already contains another block still renders on the frontend.
 */
const THEME_ALLOWED_BLOCKS = [
    'core/paragraph',
    'core/heading',
    'core/list',
    'core/list-item',
    'core/image',
    'core/quote',
    'core/separator',
    'core/columns',
    'core/column',
    'core/embed',
    'core/html',
    // Contact Form 7 forms are embedded as shortcodes
    'core/shortcode',
    // Placeholder the editor shows for unrecognised blocks in existing content
    'core/missing',
];

add_filter('allowed_block_types_all', function ($allowed_blocks, $editor_context) {
    // Only lock down the post and page editor, not the widget or site editor.
    if (! isset($editor_context->post)) {
        return $allowed_blocks;
    }

    return THEME_ALLOWED_BLOCKS;
}, 10, 2);

// The inserter otherwise suggests wordpress.org patterns built from blocks
// this theme neither allows nor styles.
add_filter('should_load_remote_block_patterns', '__return_false');

add_action('enqueue_block_editor_assets', function (): void {
    // Style variations declared in a block's own block.json can only be removed
    // client-side. Filtering registration is order-independent, unlike calling
    // unregisterBlockStyle() on domReady before core has registered the block.
    wp_enqueue_script('theme-editor', false, ['wp-blocks', 'wp-hooks'], null, true);
    wp_add_inline_script('theme-editor', <<<'JS'
        wp.hooks.addFilter('blocks.registerBlockType', 'ron-ulrich/block-styles', function (settings, name) {
            // The theme styles one separator, so the "wide" and "dots" variations only
            // offer editors a look the frontend does not have.
            if (name === 'core/separator') {
                settings.styles = (settings.styles || []).filter(function (style) {
                    return style.name === 'default';
                });
            }

            return settings;
        });
        JS);
});
