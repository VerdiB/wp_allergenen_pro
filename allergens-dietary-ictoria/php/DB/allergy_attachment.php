<?php

if (!defined('ABSPATH')) {
	exit;
}

class Allergens_Dietary_Ictoria_Allergy_Attachment_Queries
{
	private static ?self $_instance = null;

	public static function getInstance()
	{
		if (self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct()
	{
	}

	public function addallergyAttachment(array $data)
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

		$wpdb->insert(
			$table_name,
			array(
				'allergy_name' => $data['allergen_name'],
				'attachment_name' => $data['allergen_icon']['name'],
			)
		);

		return (isset($wpdb->insert_id)) ? true : false;
	}

	public function getallergyAttachment(string $allergy_name)
	{
		global $wpdb;

		$sql = $wpdb->prepare(
			"SELECT a.allergy_name, a.allergy_description, a.is_allergy, aa.attachment_name
            FROM  {$wpdb->prefix}allergens_dietary_ictoria_allergy_attachment as aa
            JOIN {$wpdb->prefix}allergens_dietary_ictoria_allergy as a
            ON aa.allergy_name = a.allergy_name

            WHERE aa.allergy_name = %s",
			$allergy_name
		);

		return $wpdb->get_row($sql, ARRAY_A);
	}

	public function updateallergyAttachment(array $data)
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

		$wpdb->update(
			$table_name,
			array(
				'allergy_name' => $data['allergen_name'],
				'attachment_name' => $data['allergen_icon']['name'],
			),
			array(
				'allergy_name' => $data['allergen_name'],
				'attachment_name' => $data['allergen_icon']['name'],
			)
		);

		return (isset($wpdb->insert_id)) ? true : false;
	}

	public function getAll_allergensAndAttachments()
	{
		global $wpdb;
		$table_name_allergy_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';
		$table_name_allergy = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
		$table_name_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

		$sql =
			"SELECT a.allergy_name, a.allergy_description, a.is_default_option, am.attachment_name, am.attachment_path
			FROM {$table_name_allergy_attachment} as aa
			JOIN {$table_name_allergy} as a
			ON aa.allergy_name = a.allergy_name 
			JOIN {$table_name_attachment} as am
			ON aa.attachment_name = am.attachment_name"
		;

		$result = $wpdb->get_results($sql);

		return $result;
	}

	public function checkAllergyAttachmentExists(string $allergy_name)
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

		$sql = $wpdb->prepare(
			"SELECT * FROM $table_name WHERE allergy_name = %s",
			$allergy_name
		);

		$result = $wpdb->get_results($sql);

		return (!empty($result)) ? true : false;
	}

	public static function allergy_connection( array $result ){
		global $wpdb;

		//get database table
		$table_allergens_icons = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

			
		foreach($result as $key => $value){
			
			//insert allergies
				$wpdb->insert(
				$table_allergens_icons,
				array(
					'attachment_name'   => $value['name'],
					'allergy_name'  => $value['title'],
					)
					);
			}
		}
	}