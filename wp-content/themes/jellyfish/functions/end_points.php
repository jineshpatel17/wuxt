<?php
//define('SHORTINIT',1);

/*
* MENU ENDPOINT
* http://domain/wp-json/wp/v2/menu
*/
add_action( 'rest_api_init', function(){
    
    register_rest_route( 'wp/v2', '/menu', array(
        'methods' => 'GET',
        'callback' => 'rest_main_menu',
    ));
    
});


/*
* CALLBACK FROM MENU ENDPOINT
*/
function rest_main_menu(){

     $menu_items = wp_get_nav_menu_items($_REQUEST['id']);
     return $menu_items;

}


/*
* Yoast SEO Meta Prepare
*/
function yoastSEOMeta($postId, $type="default"){
    $default_lang_id = icl_object_id( $postId, 'post', true, 'en' );
	//Page slug is fixed: "settings"
    $yoast_jellyfish_settings = get_page_by_path( 'settings', OBJECT, 'jellyfish_settings' );
    $yoast_postID = icl_object_id( $yoast_jellyfish_settings->ID , 'jellyfish_settings', true, ICL_LANGUAGE_CODE );
	$yoast_settings = '';
	$yoast_settings = get_fields($yoast_postID);
	$yoast_fallback_image = $yoast_settings['open_graph']['open_graph_fallback_image'];
	
    if($type == "default") {
        $opengraphTitle = get_post_meta($postId,"_yoast_wpseo_opengraph-title",true);
        $opengraphDescription = get_post_meta($postId,"_yoast_wpseo_opengraph-description",true);
        $opengraphImage = empty(trim(get_post_meta($default_lang_id,"_yoast_wpseo_opengraph-image",true))) ? $yoast_fallback_image : trim(get_post_meta($default_lang_id,"_yoast_wpseo_opengraph-image",true));
        $twitterTitle = get_post_meta($postId,"_yoast_wpseo_twitter-description",true);
        $twitterDescription= get_post_meta($postId,"_yoast_wpseo_twitter-description",true);
        $twitterImage= empty(trim(get_post_meta($default_lang_id,"_yoast_wpseo_twitter-image",true))) ? $yoast_fallback_image : trim(get_post_meta($default_lang_id,"_yoast_wpseo_twitter-image",true));
    } else if($type == "news-insights") {
        $imageId = get_field("featured_media",$postId);
        $imageURL = empty(trim(wp_get_attachment_url($imageId))) ? $yoast_fallback_image : wp_get_attachment_url($imageId);
        $opengraphTitle = empty(trim(get_post_meta($postId,"_yoast_wpseo_opengraph-title",true))) ? get_the_title($postId) : trim(get_post_meta($postId,"_yoast_wpseo_opengraph-title",true));
        $opengraphDescription = empty(trim(get_post_meta($postId,"_yoast_wpseo_opengraph-description",true))) ? get_field("article_excerpt",$postId) : trim(get_post_meta($postId,"_yoast_wpseo_opengraph-description",true));
        $opengraphImage = empty(trim(get_post_meta($default_lang_id,"_yoast_wpseo_opengraph-image",true))) ? $imageURL : trim(get_post_meta($default_lang_id,"_yoast_wpseo_opengraph-image",true));
        $twitterTitle = empty(trim(get_post_meta($postId,"_yoast_wpseo_twitter-title",true))) ? get_the_title($postId) : trim(get_post_meta($postId,"_yoast_wpseo_twitter-title",true));
        $twitterDescription= empty(trim(get_post_meta($postId,"_yoast_wpseo_twitter-description",true))) ? get_field("article_excerpt",$postId) : trim(get_post_meta($postId,"_yoast_wpseo_twitter-description",true));
        $twitterImage= empty(trim(get_post_meta($default_lang_id,"_yoast_wpseo_twitter-image",true))) ? $imageURL : trim(get_post_meta($default_lang_id,"_yoast_wpseo_twitter-image",true));
    }
    
    $yoastArray = [
      "og_title" => $opengraphTitle,
      "og_description" => $opengraphDescription,
      "og_image" => $opengraphImage,
      "twitter_title" => $twitterTitle,
      "twitter_description" => $twitterDescription,
      "twitter_image" => $twitterImage
    ];
    
    return $yoastArray;
}
/* 
* Add filter to respond with next and previous post in News and Insights custom post type response.
*/
add_filter( 'rest_prepare_news_insights', function( $response, $post, $request ) {
    // Only do this for single post requests.
    if( $request->get_param('per_page') === 1 ) {
          global $post;
          // Get the so-called next post.
          $next = get_adjacent_post( false, '', false );
          // Get the so-called previous post.
          $previous = get_adjacent_post( false, '', true );
          // Format them a bit and only send id and slug (or null, if there is no next/previous post).
          $response->data['next'] = ( is_a( $next, 'WP_Post') ) ? array( "id" => $next->ID, "slug" => $next->post_name, "title" => $next->post_title) : null;
          $response->data['previous'] = ( is_a( $previous, 'WP_Post') ) ? array( "id" => $previous->ID, "slug" => $previous->post_name, "title" => $previous->post_title) : null;
    }
    $jellyfish_settings = get_page_by_path( 'settings', OBJECT, 'jellyfish_settings' );
    $settingPostID = icl_object_id( $jellyfish_settings->ID , 'jellyfish_settings', true, ICL_LANGUAGE_CODE );
    
    $contact_news = getSettingsValues(ICL_LANGUAGE_CODE);
    $logourl = $contact_news['search_engine_optimization']['jellyfish_logo'];
    $contact_news = $contact_news["footer_contact_settings"]["news_footer_contact"];
    $response->data['acf']['default_footer_contact_id'] = $contact_news;
    $readmore_link_label = get_field('news_settings',$settingPostID)['read_more_link_label'];
    $response->data['acf']['readmore_link_label'] = $readmore_link_label;
    $response->data['acf']['featured_media_url'] = wp_get_attachment_url($response->data['acf']['featured_media']);
    $response->data['acf']['jellyfish_logo'] = $logourl;
    $response->data['yoast_meta']['custom'] = yoastSEOMeta($post->ID,"news-insights");
    /* check for categories */
    if(!is_user_logged_in()) {
          if(count($response->data['news_categories']) > 0) {
              foreach($response->data['news_categories'] as $index=>$categoryid) {
                  $category_data = get_taxonomy_data("id",$categoryid,"news_categories");
                  $response->data['news_categories'][$index] = $category_data->name;
                   if($category_data->name == "News") {
                        $readmore_link_label = get_field('news_settings',$settingPostID)['read_more_link_label'];
                        $response->data['acf']['readmore_link_label'] = $readmore_link_label;
                    } else if($category_data->name == "Insights") {
                        $readmore_link_label = get_field('insight_settings',$settingPostID)['read_more_link_label'];
                        $response->data['acf']['readmore_link_label'] = $readmore_link_label;
                    } 
              }
          }
      }
    return $response;
  }, 10, 3 );


