<?php

/**
 * Do not edit anything in this file unless you know what you're doing
 */

use Roots\Sage\Config;
use Roots\Sage\Container;

/**
 * Helper function for prettying up errors
 * @param string $message
 * @param string $subtitle
 * @param string $title
 */
$sage_error = function ($message, $subtitle = '', $title = '') {
    $title = $title ?: __('Sage &rsaquo; Error', 'sage');
    $footer = '<a href="https://roots.io/sage/docs/">roots.io/sage/docs/</a>';
    $message = "<h1>{$title}<br><small>{$subtitle}</small></h1><p>{$message}</p><p>{$footer}</p>";
    wp_die($message, $title);
};

/**
 * Ensure compatible version of PHP is used
 */
if (version_compare('7.1', phpversion(), '>=')) {
    $sage_error(__('You must be using PHP 7.1 or greater.', 'sage'), __('Invalid PHP version', 'sage'));
}

/**
 * Ensure compatible version of WordPress is used
 */
if (version_compare('4.7.0', get_bloginfo('version'), '>=')) {
    $sage_error(__('You must be using WordPress 4.7.0 or greater.', 'sage'), __('Invalid WordPress version', 'sage'));
}

/**
 * Ensure dependencies are loaded
 */
if (!class_exists('Roots\\Sage\\Container')) {
    if (!file_exists($composer = __DIR__.'/../vendor/autoload.php')) {
        $sage_error(
            __('You must run <code>composer install</code> from the Sage directory.', 'sage'),
            __('Autoloader not found.', 'sage')
        );
    }
    require_once $composer;
}

/**
 * Sage required files
 *
 * The mapped array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 */
array_map(function ($file) use ($sage_error) {
    $file = "../app/{$file}.php";
    if (!locate_template($file, true, true)) {
        $sage_error(sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file), 'File not found');
    }
}, [
    'helpers',
    'setup',
    'filters',
    'admin',
    'acf-options-page'
]);

/**
 * Here's what's happening with these hooks:
 * 1. WordPress initially detects theme in themes/sage/resources
 * 2. Upon activation, we tell WordPress that the theme is actually in themes/sage/resources/views
 * 3. When we call get_template_directory() or get_template_directory_uri(), we point it back to themes/sage/resources
 *
 * We do this so that the Template Hierarchy will look in themes/sage/resources/views for core WordPress themes
 * But functions.php, style.css, and index.php are all still located in themes/sage/resources
 *
 * This is not compatible with the WordPress Customizer theme preview prior to theme activation
 *
 * get_template_directory()   -> /srv/www/example.com/current/web/app/themes/sage/resources
 * get_stylesheet_directory() -> /srv/www/example.com/current/web/app/themes/sage/resources
 * locate_template()
 * ├── STYLESHEETPATH         -> /srv/www/example.com/current/web/app/themes/sage/resources/views
 * └── TEMPLATEPATH           -> /srv/www/example.com/current/web/app/themes/sage/resources
 */
array_map(
    'add_filter',
    ['theme_file_path', 'theme_file_uri', 'parent_theme_file_path', 'parent_theme_file_uri'],
    array_fill(0, 4, 'dirname')
);
Container::getInstance()
    ->bindIf('config', function () {
        return new Config([
            'assets' => require dirname(__DIR__).'/config/assets.php',
            'theme' => require dirname(__DIR__).'/config/theme.php',
            'view' => require dirname(__DIR__).'/config/view.php',
        ]);
    }, true);

// add_action('admin_init', 'my_general_section');
// function my_general_section() {
//     add_settings_section(
//         'my_settings_section', // Section ID
//         'My Options Title', // Section Title
//         'my_section_options_callback', // Callback
//         'general' // What Page?  This makes the section show up on the General Settings Page
//     );
//
//     add_settings_field( // Option 1
//         'option_1', // Option ID
//         'Option 1', // Label
//         'my_textbox_callback', // !important - This is where the args go!
//         'general', // Page it will be displayed (General Settings)
//         'my_settings_section', // Name of our section
//         array( // The $args
//             'option_1' // Should match Option ID
//         )
//     );
//
//     add_settings_field( // Option 2
//         'option_2', // Option ID
//         'Option 2', // Label
//         'my_textbox_callback', // !important - This is where the args go!
//         'general', // Page it will be displayed
//         'my_settings_section', // Name of our section (General Settings)
//         array( // The $args
//             'option_2' // Should match Option ID
//         )
//     );
//
//     register_setting('general','option_1', 'esc_attr');
//     register_setting('general','option_2', 'esc_attr');
// }
//
// function my_section_options_callback() { // Section Callback
//     echo '<p>A little message on editing info</p>';
// }
//
// function my_textbox_callback($args) {  // Textbox Callback
//     $option = get_option($args[0]);
//     echo '<input type="text" id="'. $args[0] .'" name="'. $args[0] .'" value="' . $option . '" />';
// }

// /**
//  * Class for adding a new field to the options-general.php page
//  */
// class Add_Settings_Field {
//
//     /**
//      * Class constructor
//      */
//     public function __construct() {
//         add_action( 'admin_init' , array( $this , 'register_fields' ) );
//     }
//
//     /**
//      * Add new fields to wp-admin/options-general.php page
//      */
//     public function register_fields() {
//         register_setting( 'general', 'extra_blog_description', 'esc_attr' );
//         add_settings_field(
//             'extra_blog_desc_id',
//             '<label for="extra_blog_desc_id">' . __( 'Blog description' , 'extra_blog_description' ) . '</label>',
//             array( $this, 'fields_html' ),
//             'general'
//         );
//     }
//
//     /**
//      * HTML for extra settings
//      */
//     public function fields_html() {
//         $value = get_option( 'extra_blog_description', '' );
//         echo '<textfield type="text" id="extra_blog_desc_id" name="extra_blog_description" value="' . esc_attr( $value ) . '" />';
//     }
//
// }
// new Add_Settings_Field();

// Adjust the Excerpt
// ------------------------------------>
add_filter( 'the_excerpt', function($excerpt) {
    return str_replace('<p', '<p class="clampify"', $excerpt);
});

add_filter( 'excerpt_more', function() {
    return '';
});
