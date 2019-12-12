<?php
/**
* IP based redirection to regions
*/
$currentRegion = geoIPLocation();
$language_redirection = array( 
		"GB" =>'en-gb',
		"US" =>'en-us',
		"ES" =>'en-es',
		"ZA" =>'en-za',
		"HK" =>'en-hk',
		"SG" =>'en-sg',
		"IN" =>'en-in',
		"DK" =>'en-dk',
		"KR" =>'en-kr',
		"AE" =>'en-ae',
		"JP" =>'en-jp',
		"IL" =>'en-il',
		"NL" =>'en-nl'
);
if(isset($language_redirection[$currentRegion])) {
	$url = "/".$language_redirection[$currentRegion]."/";
}
else {
	$url = "/en-us/";
}
$url .= ($_SERVER['QUERY_STRING'])? "?".$_SERVER['QUERY_STRING'] : '';
if ( wp_redirect( $url, "301" ) ) {
    exit;
}
?>