/* 
* Add filter to respond with events post type response.
*/
add_filter( 'rest_prepare_events', function( $response, $post, $request ) {
  $jellyfish_settings = get_page_by_path( 'settings', OBJECT, 'jellyfish_settings' );
  $settingPostID = icl_object_id( $jellyfish_settings->ID , 'jellyfish_settings', true, ICL_LANGUAGE_CODE );
  
  $googleMapApiKey = get_field('offices_settings',$settingPostID)['google_map_api_key'];
  $response->data['google_map_api_key'] = $googleMapApiKey;
  $response->data['yoast_meta']['custom'] = yoastSEOMeta($post->ID);
  return $response;

}, 10, 3 );

/* 
* Add filter to respond with events post type response.
*/
add_filter( 'rest_prepare_people', function( $response, $post, $request ) {
    $imageId = $response->data['acf']['profile_image'];
    if(!empty(trim($imageId))) {
      $response->data['acf']['profile_image'] = wp_get_attachment_url($imageId);
    }
  return $response;
}, 10, 3 );

/* 
* Add filter to respond with events form builder post type response.
*/
add_filter( 'rest_prepare_event_forms', function( $response, $post, $request ) {
    $settings = getSettingsValues(ICL_LANGUAGE_CODE);
    $gRecaptcha_sitekey = $settings['google_recaptcha_site_key'];
    $gRecaptcha_secretkey = $settings['google_recaptcha_secret_key'];
    $response->data['google_recaptcha_site_key'] = $gRecaptcha_sitekey;
    
  return $response;
}, 10, 3 );

/* 
* Add filter to respond with events form builder post type response.
*/
add_filter( 'rest_prepare_jf_contact_form', function( $response, $post, $request ) {
    $settings = getSettingsValues(ICL_LANGUAGE_CODE);
    $gRecaptcha_sitekey = $settings['google_recaptcha_site_key'];
    $gRecaptcha_secretkey = $settings['google_recaptcha_secret_key'];
    $response->data['google_recaptcha_site_key'] = $gRecaptcha_sitekey;
    
  return $response;
}, 10, 3 );

add_filter( 'run_wptexturize', '__return_false' );


/*
* Jellyfish Settings ENDPOINT
* http://domain/wp-json/wp/v2/settings/jellyfish-settings/
*/
add_action( 'rest_api_init', function(){
    //Jellyfish Settings Rest END POINT registered
    register_rest_route( 'wp/v2/settings', 'jellyfish-settings', array(
        'methods' => 'GET',
        'callback' => 'rest_jellyfish_settings_new'
    ));
    //Register end point for the Authorization token.
    register_rest_route( 'wp/v2/jfauth', 'generate-token', array(
        'methods' => 'POST',
        'callback' => 'jfauth_generate_token'
    ));
    
    //Register end point for the Google recaptcha verification token.
    register_rest_route( 'wp/v2/jflead', 'submission', array(
        'methods' => 'POST',
        'callback' => 'lead_submission'
    ));
    
    //Register end point for the Google recaptcha verification token.
    register_rest_route( 'wp/v2/eventlead', 'submission', array(
        'methods' => 'POST',
        'callback' => 'lead_submission'
    ));
    
    //Register end point for the Authorization token.
    register_rest_route( 'wp/v2', 'get-ip', array(
        'methods' => 'GET',
        'callback' => 'getClientIp'
    ));
    
    //Get Client IP ISO Code
    register_rest_route( 'wp/v2', 'geoiplocation', array(
        'methods' => 'GET',
        'callback' => 'geoIPLocation'
    ));
});

