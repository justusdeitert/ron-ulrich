<?php
// Block Filters
// https://wordpress.org/gutenberg/handbook/designers-developers/developers/filters/block-filters/
// --------------------------------------------->

// Hiding blocks from the inserter
// https://wordpress.org/gutenberg/handbook/designers-developers/developers/filters/block-filters/#hiding-blocks-from-the-inserter
// add_filter( 'allowed_block_types', function($allowed_blocks) {
//     return array(
//         // 'core/image',
//         'core/paragraph',
//         'core/heading',
//         'core/list',
//         'core/button',
//         'core/quote',
//         'core/columns',
//         'core/spacer',
//         'core/html',
//     );
// });
//
// /**
//  * Register support for Gutenberg wide images in your theme
//  */
// add_action( 'after_setup_theme', function() {
//     add_theme_support( 'align-wide' );
// } );
