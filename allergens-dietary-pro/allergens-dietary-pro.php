<?php
// exit if user can access this file directly
if (!defined('ABSPATH')) {
	exit;
}

'
/*
Plugin Name: Allergens and Dietary Pro
Plugin URI:
Version:     0.19.1.3
Description: Adds Allergens and Dietary options that can be used with WooCommerce products.
Requires at least: 6.3.1
Requires PHP: 7.4
Author:      Ictoria.nl
Author URI:  http://ictoria.nl
License:     GPLv3 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: allergens-dietary-pro
Domain Path: /languages/
WC Tested Up To: 9.3.3
*/
';

__('Adds Allergens and Dietary options that can be used with WooCommerce products.', 'allergens-dietary-pro');



// "Allergens and Dietary" is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// any later version.
//
// "Allergens and Dietary" is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with "Allergens and Dietary". If not, see https://www.gnu.org/licenses/gpl-3.0.html

// Set constant values that are used to retain file location references
define('ALLERGENS_DIETARY_PRO_NAME', 'allergens-dietary-pro');
define('ALLERGENS_DIETARY_PRO_FILE', __FILE__); // contains the full path to the plugin file
define('ALLERGENS_DIETARY_PRO_DIRNAME', __DIR__);
define('ALLERGENS_DIETARY_PRO_BASE', plugin_basename(__FILE__)); // contains the path: plugin_directory/plugin_file
// Check if WooCommerce is active and store the result in a constant value
if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
	define('ALLERGENS_DIETARY_PRO_WC_ACTIVE', true);
	define('ALLERGENS_DIETARY_PRO_WC_DIRNAME', dirname(__FILE__, 2) . '/woocommerce');
	// define('ALLERGENS_DIETARY_PRO_WC_DIRNAME', dirname(__FILE__, 2) );
} else {
	define('ALLERGENS_DIETARY_PRO_WC_ACTIVE', false);
}


// if ( in_array('allergens-dietary/allergens-dietary.php',(array) get_option('active_plugins', array() ,true)) == true) {
if( file_exists( dirname(__FILE__, 2).'/allergens-dietary/allergens-dietary.php')){
	define('ALLERGENS_DIETARY_ACTIVE', true);
	define('ALLERGENS_DIETARY_FREE_DIRNAME', dirname(__FILE__, 2).'/allergens-dietary');
} else {
	define('ALLERGENS_DIETARY_ACTIVE', false);
}

// error_log((ALLERGENS_DIETARY_ACTIVE ? 'true':'false'));
// // var_dump(ALLERGENS_DIETARY_ACTIVE);
// return;

/*
Plugin Name: Allergens and Dietary
Text Domain: allergens-dietary-icotoria
Domain Path: /languages/
*/
class Allergens_Dietary_Pro_Load_Language
{
	public function __construct()
	{
		add_action('plugins_loaded', array($this, 'translation_init'));
	}

	function translation_init()
	{
		load_plugin_textdomain('allergens-dietary-pro', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}
}

$nl_NL = new Allergens_Dietary_Pro_Load_Language();
$en_US = new Allergens_Dietary_Pro_Load_Language();

// class that contains the functions that are used by the activation/deactivation/uninstall hooks
class Allergens_Dietary_Pro_Startup
{
	// function that runs when the activation hook is called
	public static function on_activation()
	{
		if (ALLERGENS_DIETARY_PRO_WC_ACTIVE) {
			if(is_admin()){
				include_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/Allergens_Dietary_Pro_Plugin_Menu.php';
				Allergens_Dietary_Pro_Plugin_Menu::instance();
			}
		} else {
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/notice/notice.php';
		
		
			$level = 'notice-error';
			$message = sprintf(__('%1$sWooCommerce is inactive or not installed. Please install & activate WooCommerce%2$s', 'allergens-dietary-pro'), '<p>', '</p>');
			Allergens_Dietary_Pro_Notices::error_notice($level, $message);
			deactivate_plugins(ALLERGENS_DIETARY_PRO_BASE);
			
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}

		}		
		if( false === ALLERGENS_DIETARY_ACTIVE){
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/notice/notice.php';
			
			$message = sprintf(__('%1$sAllergens and Dietary is inactive or not installed. Please install & activate Allergens and Dietary%2$s', 'allergens-dietary-pro'), '<p>', '</p>');
			// deactivate_plugins(ALLERGENS_DIETARY_PRO_BASE);
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
			wp_die($message);
		
		}
	}