//Jellyfish Settings Rest end point callback 
function rest_jellyfish_settings_new(){
    $customOptions = getSettingsValues(ICL_LANGUAGE_CODE);
    unset($customOptions['google_recaptcha_secret_key'],$customOptions['salesforce_end_point_url']);
    if(isset($_REQUEST['field_groups'])) {
      $settingsType = wp_parse_list( $_REQUEST['field_groups'] );
      foreach($settingsType as $type) {
        /*Sanitize the input and Lowercase alphanumeric characters, dashes and underscores are allowed. Uppercase characters will be converted to lowercase.*/
        $type = sanitize_key($type);
        $settings[$type] = $customOptions[$type];
      }
    }
    else {
      $settings = $customOptions;
    }
    if ( ! empty( $settings ) ) {
        array_walk_recursive( $settings, 'get_fields_recursiveACF' );
    }
    return $settings;
}

//Jellyfish Settings Rest end point callback 
function rest_jellyfish_settings(){
    $customOptions = getSettingsValues(ICL_LANGUAGE_CODE);

    if(isset($_REQUEST['field_groups'])) {
      $settingsType = wp_parse_list( $_REQUEST['field_groups'] );
      foreach($settingsType as $type) {
        /*Sanitize the input and Lowercase alphanumeric characters, dashes and underscores are allowed. Uppercase characters will be converted to lowercase.*/
        $type = sanitize_key($type);
        $settings[$type] = $customOptions[$type];
      }
    }
    else {
      $settings = $customOptions;
    }
    if ( ! empty( $settings ) ) {
        array_walk_recursive( $settings, 'get_fields_recursiveACF' );
    }
    return $settings;
}

function get_fields_recursiveACF( $item ) {
    if ( is_object( $item ) ) {
        $item->acf = array();
        if ( $fields = get_fields( $item ) ) {
            $item->acf = $fields;
            array_walk_recursive( $item->acf, 'get_fields_recursive' );
        }
    }
}
/*
* CALLBACK FROM JOBS ENDPOINT
* PULL ALL JOBS FROM THE CMS.
*/
function rest_jobs() {

    $job_information = [];

    $active_jobs = get_posts([
        'post_type' => 'jobs',
        'post_status' => 'publish',
        'posts_per_page' => 999999,
    ]);

    foreach ($active_jobs as $key => $job) {
        $job_information[$key]['ID'] = $job->ID;
        $job_information[$key]['post_title'] = $job->post_title;
        $job_information[$key]['post_name'] = $job->post_name;
        $job_information[$key]['post_content'] = $job->post_content;
        $job_information[$key]['guid'] = $job->guid;
        
        $job_meta = get_post_meta($job->ID);

        // Loop through the post meta data for a specific job and add all elements to $job_information array.
        foreach ($job_meta as $meta => $value) {            
            $job_information[$key][$meta] = $value[0];
        }
            
    }
    echo json_encode($job_information);
    die();
}

/* 
* Add filter to add the Offices General settings fields to response of offices rest end point.
*/
add_filter( 'rest_prepare_offices', function( $response, $post, $request ) {

  $jellyfish_settings = get_page_by_path( 'settings', OBJECT, 'jellyfish_settings' );
  $settingPostID = icl_object_id( $jellyfish_settings->ID , 'jellyfish_settings', true, ICL_LANGUAGE_CODE );
  $googleMapLink = get_field('offices_settings',$settingPostID)['google_map_link_label'];
  $response->data['acf']['map_link_label'] = $googleMapLink;
  return $response;
}, 10, 3 );

