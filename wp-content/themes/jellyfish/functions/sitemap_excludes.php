<?php

/* Exclude Multiple Content Types From Yoast SEO Sitemap */
add_filter( 'wpseo_sitemap_exclude_post_type', 'sitemap_exclude_post_type', 10, 2 );

function sitemap_exclude_post_type( $value, $post_type ) {
    $post_type_to_exclude = array('people', 'jellyfish_settings', 'author');

    if( in_array( $post_type, $post_type_to_exclude ) ) return true;
}


/* Exclude Multiple Taxonomies From Yoast SEO Sitemap */
add_filter( 'wpseo_sitemap_exclude_taxonomy', 'sitemap_exclude_taxonomy', 10, 2 );

function sitemap_exclude_taxonomy( $value, $taxonomy ) {
    $taxonomy_to_exclude = array( 'author', 'category', 'post_tag', 'zone', 'news_categories');
    
    if( in_array( $taxonomy, $taxonomy_to_exclude ) ) return true;
}