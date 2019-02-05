<?php
// Adjust Wp Admin Menus, etc..
// -------------------->

// Custom Plugin Menu Name(s)
// Rename admin menus added by plugins
add_action('admin_init', function() {
    global $menu;

    // Define your changes here
    $updates = array(
        'MailPoet' => array(
            'name' => __('Newsletter', 'sage'),
            'icon' => 'dashicons-email'
        ),
        'Contact' => array(
            'name' => __('Contact Forms', 'sage'),
            'icon' => 'dashicons-text',
        ),
        'Contact Forms' => array(
            'name' => __('Messages', 'sage'),
            'icon' => 'dashicons-email',
        )
    );

    foreach ( $menu as $k => $props ) {

        // Check for new values
        $new_values = ( isset( $updates[ $props[0] ] ) ) ? $updates[ $props[0] ] : false;
        if ( ! $new_values ) continue;

        // Change menu name
        $menu[$k][0] = $new_values['name'];

        // Optionally change menu icon
        if ( isset( $new_values['icon'] ) )
            $menu[$k][6] = $new_values['icon'];
    }
});


// Disable Default Dashboard Widgets
// @ https://digwp.com/2014/02/disable-default-dashboard-widgets/
// ------------------------->
add_action('wp_dashboard_setup', 'disable_default_dashboard_widgets', 999);
function disable_default_dashboard_widgets() {
    global $wp_meta_boxes;
    // wp..
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
    // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
    // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
    // unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
    unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
    // unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
}

// Removing Welcome Panel in Admin Dashboard
// ------------------------->
remove_action('welcome_panel', 'wp_welcome_panel');