/* 
* Add filter to add the Offices General settings fields to response of offices page.
* Add filter to add the Jobs count to response of Vacancies Section page.
*/
add_filter( 'rest_prepare_page', function( $response, $post, $request ) {
//print_r(get_post_meta(get_the_id()));die;
    if(isset($response->data['template']) && ($response->data['template'] == "templates/vacancies-section-page.php" || $response->data['template'] == "templates/working-at-jellyfish.php")) {
        $new_response_with_job_data = array();
        $settings = getSettingsValues(ICL_LANGUAGE_CODE);
        //Perks & Benfits from Jellyfish settings and append to working at jellyfish api
        $perks = $settings['vacancy_individual_page']['other_job_details']['perks_and_benefits'];
        $response->data['acf']['perks'] = $perks;
        // get jobs data and append to the response
        $args = array(
            'posts_per_page'    => '-1',
            'post_type'     => 'jobs',
            'post_status' => 'publish'
        );
        
        $jobs = new WP_Query( $args );

        while ( $jobs->have_posts() ) { 
            $jobs->the_post();
            $all_countries[] = get_field("job_country_full",get_the_id());
        }
        
        
        $domains = array_filter(array_unique($all_countries));
       
        $totalJobCount = 0;
        foreach($domains as $key => $domain) {
            $get_jobs_total[$domain] = get_domain_jobs($domain);
            $totalJobCount += get_domain_jobs($domain);
         }
         $response->data['jobs_count'] = $totalJobCount;
        //return $response;     
    } else if(isset($response->data['template']) && $response->data['template'] == "templates/we-are.php") {
        
        /*
        // Add fetaure image id in relationship object
        if(isset($response->data['acf']['news_details']['news_articles'])) {
            $news_articles = $response->data['acf']['news_details']['news_articles'];
            foreach($news_articles as $news_article) {
                $post_thumbnail_id = get_post_thumbnail_id($news_article->ID);
                $news_article->featured_media = $post_thumbnail_id;
            }

        }
       
        $all_peoples = array();

        $args = array(
            'numberposts'   => -1,
            'post_type'     => 'people',
            'post_status' => 'publish'
        );
        
        $get_people = new WP_Query( $args );

        $i=0;

        while ( $get_people->have_posts() ) { 
            $get_people->the_post();
            $all_peoples[$i]['name'] = get_field("name",get_the_id());
            $all_peoples[$i]['role'] = get_field("role",get_the_id());
            $all_peoples[$i]['image'] = get_field("image",get_the_id());   
            $i++;
        }

        if(count($all_peoples) > 0) {
            $response->data['acf']['our_people_details']['our_peoples'] = $all_peoples;
        }
 */
        /* Remove fields that are not needed in the response */
        if(!is_user_logged_in()) {
        $response_fields_to_unset = array("date",
                                    "date_gmt",
                                    "modified",
                                    "modified_gmt",
                                    "content",
                                    "featured_media",
                                    "parent",
                                    "menu_order",
                                    "ping_status",
                                    "id",
                                    "guid",
                                    "excerpt",
                                    "author",
                                    "comment_status",
                                    "link",
                                    "title"
                                );


        $response = unset_response($response,$response_fields_to_unset,"");
        }
     

    } else if(isset($response->data['template']) && $response->data['template'] == "templates/contact.php") {
        $settings = getSettingsValues(ICL_LANGUAGE_CODE);
        $gRecaptcha_sitekey = $settings['google_recaptcha_site_key'];
        $gRecaptcha_secretkey = $settings['google_recaptcha_secret_key'];
        $response->data['acf']['contact_form_settings'] = $settings['contact_form_settings'];
        $response->data['google_recaptcha_site_key'] = $gRecaptcha_sitekey;
    }
    else if(isset($response->data['template']) && $response->data['template'] == "templates/work.php") {
        $settings = getSettingsValues(ICL_LANGUAGE_CODE);
        //Perks & Benfits from Jellyfish settings and append to working at jellyfish api
        $casestudies = $settings['case_studies']['case_studies'];
        $response->data['acf']['case_studies'] = $casestudies;
    }
    else if(isset($response->data['template']) && $response->data['template'] == "templates/offices-page.php") {
        $settings = getSettingsValues(ICL_LANGUAGE_CODE);
        //Perks & Benfits from Jellyfish settings and append to working at jellyfish api
        $logourl = $settings['search_engine_optimization']['jellyfish_logo'];
        $googleMapLink = $settings['offices_settings']['google_map_link_label'];
        $response->data['acf']['map_link_label'] = $googleMapLink;
        $response->data['acf']['jellyfish_logo'] = $logourl;
    }
    $response->data['yoast_meta']['custom'] = yoastSEOMeta($post->ID);
  return $response;
}, 10, 3 );

/*
* get_domain_jobs will return jobs count for each domain.
*/
function get_domain_jobs($domain) {
    $args = array();
    $args['numberposts'] = "-1";
    $args['post_type'] = "jobs";
    $args['post_status'] = "publish";
    $args['meta_query'][] = array('key' => 'job_country_full','value' => $domain,'compare' => '=');

    $query[$domain] = new WP_Query( $args );
    $total[$domain] = $query[$domain]->found_posts;
    return $total[$domain];
}

/*
* get_taxonomy_data will return taxonomy data based on taxonomy term name, taxonomy term id and taxonomy.
*/
function get_taxonomy_data($by,$taxonomy_term_name,$taxonomy) {
    $get_taxonomy_data = get_term_by($by, $taxonomy_term_name, $taxonomy);
    return $get_taxonomy_data;
}

/* 
* Add filter to append jobs settings data to the jobs post type api.
*/
add_filter( 'rest_prepare_jobs', function($response, $post, $request ) {
    
    /* Get jobs settings data */    
    $settings = getSettingsValues(ICL_LANGUAGE_CODE);
    /* Get jobs settings data */    
   
     $description = "Come join us on our Digital Journey";   
     /* Append settings data with job response */
    if( isset($_REQUEST['slug'])) {
        $location = explode(',',$response->data['acf']['job_location']);        
        $jobLocationData = getLocationData($location[0]);
        $response->data['acf']['job_location_data'] = $jobLocationData;
        $response->data['yoast_meta']['yoast_wpseo_title'] = get_the_title($post->ID)." | Jellyfish";
        $response->data['yoast_meta']['yoast_wpseo_metadesc'] = $description;
        $response->data['vacancy_details'] = $settings['vacancy_individual_page'];
        $response->data['yoast_meta']['custom']['og_title'] = get_the_title($post->ID);
        $response->data['yoast_meta']['custom']['og_description'] = $description;
        $response->data['yoast_meta']['custom']['og_image'] = $settings['icims_jobs']['facebook_card_image'];
        $response->data['yoast_meta']['custom']['twitter_title'] = get_the_title($post->ID);
        $response->data['yoast_meta']['custom']['twitter_description'] = $description;
        $response->data['yoast_meta']['custom']['twitter_image'] = $settings['icims_jobs']['twitter_card_image'];
        
    } 
    
    return $response;
}, 10, 3 ); 

function getLocationData($location) {

    $args = array( 
        'post_type' => 'offices',
        'posts_per_page' => '1',
        'post_status'    => 'publish',
        'meta_query'     => array(
            'relation' => 'OR',
            array(
                'key'       => 'json_ld_fields_locality',
                'value'     => $location,
                'compare'   => '='
            ),
            array(
                'key'       => 'json_ld_fields_region',
                'value'     => $location,
                'compare'   => '='
            )
        ),
    
    );

    $offices = get_posts( $args );
    $locationArr = array();
    if(isset($offices[0]->ID) && !empty(trim($offices[0]->ID))){
        $locationArr = get_field("json_ld_fields",$offices[0]->ID);
        
    }
    
    return $locationArr;
}
/* Disable paging on jobs listing page */
/*
add_filter( "rest_jobs_query", function ($args, $request) {
                $args['nopaging'] = true; 
                return $args;
}, 15, 2); */

/* Unset fields from the response */
function unset_response($response,$fields_to_unset,$acf_fields_to_unset) {  
    
    if(isset($fields_to_unset)) {
        foreach($fields_to_unset as $field) {
            unset($response->data[$field]);
        }
    }
    
    if(isset($acf_fields_to_unset)) {
        foreach($acf_fields_to_unset as $acf_field) {
            unset($response->data['acf'][$acf_field]);
        }
    }
    
    $response = response_remove_links($response);
    
    return $response;
}

/* Remove _links from api response*/
function response_remove_links($data) {
  
    $data->remove_link( 'collection' );
    $data->remove_link( 'self' );
    $data->remove_link( 'about' );
    $data->remove_link( 'author' );
    $data->remove_link( 'replies' );
    $data->remove_link( 'version-history' );
    $data->remove_link( 'https://api.w.org/featuredmedia' );
    $data->remove_link( 'https://api.w.org/attachment' );
    $data->remove_link( 'https://api.w.org/term' );
    $data->remove_link( 'curies' );
    return $data;
}


/**
* Contact Form Validation Immidiate after rest api call and before save.
*/
function contact_form_validation($request) {
     /* Get settings data */    
    $settings = getSettingsValues(ICL_LANGUAGE_CODE);
     /* Get settings data */   
    $fields = $request['fields'];

    $invalid = false;
    $error = $validationMsg = $requiredFields = $numberFields = $emailFields = $characterLengthFields = array();
    
    
    if(sizeof($request) < 0 || sizeof($fields) < 0 || !isset($fields['post_id']) || empty(sanitize_text_field($fields['post_id'])) || !isset($request['title']) || empty(sanitize_text_field($request['title']))) {
      return new WP_Error( 'contact_form_validation', "Invalid request", array('status' => 400 ) );
    }
    
    // Block leads submitted from the personal email address
    
    if (block_personal_emails($fields['email']) > 0) {
        // Send mail to the user
        send_mail_to_user($fields['email']);
        return new WP_Error( 'contact_form_validation',$settings['contact_form_settings']['contact_form_personal_email_block_validation_message'], array('status' => 200 ) );
    }

    
    $fields_acf = get_fields($fields['post_id']);

    //Get Field array of applied validation fields.
    foreach($fields_acf['validation_messages'] as $messageObj) {
        foreach($messageObj as $message) {
            $validationMsg[$message['jf_validation_type']] = $message['validation_patern'];
        }
    }

   //Get Field array of applied validation fields.
    foreach($fields_acf['contact_form'] as $section) {
        foreach($section['fields'] as $field) {
            $searchVal = array("##field_name##", "##max_chars##");
            $replaceVal = array($field['jf_display_name'], $field['max_chars']);
            if(in_array('required',$field['jf_validation_type'])) {
                $msg = str_replace($searchVal,$replaceVal,$validationMsg['required']);
                $requiredFields[$field['jf_field_name']] = array("msg" => $msg, "length" => $field['max_chars']);
                if(!array_key_exists($field['jf_field_name'],$fields)) {
                  $invalid = true;
                }
            }
            if(in_array('email',$field['jf_validation_type'])) {
                $msg = str_replace($searchVal,$replaceVal,$validationMsg['email']);
                $emailFields[$field['jf_field_name']] = array("msg" => $msg, "length" => $field['max_chars']);
            }
            if(in_array('number',$field['jf_validation_type'])) {
                $msg = str_replace($searchVal,$replaceVal,$validationMsg['number']);
                $numberFields[$field['jf_field_name']] = array("msg" => $msg, "length" => $field['max_chars']);
            }
            if(in_array('character_length',$field['jf_validation_type'])) {
                $msg = str_replace($searchVal,$replaceVal,$validationMsg['character_length']);
                $characterLengthFields[$field['jf_field_name']] = array("msg" => $msg, "length" => $field['max_chars']);
            }
        }
    }
    if($invalid == true) {
      return new WP_Error( 'contact_form_validation', "Invalid request", array('status' => 400 ) );
    }

    foreach($fields as $key => $val) {
        if(array_key_exists($key,$emailFields) && !is_email(sanitize_email($val))) {
          $error[$key] = $emailFields[$key]['msg'];
        }
        if(array_key_exists($key,$characterLengthFields) && strlen(sanitize_text_field($val)) > $characterLengthFields[$key]['length']) {
          $error[$key] = $characterLengthFields[$key]['msg'];
        }
        if(array_key_exists($key,$numberFields) && !preg_match('/^\+{0,1}[\d][\d\-]+$/', sanitize_text_field($val))) {
          $error[$key] = $numberFields[$key]['msg'];
        }
        if(array_key_exists($key,$requiredFields) && empty(sanitize_text_field($val))) {
          $error[$key] = $requiredFields[$key]['msg'];
        }
    }
    if(sizeof($error) > 0) {
        return new WP_Error( 'contact_form_validation', $error, array('status' => 200 ) );
    }
}

