<?php

if (!defined('ABSPATH')) {
	exit;
}

if(!class_exists('Allergens_Dietary_Allergen_Queries')){
	require_once ALLERGENS_DIETARY_FREE_DIRNAME . '/php/DB/allergen.php';
}

/**
 * @class Allergens_Dietary_Pro_Allergen_Queries
 * @brief This class is a singleton that handles all the queries for the allergens and dietary restrictions DB table.
 * @author Ictoria
 * @date 11-9-2024
 * @since 1.0.0
 */
class Allergens_Dietary_Pro_Allergen_Queries extends Allergens_Dietary_Allergen_Queries
{
	protected function __construct()
	{
	}

	/**
	 * @brief This method adds an allergen to the DB.
	 * @param array $data
	 * @return bool
	 * @since 1.0.0
	 * @date 11-9-2024
	 * @author Ictoria
	 */
	public function addAllergens(array $data)
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$wpdb->insert(
			$table_name,
			array(
				'allergy_name' => $data['allergen_name'],
				'allergy_description' => $data['allergen_description'],
				'is_allergy' => $data['type'],
			),
			array(
				'%s',
				'%s',
				'%d',
			)
		);

		return (isset($wpdb->insert_id)) ? true : false;
	}

	public function checkAllergenExists(string $allergenName)
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$sql = $wpdb->prepare(
			"SELECT allergy_name FROM $table_name WHERE allergy_name = %s",
			$allergenName
		);

		$result = $wpdb->get_results($sql);

		return (count($result) > 0) ? true : false;
	}

	public function updateAllergens(array $data)
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$wpdb->update(
			$table_name,
			array(
				'allergy_name' => $data['allergen_name'],
				'allergy_description' => $data['allergen_description'],
				'is_allergy' => $data['type'],
			),
			array(
				'allergy_name' => $data['allergen_name_hidden'],
			)
		);
	}

	public function getAllergen(string $allergenName)
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$sql = $wpdb->prepare(
			"SELECT * FROM $table_name WHERE allergy_name = %s",
			$allergenName
		);

		$result = $wpdb->get_results($sql);

		return $result;
	}

	// public function singleActivationUpdate(int $return_page, string $message){
	// 	parent::singleActivationUpdate($return_page, $message);
	// 	// $instance = self::getInstance();
	// 	// $instance->singleActivationUpdate();
	// }
}