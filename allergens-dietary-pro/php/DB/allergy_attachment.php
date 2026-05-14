<?php

if (!defined('ABSPATH')) {
	exit;
}

if(!class_exists('Allergens_Dietary_Allergy_Attachment_Queries')){
	require_once ALLERGENS_DIETARY_FREE_DIRNAME . '/php/DB/allergy_attachment.php';
}

class Allergens_Dietary_Pro_Allergy_Attachment_Queries extends Allergens_Dietary_Allergy_Attachment_Queries
{
	protected function __construct(){}

	public function addAllergyAttachment(array $data)
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_allergy_attachment';

		$wpdb->insert(
			$table_name,
			array(
				'allergy_name' => $data['allergen_name'],
				'attachment_name' => $data['allergen_icon']['name'],
			)
		);

		return (isset($wpdb->insert_id)) ? true : false;
	}

	public function updateAllergyAttachment(string $old_allergy_name, string $attachment)
	{

		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_allergy_attachment';

		return $wpdb->update(
			$table_name,
			array(
				'attachment_name' => $attachment,
			),
			array(
				'allergy_name' => $old_allergy_name,
			)
		);
	}

	public function attachmentIsUsed(string $attachment)
	{
		global $wpdb;

		$table = $wpdb->prefix . 'allergens_dietary_allergy_attachment';
		
		$count = $wpdb->get_var($wpdb->prepare(
			"SELECT COUNT(attachment_name) FROM $table
			WHERE attachment_name = %s",
			$attachment
		));

		return $count > 0;
	}

	public function checkAllergyAttachmentExists(string $allergy_name)
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_allergy_attachment';

		$sql = $wpdb->prepare(
			"SELECT attachment_name FROM $table_name WHERE allergy_name = %s",
			$allergy_name,
		);

		$result = $wpdb->get_results($sql);

		return (!empty($result)) ? true : false;
	}

	public function deleteAllergyAttachment(string $allergy)
	{
		global $wpdb;

		$table_aa = $wpdb->prefix . 'allergens_dietary_allergy_attachment';
		$table_am = $wpdb->prefix . 'allergens_dietary_attachments';
		$table_a = $wpdb->prefix . 'allergens_dietary_allergy';

		$sql = $wpdb->prepare(
			"DELETE aa.*, am.*, a.*
			FROM %i AS aa
			LEFT JOIN %i AS am
			ON am.attachment_name = aa.attachment_name
			LEFT JOIN %i AS a
			ON a.allergy_name = aa.allergy_name
			WHERE aa.allergy_name = %s  
			",
			array(
				$table_aa, $table_am,
				$table_a, $allergy
			)
		);

		$wpdb->query($sql);
	}

	public function deleteAllergyAndConnection(string $allergy){
		global $wpdb;

		$table_aa = $wpdb->prefix . 'allergens_dietary_allergy_attachment';
		$table_a = $wpdb->prefix . 'allergens_dietary_allergy';

		$sql = $wpdb->prepare(
			"DELETE aa, a 
			FROM $table_aa AS aa
			JOIN $table_a AS a 
			ON a.allergy_name = aa.allergy_name
			WHERE aa.allergy_name = %s  
			AND a.is_default_option != 1",
			$allergy
		);

		$wpdb->query($sql);
	}

	// public function getallergyAttachment(string $attachment, bool $isForm = true){
	// 	parent::getallergyAttachment($attachment, $isForm);
	// }

	public function checkMultipleAttachmentsExists(string $attachment): bool
	{
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_allergy_attachment';

		$sql = $wpdb->prepare(
			"SELECT COUNT(attachment_name) FROM $table_name
			WHERE attachment_name = %s",
			$attachment
		);

		$count = $wpdb->get_var($sql);

		return $count > 1;
	}
}
