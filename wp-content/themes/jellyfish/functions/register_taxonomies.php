<?php

function job_tax_init() {
	// create a new taxonomy
	register_taxonomy(
		'job_category',
		'jobs',
		array(
			'label' => __( 'Job categories' ),
			'rewrite' => array( 'slug' => 'job_cat' ),
			'capabilities' => array(
				'assign_terms' => 'edit_guides',
				'edit_terms' => 'publish_guides'
			)
		)
	);
}
add_action( 'init', 'job_tax_init' );

function cptui_register_my_taxes() {

	/**
	 * Taxonomy: Zone.
	 */

	$labels = array(
		"name" => __( "Zone", "jellyfish" ),
		"singular_name" => __( "Zone", "jellyfish" ),
	);

	$args = array(
		"label" => __( "Zone", "jellyfish" ),
		"labels" => $labels,
		"public" => true,
		"hierarchical" => false,
		"label" => "Zone",
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'zone', 'with_front' => true, ),
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "zone",
		"show_in_quick_edit" => false,
	);
	register_taxonomy( "zone", array( "partners","clients" ), $args );
	
	
	/**
	 * Taxonomy: Job Locations.
	 */

	$labels = array(
		"name" => __( "Job Locations", "jellyfish" ),
		"singular_name" => __( "Job Location", "jellyfish" ),
	);

	$args = array(
		"label" => __( "Job Locations", "jellyfish" ),
		"labels" => $labels,
		"public" => true,
		"hierarchical" => true,
		"label" => "Job Locations",
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array( 'slug' => 'job_locations', 'with_front' => true, ),
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "job_locations",
		"show_in_quick_edit" => false,
	);
	register_taxonomy( "job_locations", array( "jobs" ), $args );
    
    /**
    * Taxonomy: News Categories.
    */

    $labels = array(
        "name" => __( "News Categories", "jellyfish" ),
        "singular_name" => __( "News Category", "jellyfish" ),
    );

    $args = array(
        "label" => __( "News Categories", "jellyfish" ),
        "labels" => $labels,
        "public" => true,
        "hierarchical" => true,
        "label" => "News Categories",
        "show_ui" => true,
        "show_in_menu" => true,
        "show_in_nav_menus" => true,
        "query_var" => true,
        "rewrite" => array( 'slug' => 'news_categories', 'with_front' => true, ),
        "show_admin_column" => true,
        "show_in_rest" => true,
        "rest_base" => "news_categories",
        "show_in_quick_edit" => false,
    );
    register_taxonomy( "news_categories", array( "news_insights" ), $args );
}

add_action( 'init', 'cptui_register_my_taxes' );
