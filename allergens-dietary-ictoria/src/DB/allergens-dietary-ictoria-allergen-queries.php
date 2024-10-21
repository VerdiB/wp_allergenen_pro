<?php

namespace Db;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @class Allergens_Dietary_Ictoria_Allergen_Queries
 * @brief This class is a singleton that handles all the queries for the allergens and dietary restrictions DB table.
 * @author V.B.
 * @date 11-9-2024
 * @since 1.0.0
 */
class Allergens_Dietary_Ictoria_Allergen_Queries {
	private static ?self $_instance = null;

	/**
	 * @brief This method returns the instance of the class.
	 * @return Allergens_Dietary_Ictoria_Allergen_Queries
	 * @author V.B.
	 * @since 1.0.0
	 * @date 11-9-2024
	 */
	public static function getInstance() {
		if ( self::$_instance === null ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {}

	/**
	 * @brief This method adds an allergen to the DB.
	 * @param array $data
	 * @return bool
	 * @since 1.0.0
	 * @date 11-9-2024
	 * @author V.B.
	 */
	public function addAllergens( array $data ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$wpdb->insert(
			$table_name,
			array(
				'allergy_name'        => $data['allergen_name'],
				'allergy_description' => $data['allergen_description'],
			),
			array(
				'%s',
				'%s',
			)
		);
		return ( isset( $wpdb->insert_id ) ) ? true : false;
	}

	public function checkAllergenExists( string $allergenName ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$sql = $wpdb->prepare(
			"SELECT * FROM $table_name WHERE allergy_name = %s",
			$allergenName
		);

		$result = $wpdb->get_results( $sql );

		return ( count( $result ) > 0 ) ? true : false;
	}

	public function updateAllergens( array $data ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$wpdb->update(
			$table_name,
			array(
				'allergy_name'        => $data['allergen_name'],
				'allergy_description' => $data['allergen_description'],
			),
			array(
				'allergy_name' => $data['allergen_name'],
			)
		);
	}

	public function getAllergen( string $allergenName ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$sql = $wpdb->prepare(
			"SELECT * FROM $table_name WHERE allergy_name = %s",
			$allergenName
		);

		$result = $wpdb->get_results( $sql );

		return $result;
	}

	public static function includeItems(){
		if ( ! class_exists( 'Allergens_Dietary_Ictoria_Allergy_Attachment_Queries' ) ) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
		}
		if ( ! class_exists( 'Allergens_Dietary_Ictoria_Attachment_Queries' ) ) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/attachment.php';
		}

		//get arrays
		Allergens_Dietary_Ictoria_Activator::initialize();

		$allergens_result = Allergens_Dietary_Ictoria_Activator::allergens_options();
		$icon_allergy_result = Allergens_Dietary_Ictoria_Activator::allergy_icon_options();
		$icon_result = Allergens_Dietary_Ictoria_Activator::icon_options();

		global $wpdb;

		//get database table
		$table_allergens = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
				
		$sql = $wpdb->prepare(
			"SELECT * FROM $table_allergens WHERE allergy_name = 'alcohol'"
		);

		$exists = $wpdb->get_var( $sql );

		//checks if database record of the standard allergies already exists
		if ($exists == 0){

	foreach($allergens_result as $key => $value){
		$sql = $wpdb->prepare(
			"SELECT * FROM $table_allergens WHERE allergy_name = '" . $value['title'] . "'"
		);
			$exists = $wpdb->get_var( $sql );
		if ($exists == 0){
			$isallergy = 0;

		if ($value['category'] == "allergen"){
				$isallergy = 1;
			}else{
				$isallergy = 0;
		}
		//insert allergies
		$wpdb->insert(
			$table_allergens,
			array(
				'allergy_name'  => $value['title'],
				'allergy_description'    => $value['description'],
				'is_allergy' => 	$isallergy,
			)
			); 
			}
		}
	}

	//activate other inserters
	Allergens_Dietary_Ictoria_Attachment_Queries::attachment_insert( $icon_allergy_result );
	Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::allergy_connection( $icon_result );
}
}