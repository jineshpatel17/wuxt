<?php

/**
* cptui_register_my_cpts() - To register the new post types.
* Steps 1: Create/edit the post type/taxonomies using CPT UI Plugin.
* Steps 2: Export as a code and paste inside this function to see changes on backend.
*/ 

function cptui_register_my_cpts() {

    /**
     * Post Type: jobs.
     */

    $labels = array(
        "name" => __( "jobs", "jellyfish" ),
        "singular_name" => __( "job", "jellyfish" ),
        "menu_name" => __( "Jobs", "jellyfish" ),
        "all_items" => __( "All Jobs", "jellyfish" ),
        "add_new" => __( "Add new", "jellyfish" ),
        "add_new_item" => __( "Add new job", "jellyfish" ),
        "edit_item" => __( "Edit job", "jellyfish" ),
        "new_item" => __( "New job", "jellyfish" ),
        "view_item" => __( "View job", "jellyfish" ),
        "view_items" => __( "View jobs", "jellyfish" ),
        "search_items" => __( "Search job", "jellyfish" ),
        "not_found" => __( "No jobs found", "jellyfish" ),
        "not_found_in_trash" => __( "Bo jobs found in Bin", "jellyfish" ),
        "parent_item_colon" => __( "Parent Job", "jellyfish" ),
        "archives" => __( "Job archives", "jellyfish" ),
        "insert_into_item" => __( "Insert into job", "jellyfish" ),
        "items_list" => __( "Jobs list", "jellyfish" ),
        "parent_item_colon" => __( "Parent Job", "jellyfish" ),
    );

    $args = array(
        "label" => __( "jobs", "jellyfish" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "exclude_from_search" => false,
        "capability_type" => "job",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "jobs", "with_front" => true ),
        "query_var" => true,
        "menu_position" => 5,
        "menu_icon" => "dashicons-hammer",
        "supports" => array( "title", "editor", "thumbnail" ),
    );

    register_post_type( "jobs", $args );


    /**
	 * Post Type: Partners.
	 */

	$labels = array(
		"name" => __( "Partners", "jellyfish" ),
		"singular_name" => __( "Partner", "jellyfish" ),
	);

	$args = array(
		"label" => __( "Partners", "jellyfish" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "partner",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "partners", "with_front" => true ),
		"query_var" => true,
        "menu_position" => 6,
		"menu_icon" => "dashicons-store",
		"supports" => array( "title", "editor", "thumbnail", "author" ),
	);

	register_post_type( "partners", $args );
	
    /**
     * Post Type: Clients.
     */

    $labels = array(
        "name" => __( "Clients", "jellyfish" ),
        "singular_name" => __( "Clients", "jellyfish" ),
    );

    $args = array(
        "label" => __( "Clients", "jellyfish" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "exclude_from_search" => false,
        "capability_type" => "client",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "clients", "with_front" => true ),
        "query_var" => true,
        "menu_position" => 6,
        "menu_icon" => "dashicons-groups",
        "supports" => array( "title", "editor", "thumbnail", "author" ),
    );

    register_post_type( "clients", $args );
	/**
	 * Post Type: Footercontacts.
	 */

	$labels = array(
		"name" => __( "Footer contacts", "jellyfish" ),
		"singular_name" => __( "Footer Contact", "jellyfish" ),
	);

	$args = array(
		"label" => __( "Footer contacts", "jellyfish" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "footer_contact",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "footer_contact",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "footer_contact", "with_front" => true ),
		"query_var" => true,
		"supports" => array( "title" ),
	);

	register_post_type( "footer_contact", $args );
	
	/**
     * Post Type: Offices.
     */

    $labels = array(
        "name" => __( "Offices", "jellyfish" ),
        "singular_name" => __( "Office", "jellyfish" ),
    );

    $args = array(
        "label" => __( "Offices", "jellyfish" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "exclude_from_search" => false,
        "capability_type" => "office",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "offices", "with_front" => true ),
        "query_var" => true,
        "menu_icon" => "dashicons-building",
        "supports" => array( "title" ),
    );

    register_post_type( "offices", $args );

	/**
	 * Post Type: Contact Leads.
	 */

	$labels = array(
		"name" => __( "Contact Leads", "twentysixteen" ),
		"singular_name" => __( "Contact Lead", "twentysixteen" ),
	);

	$args = array(
		"label" => __( "Contact Leads", "twentysixteen" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "contact_lead",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "contact_leads", "with_front" => true ),
		"query_var" => true,
		"menu_icon" => "dashicons-money",
		"supports" => array( "title" ),
	);

	register_post_type( "contact_leads", $args );

	/**
	 * Post Type: Jellyfish Contact Forms.
	 */

	$labels = array(
		"name" => __( "Jellyfish Contact Forms", "twentysixteen" ),
		"singular_name" => __( "Jellyfish Contact Form", "twentysixteen" ),
	);

	$args = array(
		"label" => __( "Jellyfish Contact Forms", "twentysixteen" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "jf_contact_form",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "jf_contact_form", "with_front" => true ),
		"query_var" => true,
		"menu_icon" => "dashicons-welcome-widgets-menus",
		"supports" => array( "title" ),
	);

    register_post_type( "jf_contact_form", $args );
    

    /**
	 * Post Type: Events.
	 */

	$labels = array(
		"name" => __( "Events", "jellyfish" ),
		"singular_name" => __( "Event", "jellyfish" ),
	);

	$args = array(
		"label" => __( "Events", "jellyfish" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "event",
		"map_meta_cap" => true,
		"hierarchical" => false,
        "menu_position" => 10,
		"rewrite" => array( "slug" => "events", "with_front" => true ),
		"query_var" => true,
		"menu_icon" => "dashicons-calendar-alt",
		"supports" => array( "title", "editor", "thumbnail", "excerpt", "custom-fields", "revisions" ),
	);

    register_post_type( "events", $args );
    
    /**
     * Post Type: Event Forms.
     */

    $labels = array(
        "name" => __( "Event Forms", "jellyfish" ),
        "singular_name" => __( "Event Form", "jellyfish" ),
    );

    
    $args = array(
        "label" => __( "Event Forms", "jellyfish" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "exclude_from_search" => false,
        "capability_type" => "event_form",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "menu_position" => 11,
        "rewrite" => array( "slug" => "event_forms", "with_front" => true ),
        "query_var" => true,
        "menu_icon" => "dashicons-welcome-widgets-menus",
        "supports" => array( "title" , "custom-fields", "revisions" ),
    );

    register_post_type( "event_forms", $args );

    /**
     * Post Type: Event Leads.
     */

    $labels = array(
        "name" => __( "Event Leads", "jellyfish" ),
        "singular_name" => __( "Event Lead", "jellyfish" ),
    );

    $args = array(
        "label" => __( "Event Leads", "jellyfish" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "exclude_from_search" => false,
        "capability_type" => "event_lead",
        "menu_position" => 12,
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "event_leads", "with_front" => true ),
        "query_var" => true,
        "menu_icon" => "dashicons-money",
        "supports" => array( "title" ),
    );

    register_post_type( "event_leads", $args );
    /**
	 * Post Type: People.
	 */

	$labels = array(
		"name" => __( "People", "jellyfish" ),
		"singular_name" => __( "People", "jellyfish" ),
	);

	$args = array(
		"label" => __( "People", "jellyfish" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "people",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "people", "with_front" => true ),
		"query_var" => true,
		"menu_icon" => "dashicons-businessman",
		"supports" => array( "title", "editor" ),
	);

	register_post_type( "people", $args );

    /**
     * Post Type: News and Insights.
     */

    $labels = array(
        "name" => __( "News and Insights", "jellyfish" ),
        "singular_name" => __( "News and Insight", "jellyfish" ),
    );

    $args = array(
        "label" => __( "News and Insights", "jellyfish" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "news-insights",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "exclude_from_search" => false,
        "capability_type" => "article",
        "map_meta_cap" => true,
        "hierarchical" => true,
        "rewrite" => array( "slug" => "news-insights", "with_front" => true ),
        "query_var" => true,
        "supports" => array( "title", "editor", "revisions","custom-fields" ),
        "menu_icon" => "dashicons-megaphone",
        "menu_position" => 5,
        //"taxonomies" => array( "news_categories" ),
    );

    register_post_type( "news_insights", $args );
   
    /**
	 * Post Type: Case Study
	 */

	$labels = array(
		"name" => __( "Case Study", "jellyfish" ),
		"singular_name" => __( "Case Study", "jellyfish" ),
	);

	$args = array(
		"label" => __( "Case Study", "jellyfish" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "case_study",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "case_study",
		"map_meta_cap" => true,
		"hierarchical" => false,
        "menu_position" => 6,
		"rewrite" => array( "slug" => "case-study", "with_front" => true ),
		"query_var" => true,
	);

	register_post_type( "case_study", $args );
    
    /**
     * Post Type: Regions
     */

    $labels = array(
        "name" => __( "Regions", "jellyfish" ),
        "singular_name" => __( "Region", "jellyfish" ),
    );

    $args = array(
        "label" => __( "Regions", "jellyfish" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "regions",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "exclude_from_search" => false,
        "capability_type" => "region",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "regions", "with_front" => true ),
        "query_var" => true,
        "supports" => array( "title", "editor", "custom-fields", "revisions" ),
        "menu_icon" => "dashicons-admin-site",
        "menu_position" => 3
    );

    register_post_type( "regions", $args );
    
     /**
     * Post Type: Region selector menu
     */

    $labels = array(
        "name" => __( "Region Selector Menu", "jellyfish" ),
        "singular_name" => __( "Region Selector Menu", "jellyfish" ),
    );

    $args = array(
        "label" => __( "Region Selector Menu", "jellyfish" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "region_selector",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "exclude_from_search" => false,
        "capability_type" => "region_selector",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "region_selector", "with_front" => true ),
        "query_var" => true,
        "supports" => array( "title", "editor", "revisions" ),
        "menu_icon" => "dashicons-menu",
        "menu_position" => 3
    );

    register_post_type( "region_selector", $args );

    /**
     * Post Type: Jellyfish Settings
     */

    $labels = array(
        "name" => __( "Jellyfish Settings", "jellyfish" ),
        "singular_name" => __( "Jellyfish Settings", "jellyfish" ),
    );

    $args = array(
        "label" => __( "Jellyfish Settings", "jellyfish" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "jellyfish_settings",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "exclude_from_search" => false,
        "capability_type" => "jellyfish_setting",
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array( "slug" => "jellyfish-settings", "with_front" => true ),
        "query_var" => true,
        "supports" => array( "title"),
        "menu_icon" => "dashicons-admin-generic",
        "menu_position" => 20
    );

    register_post_type( "jellyfish_settings", $args );
	
	/**
	 * Post Type: Quotes.
	 */

	$labels = array(
		"name" => __( "Quotes", "jellyfish" ),
		"singular_name" => __( "Quote", "jellyfish" ),
	);

	$args = array(
		"label" => __( "Quotes", "jellyfish" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"delete_with_user" => false,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "quote",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "quotes", "with_front" => true ),
		"query_var" => true,
		"menu_icon" => "dashicons-format-quote",
		"supports" => array( "title" ),
	);

	register_post_type( "quotes", $args );
	
	/**
	 * Post Type: Perks & Benefits.
	 */

	$labels = array(
		"name" => __( "Perks & Benefits", "jellyfish" ),
		"singular_name" => __( "Perks & Benefits", "jellyfish" ),
	);

	$args = array(
		"label" => __( "Perks & Benefits", "jellyfish" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"delete_with_user" => false,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"exclude_from_search" => false,
		"capability_type" => "perk",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "perks_benefits", "with_front" => true ),
		"query_var" => true,
		"menu_icon" => "dashicons-awards",
		"supports" => array( "title", "editor", "thumbnail", "revisions","custom-fields"),
	);

	register_post_type( "perks_benefits", $args );
	
	
	/**
     * Post Type: Sample Posts.
     */

    $labels = array(
        "name" => __( "Sample Posts", "jellyfish" ),
        "singular_name" => __( "Sample Post", "jellyfish" ),
    );

    $args = array(
        "label" => __( "Sample Posts", "jellyfish" ),
        "labels" => $labels,
        "description" => "",
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => true,
        "rest_base" => "sample-posts",
        "has_archive" => false,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "exclude_from_search" => false,
        "capability_type" => "sample_posts",
        "map_meta_cap" => true,
        "hierarchical" => true,
        "rewrite" => array( "slug" => "sample-posts", "with_front" => true ),
        "query_var" => true,
        "supports" => array( "title", "editor" ,"custom-fields", "revisions" ),
        "menu_icon" => "dashicons-megaphone",
        "menu_position" => 5,        
    );

    register_post_type( "sample_posts", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );
