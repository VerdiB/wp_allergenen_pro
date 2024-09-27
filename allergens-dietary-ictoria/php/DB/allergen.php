<?php

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
				'is_allergy'          => $data['type'],
			),
			array(
				'%s',
				'%s',
				'%d',
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
				'is_allergy'          => $data['type'],
			),
			array(
				'allergy_name' => $data['allergen_name_hidden'],
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
		$result1 = Allergens_Dietary_Ictoria_Activator::getOptions1();
		$result2 = Allergens_Dietary_Ictoria_Activator::getOptions2();
		$result3 = Allergens_Dietary_Ictoria_Activator::getOptions3();


		global $wpdb;

		$table_allergens = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
		$table_allergens_icons = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';
		$table_product = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_product';
		$table_product_icons = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

		$totalcount = count($result1);

		$counter = 0;
				
		$sql = $wpdb->prepare(
			"SELECT * FROM $table_allergens WHERE allergy_name = 'alcohol'"
		);

		$exists = $wpdb->get_var( $sql );

		if ($exists == 0){

	foreach($result1 as $key => $value){

			$counter++;

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


		if ($counter >= $totalcount || $counter >= 50){
			break; //exit loop
		}
	
			
			}
	}
	foreach($result2 as $key => $value2){
		$counter++;

		$wpdb->insert(
			$table_product_icons,
			array(
				'attachment_path'  => $value2['path'],
				'attachment_name'   => $value2['name'],
			)
		); 
	
	}
	foreach($result3 as $key => $value3){
		$counter++;

		$wpdb->insert(
		$table_allergens_icons,
		array(
			'attachment_name'   => $value3['name'],
			'allergy_name'  => $value3['title'],
			)
			); 
		
	}
}
}
	
}