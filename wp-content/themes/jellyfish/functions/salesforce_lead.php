<?php

/* This function will post the leads data to the salesforce in background.
the contact us form from the website.
* @param $lead_data, $postID
*/
function AsyncPostLead( $lead_data,$postID ) {
    $response = post_lead_data($lead_data);
    update_field("salesforce_response", $response."\n".json_encode($lead_data), $postID); 
}
/* custom hook to scheduled the async salesforce post lead. */
//add_action( 'my_scheduled_event', 'AsyncPostLead', 10, 3 );


/* This function will create and post the leads data to the salesforce while submitting
the contact us form from the website.
* @param $post, $request, $update
*/
function save_contact_leads($post, $request, $update) {

    if($post->post_type == "contact_leads") {
        $lead_data = create_lead_data($post->ID);
        //The salesforce lead submission will be running into background after 5 second.
        wp_schedule_single_event( time() + 5, 'my_scheduled_event', array( $lead_data, $post->ID ) );
    }
}

/* hook into rest_insert_{post_type}, while submitting the contact us form from the website */
add_action('rest_insert_contact_leads', 'save_contact_leads', 20, 3);

/* hook into save_post, while updating the contact leads data from the wordpress cms */
add_action('save_post', 'update_contact_leads', 20, 3 );


/* This function will create and post the leads data to the salesforce while updating
the contact leads from the wordpress cms.
* @param $post_id, $post, $update
*/
function update_contact_leads($post_id, $post, $update) {
    $post_type = get_post_type($post_id);
    
    if($post_type == "contact_leads") {
        if($update == 1) {
            $send_to_salesforce = get_field("send_to_salesforce",$post_id);     
            if($send_to_salesforce[0] == "true") {
                $lead_data = create_lead_data($post_id);
                $response = post_lead_data($lead_data);
                update_field("salesforce_response", $response."\n".json_encode($lead_data), $post_id);
                update_post_meta($post_id, "send_to_salesforce", false );
            }       
        }
    }
}   

/* This function will create and post the leads data to the salesforce while submitting
the contact us form from the website.
* @param $post, $request, $update
*/
function save_event_leads($post, $request, $update) {

    if($post->post_type == "event_leads") {
    
        $lead_data = create_event_lead_data($post->ID);   
        //print_r($lead_data);die;
        //The salesforce lead submission will be running into background after 5 second.
        wp_schedule_single_event( time() + 5, 'my_scheduled_event', array( $lead_data, $post->ID ) );
    }
}

/* hook into rest_insert_{post_type}, while submitting the contact us form from the website */
add_action('rest_insert_event_leads', 'save_event_leads', 20, 3);

/* hook into save_post, while updating the contact leads data from the wordpress cms */
add_action('save_post', 'update_event_leads', 20, 3 );


/* This function will create and post the leads data to the salesforce while updating
the contact leads from the wordpress cms.
* @param $post_id, $post, $update
*/
function update_event_leads($post_id, $post, $update) {
    
    $post_type = get_post_type($post_id);
    
    if($post_type == "event_leads") {
        if($update == 1) {
            $send_to_salesforce = get_field("send_to_salesforce",$post_id);     
            if($send_to_salesforce[0] == "true") {
                $lead_data = create_event_lead_data($post_id);
                $response = post_lead_data($lead_data);
                update_field("salesforce_response", $response."\n".json_encode($lead_data), $post_id);
                update_post_meta($post_id, "send_to_salesforce", false );
            }       
        }
    }   
}
/* This function will create Leads array for a specific post to be posted to the salesforce 
* @param $post_id
* @return $lead_data
*/
function create_lead_data($post_id) {
    
        // initiate arrays.
        $lead_data = array();
		$settings = getSettingsValues(ICL_LANGUAGE_CODE);
		
        $subject = get_field("subject",$post_id);
        $full_name = get_field("full_name",$post_id);
        $email = get_field("email",$post_id);
        $phone = get_field("phone",$post_id);
        $company = get_field("company",$post_id);
        $message = get_field("message",$post_id);
        $jellyfish_communications = get_field("sign_up_for_jellyfish_communications",$post_id);
        $jellyfish_communications = $jellyfish_communications[0];
        $infinity_visitor_id = get_field("infinity_visitor_id",$post_id); //"539bd1ab-54d6-495d-a4fa-180210a30d01";
        $infinity_installation_id = $settings['contact_form_settings']['infinity_installation_id'];
        $ga_client_id = get_field("ga_client_id",$post_id); //"175863349.1529482027"; 
        $ga_track_id = get_field("ga_track_id",$post_id); //"UA-XXXXX-XX"; 
        $region = strtolower(get_field("region",$post_id)); //uk,us,es,za will be passed from vue
        $intrested_in = get_field("interested_in",$post_id);
        $record = geoip_detect2_get_info_from_current_ip(NULL);
        $country_full = $record->raw['country']['names']['en'];
        
        $lead_data['subject'] = $subject;
        $name = explode(" ", $full_name);
        if (count($name) == 1) {
            $lead_data['lastname'] = $name[0];
        } else {
            $lead_data['firstname'] = $name[0];
            unset($name[0]);
            $last_name = implode(' ', $name);
            $lead_data['lastname'] = $last_name != '' ? $last_name : null;
        }
        
        if(empty($company) || $company == '') {
            $company = 'Personal';
        }

        // Prepare contact form array to send to Salesforce
        $lead_data['Email'] = $email;
        $lead_data['Phone'] = $phone;
        $lead_data['Company'] = $company;
        $lead_data['message'] = $message;
        $lead_data['infinity_visitor_id'] = $infinity_visitor_id;
        $lead_data['JFCommunications'] = empty(trim($jellyfish_communications))? "No" : $jellyfish_communications;
        $lead_data['infinity_installation_id'] = $infinity_installation_id;
        $lead_data['GACLIENTID'] = $ga_client_id;
        $lead_data['GATRACKID'] = $ga_track_id;
        $lead_data['CAMPAIGNID'] = 'Jellyfish';
        $lead_data['Country'] = $country_full;
        $lead_data['country_full'] = $country_full;
        $lead_data['sleadtype'] = $intrested_in;

    return $lead_data;
}