	// function that runs when the deactivation hook is called
	public static function on_deactivation()
	{
		// cookies might be needed if the filter needs to store previous search settings, and will have to be removed if this function is called

		// temporary delete_option for testing without having to uninstall/reinstall. This code is also found in the uninstall.php file of this plugin
		// delete_option('allergens_dietary_ictoria_settings');
		// delete_option('allergens_dietary_ictoria_options');
	}
}
register_deactivation_hook(ALLERGENS_DIETARY_PRO_BASE, array('Allergens_Dietary_Pro_Startup', 'on_deactivation'));
register_activation_hook(ALLERGENS_DIETARY_PRO_BASE, array('Allergens_Dietary_Pro_Startup', 'on_activation'));

if (ALLERGENS_DIETARY_PRO_WC_ACTIVE && ALLERGENS_DIETARY_ACTIVE){
	include_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/Allergens_Dietary_Pro_Plugin_Menu.php';
	Allergens_Dietary_Pro_Plugin_Menu::instance();
}



// Enqueue Thickbox scripts and styles
add_action('admin_enqueue_scripts', 'load_allergens_dietary_pro_thickbox');
function load_allergens_dietary_pro_thickbox()
{
	wp_enqueue_script('thickbox');
	wp_enqueue_style('thickbox');
}


// Add a "View Details" link for the changelog
add_filter('plugin_row_meta', 'add_allergens_dietary_pro_changelog_view_link', 10, 2);
function add_allergens_dietary_pro_changelog_view_link($plugin_meta, $plugin_file)
{
	if ($plugin_file == 'allergens-dietary-pro/allergens-dietary-pro.php') {
		$plugin_meta[] = '<a href="' . esc_url(admin_url('admin-ajax.php?action=view_changelog&TB_iframe=true&width=600&height=550')) . '" class="thickbox">Details bekijken</a>';
	}
	return $plugin_meta;
}

// Ajax handler for displaying changelog in Thickbox
add_action('wp_ajax_view_changelog', 'display_allergens_dietary_pro_changelog_in_thickbox');
function display_allergens_dietary_pro_changelog_in_thickbox()
{
	echo '<div class="wrap">';
	echo '<h1>Changelog</h1>';
	echo '<div>';
	echo wpautop(get_allergens_dietary_pro_changelog());
	echo '</div>';
	echo '</div>';
	exit;
}

// // Changelog voor admin menu
// add_action('admin_menu', 'allergens_dietary_changelog_menu');
// function allergens_dietary_changelog_menu() {
//     add_menu_page('Changelog', 'Changelog', 'manage_options', 'allergens-dietary-changelog', 'allergens_dietary_changelog_pagina');
// }

function get_allergens_dietary_pro_changelog()
{
	$readme_file = plugin_dir_path(__FILE__) . 'readme.txt';

	if (file_exists($readme_file)) {
		$content = file_get_contents($readme_file);
		$changelog = '';

		$changelog_start = strpos($content, '== Changelog ==');
		if ($changelog_start !== false) {
			$changelog_start += strlen('== Changelog ==');
			$changelog_end = strpos($content, '==', $changelog_start);
			$changelog = substr($content, $changelog_start, $changelog_end - $changelog_start);
		}

		return trim($changelog);
	}

	return 'Changelog not found.';
}