<?php
add_filter( 'preview_post_link', function ( $link, \WP_Post $post )
{
    $postType = $post->post_type;
    $postId = $post->ID;
    $siteUrl =  (ICL_LANGUAGE_CODE == 'en') ? get_site_url()."/en-us" : get_site_url()."/".ICL_LANGUAGE_CODE;
    $slug = ($postType == 'page') ? $post->post_name : str_replace("_","-",$postType)."/preview/";
    $nonceKey = "JeLLyFiSh_".$postId;
    $nonce = md5($nonceKey);
    
    return $siteUrl."/".$slug."?preview_id=".$postId."&wpnonce=".$nonce;

 }, 5, 2 ); // Notice the number of arguments is 2 for $link and $post
 

/*
add_filter( 'rest_prepare_revision', function( $response, $post ) {
    $data = $response->get_data();

    $data['acf'] = get_fields( $post->ID );

    return rest_ensure_response( $data );
}, 10, 2 );
*/
 
/**
 * Remove /wp/v2/media/:id permission check
 */
add_filter( 'rest_endpoints', function( array $endpoints ) {
    foreach ( $endpoints['/wp/v2/media/(?P<id>[\d]+)'] as &$endpoint ) {
        if ( isset($endpoint['methods']) && $endpoint['methods'] === 'GET' && in_array( 'get_item', $endpoint['callback'] ) ) {
            unset( $endpoint['permission_callback'] );
        }
    }
    return $endpoints;
});