/**
* Event Form Validation 
*/
function event_form_validation($request) {
    
    $fields = $request['fields'];
    $invalid = false;
    $error = $validationMsg = $requiredFields = $numberFields = $emailFields = $characterLengthFields = array();
    // Block leads submitted from India and Russia location
    $user_ip = geoip_detect2_get_client_ip();
    $record = geoip_detect2_get_info_from_ip($user_ip, NULL);

    /* Get settings data */    
     $settings = getSettingsValues(ICL_LANGUAGE_CODE);
     
    if ($record->country->isoCode == "RU") {
        return new WP_Error( 'event_form_validation', $settings['contact_form_settings']['contact_form_country_validation_message'], array('status' => 200 ) );
    }
    
    if(sizeof($request) < 0 || sizeof($fields) < 0 || !isset($fields['post_id']) || empty(sanitize_text_field($fields['post_id'])) || !isset($request['title']) || empty(sanitize_text_field($request['title']))) {
      return new WP_Error( 'event_form_validation', "Invalid request", array('status' => 200 ) );
    }
    
    // Block leads submitted from the personal email address
    
    if (block_personal_emails($fields['email_address']) > 0) {
        // Send mail to the user
        send_mail_to_user($fields['email_address']);
        return new WP_Error( 'contact_form_validation', $settings['contact_form_settings']['contact_form_personal_email_block_validation_message'], array('status' => 200 ) );
    }

    $fields_acf = get_fields($fields['post_id']);

    //Get Field array of applied validation fields.
    foreach($fields_acf['validation_messages'] as $messageObj) {
        foreach($messageObj as $message) {
            $validationMsg[$message['jf_validation_type']] = $message['validation_patern'];
        }
    }

   //Get Field array of applied validation fields.
    foreach($fields_acf['event_form'] as $section) {
        foreach($section['fields'] as $field) {
            $searchVal = array("##field_name##", "##max_chars##");
            $replaceVal = array($field['jf_display_name'], $field['max_chars']);
            if(in_array('required',$field['jf_validation_type'])) {
                $msg = str_replace($searchVal,$replaceVal,$validationMsg['required']);
                $requiredFields[$field['jf_field_name']] = array("msg" => $msg, "length" => $field['max_chars']);
                if(!array_key_exists($field['jf_field_name'],$fields)) {
                  $invalid = true;
                }
            }
            if(in_array('email',$field['jf_validation_type'])) {
                $msg = str_replace($searchVal,$replaceVal,$validationMsg['email']);
                $emailFields[$field['jf_field_name']] = array("msg" => $msg, "length" => $field['max_chars']);
            }
            if(in_array('number',$field['jf_validation_type'])) {
                $msg = str_replace($searchVal,$replaceVal,$validationMsg['number']);
                $numberFields[$field['jf_field_name']] = array("msg" => $msg, "length" => $field['max_chars']);
            }
            if(in_array('character_length',$field['jf_validation_type'])) {
                $msg = str_replace($searchVal,$replaceVal,$validationMsg['character_length']);
                $characterLengthFields[$field['jf_field_name']] = array("msg" => $msg, "length" => $field['max_chars']);
            }
        }
    }
    if($invalid == true) {
      return new WP_Error( 'event_form_validation', "Invalid request", array('status' => 200 ) );
    }
    foreach($fields as $key => $val) {
        if(array_key_exists($key,$emailFields) && !is_email(sanitize_email($val))) {
          $error[$key] = $emailFields[$key]['msg'];
        }
        if(array_key_exists($key,$characterLengthFields) && strlen(sanitize_text_field($val)) > $characterLengthFields[$key]['length']) {
          $error[$key] = $characterLengthFields[$key]['msg'];
        }
        if(array_key_exists($key,$numberFields) && !preg_match('/^\+{0,1}[\d][\d\-]+$/', sanitize_text_field($val))) {
          $error[$key] = $numberFields[$key]['msg'];
        }
        if(array_key_exists($key,$requiredFields) && empty(sanitize_text_field($val))) {
          $error[$key] = $requiredFields[$key]['msg'];
        }
    }
    if(sizeof($error) > 0) {
        return new WP_Error( 'event_form_validation', $error, array('status' => 200 ) );
    }
}

