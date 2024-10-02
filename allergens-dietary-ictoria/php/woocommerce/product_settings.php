<?php
// exit if user can access this file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( "Allergens_Dietary_Ictoria_Allergy_Attachment_Queries" ) ) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
}

// this class contains functions used to add/remove allergens and dietary options to/from a WooCommerce product
class Allergens_Dietary_Ictoria_Product_Settings {
	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new Allergens_Dietary_Ictoria_Product_Settings();
		}
	}

	public function __construct() {
		add_filter( 'woocommerce_product_data_tabs', array( $this, 'data_tab' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'data_fields' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_options' ) );
	}

	// function that sets the name of the menu tab for this plugin
	public function data_tab( $product_data_tabs ) {
		$product_data_tabs['allergens-tab'] = array(
			'label'  => __( 'Allergens', 'allergens-dietary-ictoria' ),
			'target' => __( 'allergens_dietary_ictoria_product_data' ),
		);
		return $product_data_tabs;
	}

	/**
	 * @param none
	 * @brief This method shows the form to add/update allergens .\
	 * function that shows all available options when the menu tab of this plugin is selected
	 * @return void
	 * @since 1.0.0
	 * @date 30-9-2024
	 */
	public function data_fields() {
		global $post;

		$options = Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::getInstance();
		$allergens = $options->getAllAllergyAttachmments();

		
		// $list    = get_post_meta( $post->ID, __( 'allergens_dietary_ictoria' ), true );
		if ( empty( $list ) ) {
			$list = array();
		}

		$html = '<div id="allergens_dietary_ictoria_product_data" class="panel woocommerce_options_panel">
			<h2>' . __( 'Allergens', 'allergens-dietary-ictoria' ) . '</h2> <br/>';
		// create the html for all options, seperating them by category
		foreach ( $allergens as $allergen ) {
			// check if option is globally enabled
			//TODO: replace with actual check in use with new db structure
				// add the html to the relevant array entry
				$html .= '<div class="allergen-field">
					<input type="checkbox" value="1" />
					<span class="description">
						<img width="70" height="70" alt="' . $allergen['allergy_name'] .'" src="' . $allergen['attachment_path'] .'"/>&nbsp;' . $allergen['allergy_name'] . '
					</span>
				</div>';
			
		}

		$html .= '</div>';

		echo $html;
		
	}

	// function that stores all selected options in the productdata of the currently selected product
	public function save_product_options( $post_id ) {
		// $options = Allergens_Dietary_Ictoria_Functions::get_options();

		if (isset ($_POST['save'])){
			echo '<pre>';
			print_r($_POST);
			echo '</pre>';
		}

		// $list = array();
		// foreach ( $options as $key => $value ) {
		// 	if ( isset( $_POST[ $key . __( '_allergens_dietary_ictoria_option' ) ] ) ) {
		// 		$list[] = $key;
		// 	}
		// }
		// update_post_meta( $post_id, __( 'allergens_dietary_ictoria' ), $list );
	}
}
