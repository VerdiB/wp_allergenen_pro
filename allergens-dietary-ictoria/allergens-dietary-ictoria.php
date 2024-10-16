<?php

require_once __DIR__ . '/src/class-allergen-functions.php';
require_once __DIR__ . '/src/Admin/class-allergen-admin-startup.php';
// require_once __DIR__ . '/src/class-allergen-allergens-dietary-ictoria.php';
// use Allergens_Dietary_Ictoria_Functions;



// exit if user can access this file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

spl_autoload_register( 'allergen_autoloader');

function allergen_autoloader($class_name) {
	if ( false !== strpos($class_name, 'Allergen' ) ) {
		$classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    	$class_file = strtolower($class_name);

		$class_file = str_replace( '_', '-', $class_name );
		$namespace_arr = explode( '\\', $class_name );
		$namespace_arr[ array_key_last( $namespace_arr ) ] = 'class-' . $namespace_arr[ array_key_last( $namespace_arr ) ];
		$class_file = implode( '\\', $namespace_arr ) . '.php';
		
		error_log($classes_dir . $class_file);

		if( file_exists( $classes_dir . $class_file ) ) {
			error_log($classes_dir . $class_file);
    		require_once $classes_dir . $class_file;
		}
  	}
}
//
// Plugin Name: Allergens and Dietary
// Plugin URI:
// Description: Adds Allergens and Dietary options that can be used with WooCommerce products.
// @Version:     1.0.0.0 
// Requires at least: 6.3.1
// Requires PHP: 7.4
// Author:      Ictoria.nl
// Author URI:  http://ictoria.nl
// License:     GPLv3 or later
// License URI: https://www.gnu.org/licenses/gpl-3.0.html
// Text Domain: allergens-dietary-ictoria
// Domain Path: /languages/
// WC Tested Up To: 8.1.1
//

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
define( 'ALLERGENS_DIETARY_ICTORIA_NAME', 'allergens-dietary-ictoria' );
define( 'ALLERGENS_DIETARY_ICTORIA_FILE', __FILE__ ); // contains the full path to the plugin file
define( 'ALLERGENS_DIETARY_ICTORIA_DIRNAME', __DIR__ );
// define( 'ALLERGENS_DIETARY_ICTORIA_BASE', plugin_basename( __FILE__ ) ); // contains the path: plugin_directory/plugin_file
define( 'ALLERGENS_DIETARY_ICTORIA_BASE', './src/class-allergen-allergens-dietary-ictoria' ); // contains the path: plugin_directory/plugin_file
// Check if WooCommerce is active and store the result in a constant value
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	define( 'ALLERGENS_DIETARY_ICTORIA_WC_ACTIVE', true );
} else {
	define( 'ALLERGENS_DIETARY_ICTORIA_WC_ACTIVE', false );
}

// load file with generic static methods
// require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/functions.php';
add_action( 'plugins_loaded', array( 'Allergen\\Allergens_Dietary_Ictoria_Startup', 'load_textdomain' ) );
require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/src/class-allergen-allergens-dietary-ictoria.php';
function activate(){
    Allergen\Allergens_Dietary_Ictoria_Startup::get_instance()::on_activation();
}

// class that contains the functions that are used by the activation/deactivation/uninstall hooks
error_log('test');
register_activation_hook( __FILE__, array( 'Allergen\\Allergens_Dietary_Ictoria_Startup', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'Allergen\\Allergens_Dietary_Ictoria_Startup', 'on_deactivation' ) );
if ( ALLERGENS_DIETARY_ICTORIA_WC_ACTIVE ) {
	if ( is_admin() ) {
		$integration_startup = new Allergen\Admin\Allergens_Dietary_Ictoria_Wc_Integration_Startup(__FILE__);
	}
} else {
	$level   = 'notice-error';
	$message = sprintf( __( '%1$sWooCommerce is inactive or not installed. Please install & activate WooCommerce%2$s', 'allergens-dietary-ictoria' ), '<p>', '</p>' );
	Allergen\Allergens_Dietary_Ictoria_Startup::error_notice( $level, $message );
}

add_filter('plugin_auto_update_setting_html', 'my_plugin_auto_update_link_html', 10, 3);

