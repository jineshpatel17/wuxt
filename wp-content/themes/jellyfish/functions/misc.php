<?php
//Filter to to get acf field within acf field for relational field set
add_filter( 'acf/rest_api/page/get_fields', function( $data ) {
    if ( ! empty( $data ) ) {
        array_walk_recursive( $data, 'get_fields_recursive' );
    }

    return $data;
} );

//Filter to get news and insights post type acf fields.
add_filter('acf/rest_api/news_insights/get_fields', function($data) {
    if ( ! empty( $data ) ) {
        array_walk_recursive( $data, 'get_fields_recursive' );
    }

    return $data;
});


//Filter to get news and insights post type acf fields.
add_filter('acf/rest_api/case_study/get_fields', function($data) {
    if ( ! empty( $data ) ) {
        array_walk_recursive( $data, 'get_fields_recursive' );
    }

    return $data;
});

//Filter to get news and insights post type acf fields.
add_filter('acf/rest_api/offices/get_fields', function($data) {
    if ( ! empty( $data ) ) {
        array_walk_recursive( $data, 'get_fields_recursive' );
    }

    return $data;
});

//Filter to get news and insights post type acf fields.
add_filter('acf/rest_api/region_selector/get_fields', function($data) {
    if ( ! empty( $data ) ) {
        array_walk_recursive( $data, 'get_fields_recursive' );
    }

    return $data;
});


//Filter to get news and insights post type acf fields.
add_filter('acf/rest_api/events/get_fields', function($data) {
    if ( ! empty( $data ) ) {
        array_walk_recursive( $data, 'get_fields_recursive' );
    }

    return $data;
});


//Filter to get news post type acf fields.
add_filter('acf/rest_api/news/get_fields', function($data) {
    if ( ! empty( $data ) ) {
        array_walk_recursive( $data, 'get_fields_recursive' );
    }

    return $data;
});

//Filter to get insights post type acf fields.
add_filter('acf/rest_api/insights/get_fields', function($data) {
    if ( ! empty( $data ) ) {
        array_walk_recursive( $data, 'get_fields_recursive' );
    }

    return $data;
});

add_filter( 'acf/rest_api/jellyfish-settings/get_fields', function( $data ) {
	if ( ! empty( $data ) ) {
		array_walk_recursive( $data, 'get_fields_recursive' );
	}

	return $data;
} );

function get_fields_recursive( $item ) {
    if ( is_object( $item ) ) {
        $item->acf = array();
        if ( $fields = get_fields( $item ) ) {
            $item->acf = $fields;
            array_walk_recursive( $item->acf, 'get_fields_recursive' );
        }
    }
}

//Remove auto <p></p> tags from the content and excerpt
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

add_filter('tiny_mce_before_init', function($init) {
    $init['wpautop'] = false;
    $init["forced_root_block"] = false;
    $init["force_br_newlines"] = true;
    $init["force_p_newlines"] = false;
    $init["convert_newlines_to_brs"] = true;

    return $init;
});

add_action('acf/init', 'register_settings');

function register_settings() {
    
    //Remove auto <p></p> tags from the acf based text editors
    remove_filter('acf_the_content', 'wpautop' );
    remove_filter('the_content', 'wpautop' );

    /*if( function_exists('acf_add_options_page') ) {
        $option_page = acf_add_options_page(array(
            'page_title'    => __('Jellyfish Settings'),
            'menu_title'    => __('Jellyfish Settings'),
            'menu_slug'     => 'jellyfish-settings',
        ));
    }*/

    $jellyfish_settings = rest_jellyfish_settings();
    acf_update_setting('google_api_key', $jellyfish_settings['offices_settings']['google_map_api_key']);
}

function role_wise_remove_menu_items() {
    
	if( current_user_can( 'drafter' ) || current_user_can( 'approver' )):
		remove_menu_page( 'sitepress-multilingual-cms/menu/languages.php' );
        remove_menu_page( 'edit-comments.php' ); //Comment menu
    endif;
}
add_action( 'admin_head', 'role_wise_remove_menu_items' );

// define the custom replacement callback
function get_region() {
	$seoRegionText = '';
	$Regions_posts = get_posts(array('post_type' => 'regions','post_status' => 'publish',
        'posts_per_page' => 999999));
	//Get the base location Latitude and Longitude
	foreach($Regions_posts as $key => $res ) {		
		$regionId = $res->ID;
		$subsite = get_field("sub_site",$regionId);
		if(array_search(ICL_LANGUAGE_CODE, array_column($subsite, 'locale')) !== false) {			
			$seoRegionText = get_field("seo_title_suffix",$regionId);
			break;
		}
	}
	
    return $seoRegionText;
}
// define the action for register yoast_variable replacments
function register_custom_yoast_variables() {
    wpseo_register_var_replacement( '%%region%%', 'get_region', 'advanced', 'Get the region suffix value' );
}
// Add action
add_action('wpseo_register_extra_replacements', 'register_custom_yoast_variables');