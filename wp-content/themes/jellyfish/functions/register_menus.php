<?php
/*
* REGISTER NAV MENUS
*/
if ( function_exists( 'register_nav_menus' ) ) {

	register_nav_menus(
		array(
		'primary-menu' => __( 'Primary Menu' ),
		'secondary-menu' => __( 'Secondary Menu' ),
		)
	);

}