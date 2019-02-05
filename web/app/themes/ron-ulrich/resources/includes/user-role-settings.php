<?php
// Remove from Menu
// https://codex.wordpress.org/Function_Reference/remove_menu_page

// Remove by default
// add_action('admin_menu', function () {
//     remove_menu_page('edit.php'); // Posts
//     remove_menu_page('edit-comments.php'); // Comments
//     add_filter('acf/settings/show_admin', '__return_false');
// });

// Remove capabilities from editors.
// Call the function when your plugin/theme is activated.
// ------------------------->
add_action( 'init', function() {

    // Get the role object.
    $editor = get_role('editor');

    // A list of capabilities to remove from editors.
    $caps = array(
        // https://codex.wordpress.org/Roles_and_Capabilities#edit_themes
        'edit_themes' => false,
        // https://codex.wordpress.org/Roles_and_Capabilities#edit_theme_options
        'edit_theme_options' => true,
        // https://codex.wordpress.org/Roles_and_Capabilities#manage_options
        'manage_options' => true,
    );

    foreach ($caps as $cap => $value) {
        if($value) {
            // Add the capability.
            $editor->add_cap($cap);
        } else {
            // Remove the capability.
            $editor->remove_cap($cap);
        }
    }
});

// Add User Role Class to WP Admin Html
// ---------------------------->
add_filter('admin_body_class', 'add_role_body_class');
function add_role_body_class($classes) {
    global $current_user;

    foreach($current_user->roles as $role) {
        $classes .= ' role-' . $role;
    }

    return trim($classes);
}
