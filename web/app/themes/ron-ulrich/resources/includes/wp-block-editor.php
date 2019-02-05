<?php
// Adjust the Block editor Stuff

// Adds support for editor color palette.
// Custom Editor Color Palette
// ---------------------------------->
add_theme_support( 'editor-color-palette', array(
    array(
        'name'  => __( 'Blue', 'sage' ),
        'slug'  => 'blue',
        'color'	=> '#0687B4',
    ),
    array(
        'name'  => __( 'Orange', 'sage' ),
        'slug'  => 'orange',
        'color' => '#F4952E',
    ),
    array(
        'name'  => __( 'Pink', 'sage' ),
        'slug'  => 'pink',
        'color' => '#AC2E87',
    ),
    array(
        'name'  => __( 'Green', 'sage' ),
        'slug'  => 'green',
        'color' => '#006256',
    ),
    array(
        'name'  => __( 'Grey One', 'sage' ),
        'slug'  => 'grey_one',
        'color' => '#53575A',
    ),
    array(
        'name'  => __( 'Grey Two', 'sage' ),
        'slug'  => 'grey_two',
        'color' => '#A6A7A9',
    ),
    array(
        'name'  => __( 'White', 'sage' ),
        'slug'  => 'white',
        'color' => '#FFF',
    ),
    array(
        'name'  => __( 'Black', 'sage' ),
        'slug'  => 'black',
        'color' => '#000',
    ),
));
