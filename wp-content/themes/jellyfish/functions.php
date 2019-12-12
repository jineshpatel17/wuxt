<?php
/*
 *  Author: Steve Slade | @shtev21
 *  URL: jellyfish.co.uk | @jellyfishagency
 *  Custom functions, support, custom post types and more.
 */


/* function peckingorder_remove_image_sizes( $sizes) {
     unset( $sizes['thumbnail']);
     unset( $sizes['medium']);
     unset( $sizes['medium_large']);
     unset( $sizes['large']);

     return $sizes;
 }
 add_filter('intermediate_image_sizes_advanced', 'peckingorder_remove_image_sizes');
*/
 if (function_exists('add_theme_support'))
 {
     // Add Menu Support
     add_theme_support('menus');

     // Add Thumbnail Theme Support
     add_theme_support('post-thumbnails');

     add_image_size('square', 495, 495, true);
     add_image_size('portrait', 232, 338, true);
     add_image_size('landscape', 495, 232, true);
     
     //New Image Crops Square
     add_image_size('sq-162w', 162, 162, true);
     add_image_size('sq-384w', 384, 384, true);
     add_image_size('sq-496w', 496, 496, true);
     add_image_size('sq-768w', 768, 768, true);
     add_image_size('sq-1540w', 1540, 1540, true);
     //New Image Crops Landscape
     add_image_size('ls-496w', 496, 248, array('center','center'));
     add_image_size('ls-630w', 630, 315, true);
     add_image_size('ls-992w', 992, 496, true);
     add_image_size('ls-1260w', 1260, 630, true);
     add_image_size('ls-1500w', 1500, 750, true);
     //New Image Crops Portrait
     add_image_size('pt-162w', 162, 231, true);
     add_image_size('pt-232w', 232, 331, true);
     add_image_size('pt-335w', 335, 479, true);
     add_image_size('pt-652w', 652, 931, true);
     add_image_size('pt-928w', 928, 1326, true);

     // Enables post and comment RSS feed links to head
     add_theme_support('automatic-feed-links');

     // Localisation Support
     load_theme_textdomain('html5blank', get_template_directory() . '/languages');
 }

 $user = wp_get_current_user();
 $logged_in_user = '';
 if (isset($user->data->user_login)) {
    $logged_in_user = $user->data->user_login;
 }

 /**
  * Allow SVG uploads.
  */
 function add_file_types_to_uploads($file_types) {
     $new_filetypes = array();
     $new_filetypes['svg'] = 'image/svg+xml';
     $file_types = array_merge($file_types, $new_filetypes );
     return $file_types;
 }

 if (strcmp($logged_in_user, 'gwright') == 0 || strcmp($logged_in_user, 'jf_4dmin') == 0 ) {
     add_action('upload_mimes', 'add_file_types_to_uploads');
 }

 // HTML5 Blank navigation
 function html5blank_nav()
 {
    wp_nav_menu(
    array(
        'theme_location'  => 'header-menu',
        'menu'            => '',
        'container'       => 'div',
        'container_class' => 'menu-{menu slug}-container',
        'container_id'    => '',
        'menu_class'      => 'menu',
        'menu_id'         => '',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul>%3$s</ul>',
        'depth'           => 0,
        'walker'          => ''
        )
    );
 }

/**
* A Global function to get the Jellyfish settings values
* $lang: specify the language code
*/
function getSettingsValues($lang) {
    //Page slug is fixed: "settings"
    $jellyfish_settings = get_page_by_path( 'settings', OBJECT, 'jellyfish_settings' );
    $postID = icl_object_id( $jellyfish_settings->ID , 'jellyfish_settings', true, $lang );
    $settings = get_fields($postID);
    if ( ! empty( $settings ) ) {
        array_walk_recursive( $settings, 'get_fields_recursiveACF' );
    }
    return $settings;
}

function app_acf_admin_enqueue_scripts() {
        wp_enqueue_script('acf-character-limit-js', get_template_directory_uri() . '/assets/backend.js', [], false);
}

add_action('acf/input/admin_enqueue_scripts', 'app_acf_admin_enqueue_scripts');

/* ----------------------
/* INCLUDE FUNCTIONS BITS
/* ----------------------
*/

// Additional filters/hooks related to salesforce and contact page
include('functions/salesforce_lead.php');

// CPT UI POST TYPES
include('functions/register_post_types.php');

// END POINTS
include('functions/end_points.php');

// MENU LOCATIONS
include('functions/register_menus.php');

// TAXONOMIES
include('functions/register_taxonomies.php');

// Additional filters/hooks related to rest api
include('functions/misc.php');

// Additional filters/hooks related to rest api
include('functions/preview.php');

// Post Types & Taxonies to exclude from Sitemap
include('functions/sitemap_excludes.php');
