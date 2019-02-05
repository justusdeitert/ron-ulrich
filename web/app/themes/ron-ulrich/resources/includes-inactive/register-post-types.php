<?php
// ----------------------------------->
//	Register Custom Post Types & Taxonomies
// ----------------------------------->
add_action('init', function() {

    register_post_type('Referencies', [
        'labels' => [
            'name' => __('Referencies', 'sage'),
            'singular_name'  => __('Reference', 'sage'),
            'menu_name' => __('Referencies', 'sage'),
            'show_in_menu' => __('Referencies', 'sage'),
            'add_new_item'       => __('Add New Team Member', 'sage'),
        ],
        'public' => true,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'supports' => array('title', 'revisions', 'thumbnail'),
        'menu_icon' => 'dashicons-admin-home',
        'publicly queryable' => true,
        'rewrite' => array('slug' => 'referenzen')
        // 'show_ui'       => true,
        // 'show_in_menu'  => true,
        // 'taxonomies'         => array('department')
    ]);

    // Add new taxonomy, make it hierarchical (like categories)
    register_taxonomy( 'areas', array('referencies'), array(
        'labels' => array(
            'name' => __( 'Areas', 'sage'),
            'menu_name' => __( 'Areas', 'sage'),
            'singular_name' => __( 'Area', 'sage'),
            'search_items' => __( 'Search Areas', 'sage'),
            'all_items' => __( 'All Areas', 'sage'),
            // 'parent_item' => __( 'Parent Department', 'sage'),
            // 'parent_item_colon' => __( 'Parent Fitness Type:', 'sage'),
            'edit_item' => __( 'Edit Areas', 'sage'),
            'update_item' => __( 'Update Areas', 'sage'),
            'add_new_item' => __( 'Add New Area', 'sage'),
            'new_item_name' => __( 'New Area Name', 'sage'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'area' ),
    ));
});

