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
		/*load data*/
		  $result = Allergens_Dietary_Ictoria_Functions::default_options();
		
		/*test activation*/
			echo "<script>console.log('activated')</script>";

		/*test contains*/
			//echo $result;

		/*test further contains*/ 
			echo $result['nuts']['category'];

		/*inserts*/

		global $wpdb;

		$table_allergens = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
		$table_allergens_icons = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';
		$table_product = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_product';
		$table_product_icons = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

		$totalcount = count($result);

		$counter = 0;

		foreach($result as $key => $value){
			echo "Key:" . $key . ", Value: " . $value['category'];

			$counter++;

			/*insert product icons
			$wpdb->insert(
				$table_product_icons,
				array(
					$key => $value['icon'],
					$key => $value['icon'],
					$key => $value['icon'],
					$key => $value['icon'],
				)
			); */

		//insert attachment
		$wpdb->insert(
			$table_allergens_icons,
			array(
				'attachment_name' 		=> $value['title'],
			)
		); 
/*
		//insert allergies
		$wpdb->insert(
			$table_allergens,
			array(
				'allergy_name'    => $value['title'],
				'allergy_description'    => $value['status'],
			)
		); 

		*/


		if ($counter >= $totalcount || $counter >= 50){
			break; //exit loop
		}

	}
}
}