/*
* This function will check if the user's email is personal or not.
*/
function block_personal_emails($user_email) {
    
    // Define array of block email types
    $block_email_types = array("@gmail(.*)","@yahoo.(.*)","@hotmail(.*)","@aol(.*)","@outlook(.*)","@rediff(.*)");
    
    // Get user email type
    $user_email_type = strstr($user_email, "@");


    // Check if the user email matches any of the block email patterns
    foreach ($block_email_types as $block_email_type) {
        preg_match('/^'.$block_email_type.'/', $user_email_type, $matches);
        if (count($matches) > 0) {
            break;
        }
    }

    return count($matches);

}

function send_mail_to_user($email) {
    
    /* Get settings data */    
    $settings = getSettingsValues(ICL_LANGUAGE_CODE);
    /* Get settings data */ 
    
    $to = $email;
    
    $subject = $settings['contact_form_settings']['personal_email_address_mail_settings']['mail_subject'];
    
    $message = $settings['contact_form_settings']['personal_email_address_mail_settings']['mail_message'];
                 
    $headers = array(
    'From: '.$settings['contact_form_settings']['personal_email_address_mail_settings']['mail_from'].'',
    'Content-Type: text/html; charset=UTF-8',
    );

    wp_mail( $to, $subject, $message, $headers );
}

/**
* Authorization token generation end point callback
*/
function jfauth_generate_token(){
    $curIp = geoip_detect2_get_client_ip();
    //$curIp = "10.250.0.4";
    $clientSalt = $_POST['salt'];
   
   //file_put_contents(dirname(__FILE__).'/log.log', $curIp, FILE_APPEND);die;
    $postId = trim($_POST['preview_id']);
    $nonceKey = md5("JeLLyFiSh_".$postId);
    $validSalt = false;
    //error_log("\nBefore", 3, dirname(__FILE__)."/error.log");
    for($i=10; $i<=50; $i++) {
      $serverSalt = $curIp.md5('jEllYfIsH').$i;
      //error_log("\n".$clientSalt." = ".md5($serverSalt), 3, dirname(__FILE__)."/error.log");
      if($clientSalt == md5($serverSalt)) {
        $validSalt = true;
        break;
      }
    }
    
    //error_log("\n After:".$validSalt, 3, dirname(__FILE__)."/error.log");
    if($validSalt == true || ($nonceKey == $_POST['wpnonce'])) {
    //if($validSalt == true) {
         try{
            $request = new WP_REST_Request( 'POST', '/jwt-auth/v1/token');
            //unset($_SERVER["HTTP_AUTHORIZATION"]);
            //$request->set_header( 'AUTHORIZATION','Baaa aaa');
            $request->set_body_params( [ 'username' => WORDPRESS_ADMIN_USER, 'password' => WORDPRESS_ADMIN_PASSWORD] );
            $response = rest_do_request( $request );
            $server = rest_get_server();
            $data = $server->response_to_data( $response, false );
            return $data ;
        }
        catch(Exceptions $e) {
            return new WP_Error( 'authorization_token', $e, array('status' => 400 ) );
        }
    }
    else {
        return new WP_Error( 'authorization_token', 'Invalid Request', array('status' => 400 ) );
    }
}