// get auto-update links
function my_plugin_auto_update_link_html($html, $plugin_file, $plugin_data) {
    if ($plugin_file === 'allergens-dietary-ictoria/allergens-dietary-ictoria.php') {
        $auto_updates_enabled = get_site_option('auto_update_plugins', array());

        // Check if the current plugin is in the list of auto-updated plugins
        if (in_array($plugin_file, $auto_updates_enabled)) {
            $html = '<a href="#" class="my-plugin-toggle-auto-update" data-plugin="' . esc_attr($plugin_file) . '" data-action="disable">Auto-updates uitschakelen</a>';
        } else {
            $html = '<a href="#" class="my-plugin-toggle-auto-update" data-plugin="' . esc_attr($plugin_file) . '" data-action="enable">Auto-updates inschakelen</a>';
        }
    }
    return $html;
}

// Enqueue the JavaScript file for handling auto-update toggles
add_action('admin_enqueue_scripts', 'my_plugin_enqueue_admin_script');
function my_plugin_enqueue_admin_script() {
    wp_enqueue_script('my-plugin-admin-js', plugins_url('admin.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('my-plugin-admin-js', 'myPluginAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('my_plugin_auto_update_nonce')
    ));
}

// Handle the Ajax request to toggle auto-updates
add_action('wp_ajax_my_plugin_toggle_auto_update', 'my_plugin_toggle_auto_update');
function my_plugin_toggle_auto_update() {
    check_ajax_referer('my_plugin_auto_update_nonce', 'security');

    // Check if the plugin and action parameters are set
    if (isset($_POST['plugin']) && isset($_POST['toggle_action'])) {
        $plugin = sanitize_text_field($_POST['plugin']);
        $action = sanitize_text_field($_POST['toggle_action']);

        $auto_updates = get_site_option('auto_update_plugins', array());

        // Enable auto-updates if requested
        if ($action === 'enable') {
            if (!in_array($plugin, $auto_updates)) {
                $auto_updates[] = $plugin;
                update_site_option('auto_update_plugins', $auto_updates);
            }
        // Disable auto-updates if requested
        } elseif ($action === 'disable') {
            if (in_array($plugin, $auto_updates)) {
                $auto_updates = array_diff($auto_updates, array($plugin)); 
                update_site_option('auto_update_plugins', $auto_updates);
            }
        }

        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}

// Filter to control auto-update settings for the plugin
add_filter('auto_update_plugin', 'my_plugin_auto_update_control', 10, 2);
function my_plugin_auto_update_control($update, $item) {
    if ($item->plugin === 'allergens-dietary-ictoria/allergens-dietary-ictoria.php') {
        return get_site_option('auto_update_plugins', array()) ? true : false;
    }
    
    return $update;
}

// Enqueue Thickbox scripts and styles
add_action('admin_enqueue_scripts', 'load_thickbox');
function load_thickbox() {
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
}

// Add a "View Details" link for the changelog
add_filter('plugin_row_meta', 'add_changelog_view_link', 10, 2);
function add_changelog_view_link($plugin_meta, $plugin_file) {
    if ($plugin_file == 'allergens-dietary-ictoria/allergens-dietary-ictoria.php') {
        $plugin_meta[] = '<a href="' . esc_url(admin_url('admin-ajax.php?action=view_changelog&TB_iframe=true&width=600&height=550')) . '" class="thickbox">Details bekijken</a>';
    }
    return $plugin_meta;
}

// Ajax handler for displaying changelog in Thickbox
add_action('wp_ajax_view_changelog', 'display_changelog_in_thickbox');
function display_changelog_in_thickbox() {
    echo '<div class="wrap">';
    echo '<h1>Changelog</h1>';
    echo '<div>';
    echo wpautop(get_plugin_changelog()); 
    echo '</div>';
    echo '</div>';
    exit;
}

// // Changelog voor admin menu
// add_action('admin_menu', 'allergens_dietary_changelog_menu');
// function allergens_dietary_changelog_menu() {
//     add_menu_page('Changelog', 'Changelog', 'manage_options', 'allergens-dietary-changelog', 'allergens_dietary_changelog_pagina');
// }

function get_plugin_changelog() {
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
