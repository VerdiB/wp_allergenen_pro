<?php
//exit if user can access this file directly
if(!defined('ABSPATH')){
	exit;
}
//exit if uninstall.php is not called by WordPress
if(!defined('WP_UNINSTALL_PLUGIN')){
	exit;
}

$settings = get_option('allergens_dietary_ictoria_settings');
//if enabled, export all product data added by this plugin before removing them from WP and offer a dowloadable .csv file
if($settings['auto-export'] == true){
	//call export function here
}

//run code that removes all plugin data from products
//retrieve a list of post_id's
$ids = get_posts(array(
	'posts_per_page' => -1,
	'post_type' => array('product','product_variation'),
	'fields' => 'ids',
));
//loop through all products and delete the plugin data
for($i = 0; $i < count($ids); $i++)
{
	delete_post_meta($ids[$i], 'allergens_dietary_ictoria');
}

//delete all options added by this plugin. Files are automatically removed by WP
delete_option('allergens_dietary_ictoria_options');
delete_option('allergens_dietary_ictoria_settings');

?>