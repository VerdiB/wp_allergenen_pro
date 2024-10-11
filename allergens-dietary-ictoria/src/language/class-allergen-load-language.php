<?php

namespace Allergen\Language;

// exit if user can access this file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
Plugin Name: Allergens and Dietary
Text Domain: allergens-dietary-ictoria
Domain Path: /languages/
*/
class load_language {
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'translation_init' ) );
	}

	function translation_init() {
		load_plugin_textdomain( 'allergens-dietary-ictoria', false, dirname( plugin_basename( __FILE__ ) ) . './languages/' );
	}
}
// load languages on startup!
$nl_NL = new load_language();
$en_US = new load_language();
$en_GB = new load_language();


?>