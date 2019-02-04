<?php

if( function_exists('acf_add_options_page') ) {

    // ----------------------------------------->
    // Example
    // https://www.advancedcustomfields.com/resources/options-page/
    // https://www.advancedcustomfields.com/resources/acf_add_options_page/
    // https://www.advancedcustomfields.com/resources/get-values-from-an-options-page/
    // ----------------------------------------->
    $args = array(

        /* (string) The title displayed on the options page. Required. */
        'page_title' => __('Info', 'sage'),

        /* (string) The title displayed in the wp-admin sidebar. Defaults to page_title */
        'menu_title' => __('Info', 'sage'),

        /* (string) The slug name to refer to this menu by (should be unique for this menu).
        Defaults to a url friendly version of menu_slug */
        'menu_slug' => 'information',

        /* (string) The capability required for this menu to be displayed to the user. Defaults to edit_posts.
        Read more about capability here: http://codex.wordpress.org/Roles_and_Capabilities */
        'capability' => 'edit_posts',

        /* (int|string) The position in the menu order this menu should appear.
        WARNING: if two menu items use the same position attribute, one of the items may be overwritten so that only one item displays!
        Risk of conflict can be reduced by using decimal instead of integer values, e.g. '63.3' instead of 63 (must use quotes).
        Defaults to bottom of utility menu items */
        'position' => 50.2,

        /* (string) The icon class for this menu. Defaults to default WordPress gear.
        Read more about dashicons here: https://developer.wordpress.org/resource/dashicons/ */
        'icon_url' => 'dashicons-admin-customizer',
    );

    acf_add_options_page($args);
}