/**
* Contact & Event Lead Submission
*/
function lead_submission($request) {

    if ( defined( 'JFL_DIR' ) ) {
        require_once JFL_DIR . '/jf-lead-submission.php';
        // run_jf_lead_submission($request);	
        $jf_lead_submision_plugin = new Jf_Lead_Submission();
    }
	
	if ( ! defined( 'LEAD_LOG_PATH' ) ) {
		define("LEAD_LOG_PATH", WP_CONTENT_DIR ."/lead_submission_log_".date('dmy').".log");
	}
	error_log(date('Y-m-d H:i:s')." INFO - Entry to lead_submission function.\n", 3, LEAD_LOG_PATH);  

    $fields = $request['fields'];
    $leadType = $request['jf_lead_type'];
    
    try {
		if (!empty(trim($leadType)) && ($leadType == 'contact' || $leadType == 'event')) {
            // Validate form fields with recaptcha before processing.
            $recaptcha_response = validate_recaptcha($request);
			if ($recaptcha_response['success'] == 'true') {
				if ($leadType == 'contact') {

					if (function_exists('contact_form_validation')) {
                        $valid_status = contact_form_validation($fields);
					} else {
						error_log(date('Y-m-d H:i:s')." Error - function contact_form_validation() not found, Please verify If JF Lead submission plugin is activated.\n", 3, LEAD_LOG_PATH);  
					}
					
				} else {
					$valid_status = event_form_validation($fields);
				}
				
				if (sizeof($valid_status) == 0) {
					if ($leadType == 'contact' || $leadType == 'event') {
						if (class_exists( 'Jf_Lead_Processor')) {
                            error_log(date('Y-m-d H:i:s')." INFO - prepare_contact_lead_data Function exists.\n", 3, LEAD_LOG_PATH);  
							$leads_data = $jf_lead_submision_plugin->jf_lead_processor($fields, $leadType);
							error_log(date('Y-m-d H:i:s')." INFO - prepare_contact_lead_data lead prepare done.\n", 3, LEAD_LOG_PATH);
						} else {
							error_log(date('Y-m-d H:i:s')." Error - Class Jf_Lead_Processor not found, Please verify JF Lead submission plugin is activated.\n", 3, LEAD_LOG_PATH);  
						}
					}
                    
                    // Encrypt lead data and store into DB
                    $qid = $jf_lead_submision_plugin->save_lead($leadType, $leads_data);				
					
					if($qid > 0){
						$form_builder_id = $fields['fields']['post_id'];
						$success_message = get_field('success_message',$form_builder_id);
						return new WP_Error( 'success', $success_message, array('status' => 200 ) );
					} else {
						return new WP_Error( 'success', 'Something went wrong', array('status' => 200 ) );
					}
				} else {
					return $valid_status;
				}
			} else {
			    return new WP_Error( 'error', 'Recaptcha Verification Failed, Please try again.', array('status' => 200 ) );
			}
		} else {
			return new WP_Error( 'error', 'Invalid Request for Lead Submission', array('status' => 200 ) );
		}
    }
    catch(Throwable $e) {
        return new WP_Error( 'error', 'Something went wrong, Please try again.', array('status' => 200 ) );
    }
}


/*
* Validate form with recaptcha.
*/
function validate_recaptcha($request) {
    $hostName = get_site_url();

    $settings = getSettingsValues(ICL_LANGUAGE_CODE);
    $secret = $settings['google_recaptcha_secret_key'];
    $response = $request['response'];

    $endpoint_url = "https://www.google.com/recaptcha/api/siteverify";
    $data = "secret=".$secret."&response=".$response;
    $ch = curl_init($endpoint_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
        'Content-Type: application/x-www-form-urlencoded'
        )
    );
    $result = curl_exec($ch);
    $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    $recaptcha_response = json_decode($result,1);

    return $recaptcha_response;
}

/*
* Get Client IP
*/
function getClientIp(){
    $curIp = geoip_detect2_get_client_ip();
    return $curIp;
}

/*
* Get Client IP Location
*/
function geoIPLocation(){
    if (function_exists('geoip_detect2_get_info_from_current_ip')) {
        $record = geoip_detect2_get_info_from_current_ip(NULL);
        $country_full = $record->raw['country']['iso_code'];
        return (empty(trim($country_full)))? "GB" : $country_full;
    } else {
        return "GB";
    }
}

/* 
* Add filter to append the success message from Jellyfish Contact forms post type
*/
add_filter( 'rest_prepare_event_leads', function($response, $post, $request ) {
    $form_builder_id = $request['fields']['post_id'];
    $success_message = get_field('success_message',$form_builder_id);    
    $response->data['success_message'] = $success_message;
    return $response;
}, 10, 3 );

/* 
* Add filter to respond with next and previous post in News and Insights custom post type response.
*/
add_filter( 'rest_prepare_case_study', function( $response, $post, $request ) {
    // Only do this for single post requests.
    global $post;
    // Get the so-called next post.
    $next = get_adjacent_post( false, '', true );
    // Get the so-called previous post.
    $previous = get_adjacent_post( false, '', false );
    // Format them a bit and only send id and slug (or null, if there is no next/previous post).
    $response->data['next'] = ( is_a( $next, 'WP_Post') ) ? array("slug" => $next->post_name, "title" => $next->post_title) : null;
    $response->data['previous'] = ( is_a( $previous, 'WP_Post') ) ? array("slug" => $previous->post_name, "title" => $previous->post_title) : null;
    
    $contact_news = getSettingsValues(ICL_LANGUAGE_CODE);
    $contact_news = $contact_news["footer_contact_settings"]["case_studies_footer_contact"];
    $response->data['acf']['default_footer_contact_id'] = $contact_news;
    $response->data['yoast_meta']['custom'] = yoastSEOMeta($post->ID);
    
    return $response;
  }, 10, 3 );
 
