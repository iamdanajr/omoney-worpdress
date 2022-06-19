<?php
/**
 * @package Omoney
 */
/*
Plugin Name: Orange Money
Description: Orange money plugin to enable purchase by orange money.
Version: 1.0
Author: Mamoudou SISSOKO
Author URI: https://www.linkedin.com/in/mamoudou-sissoko-1169a6170
*/


if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  You\'re not supposed to be here.';
	exit;
}

define( 'OMONEY_VERSION', '1.0' );
define( 'OMONEY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

register_activation_hook( __FILE__, array( 'Omoney', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Omoney', 'plugin_deactivation' ) );

require_once( OMONEY_PLUGIN_DIR . 'class.omoney.http.helper.php' );
require_once( OMONEY_PLUGIN_DIR . 'class.omoney.manager.php' );
require_once( OMONEY_PLUGIN_DIR . 'class.omoney.php' );
add_action( 'init', array( 'Omoney', 'init' ) );

if (is_admin()) {
	require_once( OMONEY_PLUGIN_DIR . 'class.omoney-admin.php' );
	add_action( 'init', array( 'OmoneyAdmin', 'init' ) );
}