/* This function will create Leads array for a specific post to be posted to the salesforce 
* @param $post_id
* @return $lead_data
*/
function create_event_lead_data($post_id) {
    
    // initiate arrays.
    $lead_data = array();
	$settings = getSettingsValues(ICL_LANGUAGE_CODE);
	
    $firstname = get_field("first_name",$post_id);
    $lastname = get_field("last_name",$post_id);
    $email = get_field("email_address",$post_id);
    $company = get_field("company",$post_id);
    $message = get_field("message",$post_id);
    $jellyfish_communications = get_field("sign_up_for_jellyfish_communications",$post_id);
    $jellyfish_communications = $jellyfish_communications[0];
    $infinity_visitor_id = get_field("infinity_visitor_id",$post_id); //"539bd1ab-54d6-495d-a4fa-180210a30d01";
	$infinity_installation_id = $settings['contact_form_settings']['infinity_installation_id'];
    $ga_client_id = get_field("ga_client_id",$post_id); //"175863349.1529482027"; 
    $ga_track_id = get_field("ga_track_id",$post_id); //"UA-XXXXX-XX"; 
    $region = strtolower(get_field("region",$post_id)); //uk,us,es,za will be passed from vue
	$campaign_id = get_field("campaign_id",$post_id);
    $record = geoip_detect2_get_info_from_current_ip(NULL);
    $country_full = $record->raw['country']['names']['en'];

    if(empty($company) || $company == '') {
        $company = 'Personal';
    }

    // Prepare array to send to Salesforce
    $lead_data['firstname'] = $firstname;
    $lead_data['lastname'] = $lastname;
    $lead_data['Email'] = $email;
    $lead_data['Company'] = $company;
    $lead_data['message'] = $message;
    $lead_data['infinity_visitor_id'] = $infinity_visitor_id;
    $lead_data['JFCommunications'] = empty(trim($jellyfish_communications))? "No" : $jellyfish_communications;
    $lead_data['infinity_installation_id'] = $infinity_installation_id;
    $lead_data['GACLIENTID'] = $ga_client_id;
    $lead_data['GATRACKID'] = $ga_track_id;
    $lead_data['CAMPAIGNID'] = $campaign_id;
    $lead_data['Country'] = $country_full;
    $lead_data['subject'] = 'Jellyfish Events';
    $lead_data['country_full'] = $country_full;

        
    return $lead_data;
}

/**
 * This function will post the lead data using Curl to salesforce
 * and will return the response from the salesforce.
 *
 * @param $data Contact
 *            Us data.
 * @return $result Result from salesforce.
 *
 */
function post_lead_data($data)
{
    $settings = getSettingsValues(ICL_LANGUAGE_CODE);
    $salesforce_endpoint_url = $settings['salesforce_end_point_url'];
    // Json encode the data.
    $data_string = json_encode($data);
    $salesforce_endpoint_url = $salesforce_endpoint_url;

    // Post json data using Curl.
    $ch = curl_init($salesforce_endpoint_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data_string)
        )
    );

    $result = curl_exec($ch);
    return $result;
}