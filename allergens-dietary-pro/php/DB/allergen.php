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

	public function getAllAllergens()
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$sql = "SELECT allergy_name, is_allergy 
		FROM $table_name
		WHERE is_active = 1
		ORDER BY  is_allergy DESC, allergy_name ASC";

		$result = $wpdb->get_results($sql, ARRAY_A);

		return $result;
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

	public function delete_allergen_by_name(string $allergy_name, int $return_page = null, string $message)
	{
		try {
			global $wpdb;

			$table_allergy_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';
			$table_allergy = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
			$table_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';
			$url = strtok($_SERVER["REQUEST_URI"], '?');

			if (empty($allergy_name)) {
				return;
			}

			$is_default = self::is_default_allergen($allergy_name);

			static $error_displayed = false;

			if ($is_default) {
				if (!$error_displayed) {
					throw new Exception(__("Can't delete a default allergen option: '" . $allergy_name . "'"));
				}
			}

			$existing_attachment = $wpdb->query(
				"SELECT attachment_name 
				 FROM $table_allergy_attachment 
				 GROUP BY attachment_name 
				 HAVING COUNT(attachment_name) > 1
				 LIMIT 1"
			);
			
			if ($existing_attachment) {
				$sql = $wpdb->prepare(
					"DELETE aa, a 
                 FROM $table_allergy_attachment AS aa
                 JOIN $table_allergy AS a 
                 ON a.allergy_name = aa.allergy_name
                 WHERE aa.allergy_name = %s  
                 AND a.is_default_option != TRUE",
					$allergy_name
				);
			} else {
				$sql = $wpdb->prepare(
					"DELETE aa, a, am 
					FROM $table_allergy_attachment AS aa
					JOIN $table_allergy AS a 
					ON a.allergy_name = aa.allergy_name
					JOIN $table_attachment as am
					ON am.attachment_name = aa.attachment_name
					WHERE aa.allergy_name = %s  
					AND a.is_default_option != TRUE",
					$allergy_name
				);
			}
			$result = $wpdb->query($sql);
			if ($result === false) {
				if (!$error_displayed) {
					throw new Exception(__("Error deleting allergen: '" . $allergy_name . "'"));
				}
			}
		} catch (Exception $e) {
			if (!$error_displayed) {
				echo "Error: " . $e->getMessage();
			}

		}
		if (!empty($_GET)) {
			$url = strtok($_SERVER["REQUEST_URI"], '?');
			$separator = strpos($url, '?') === false ? '?' : '&';
			header("Location: $url" . $separator . "page=allergens-dietary-show-allergens" . (isset($return_page) ? '&paged=' . $return_page : '') . "&messaged=" . urlencode($message));
		}

	}

	// public function singleActivationUpdate(int $return_page, string $message){
	// 	parent::singleActivationUpdate($return_page, $message);
	// 	// $instance = self::getInstance();
	// 	// $instance->singleActivationUpdate();
	// }
}