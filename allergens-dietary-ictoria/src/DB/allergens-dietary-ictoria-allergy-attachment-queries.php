<?php

namespace Db;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Allergens_Dietary_Ictoria_Allergy_Attachment_Queries {
	private static ?self $_instance = null;

	public static function getInstance() {
		if ( self::$_instance === null ) {
			self::$_instance = new static();
		}
		return self::$_instance;
	}

	private function __construct() {}

	public function addallergyAttachment( array $data ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

		$wpdb->insert(
			$table_name,
			array(
				'allergy_name'    => $data['allergen_name'],
				'attachment_name' => $data['allergen_icon']['name'],
			)
		);

		return ( isset( $wpdb->insert_id ) ) ? true : false;
	}

	public function getallergyAttachment( string $allergy_name, bool $isForm=true ) {
		global $wpdb;

		$sql = "";

		if ( $isForm ) {
			$sql = $wpdb->prepare(
				"SELECT a.allergy_name, a.allergy_description, a.is_allergy, aa.attachment_name
				FROM  {$wpdb->prefix}allergens_dietary_ictoria_allergy_attachment as aa
				JOIN {$wpdb->prefix}allergens_dietary_ictoria_allergy as a
				ON aa.allergy_name = a.allergy_name
				WHERE aa.allergy_name = %s",
				$allergy_name
			);
		} else{
			$sql = $wpdb->prepare(
			"SELECT a.allergy_name, a.allergy_description, att.attachment_path
			FROM  {$wpdb->prefix}allergens_dietary_ictoria_allergy_attachment as aa
			JOIN {$wpdb->prefix}allergens_dietary_ictoria_allergy as a
			ON aa.allergy_name = a.allergy_name
			JOIN {$wpdb->prefix}allergens_dietary_ictoria_attachments as att
			ON aa.attachment_name = att.attachment_name
			WHERE aa.allergy_name = %s",
			$allergy_name
		);
		}

		return $wpdb->get_results( $sql );
	}

	public function getAllAllergyAttachmments() {
		global $wpdb;
		$table = "{$wpdb->prefix}allergens_dietary_ictoria_allergy_attachment";
		
		$sql = $wpdb->prepare(
			"SELECT al.allergy_name, al.allergy_description, al.is_allergy,
			att.attachment_name, att.attachment_path
            FROM %i as aa
            JOIN {$wpdb->prefix}allergens_dietary_ictoria_allergy as al
            ON aa.allergy_name = al.allergy_name
			JOIN {$wpdb->prefix}allergens_dietary_ictoria_attachments as att
			ON aa.attachment_name = att.attachment_name
			ORDER BY  al.is_allergy DESC, al.allergy_name ASC
			",$table

		);

		return $wpdb->get_results( $sql, ARRAY_A );
	}

	public function getAllAllergyAttachmments() {
		global $wpdb;
		$table = "{$wpdb->prefix}allergens_dietary_ictoria_allergy_attachment";
		
		$sql = $wpdb->prepare(
			"SELECT al.allergy_name, al.allergy_description, al.is_allergy,
			att.attachment_name, att.attachment_path
            FROM %i as aa
            JOIN {$wpdb->prefix}allergens_dietary_ictoria_allergy as al
            ON aa.allergy_name = al.allergy_name
			JOIN {$wpdb->prefix}allergens_dietary_ictoria_attachments as att
			ON aa.attachment_name = att.attachment_name
			ORDER BY  al.is_allergy DESC, al.allergy_name ASC
			",$table

		);

		return $wpdb->get_results( $sql, ARRAY_A );
	}

	public function updateallergyAttachment( array $data ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

		$wpdb->update(
			$table_name,
			array(
				'allergy_name'    => $data['allergen_name'],
				'attachment_name' => $data['allergen_icon']['name'],
			),
			array(
				'allergy_name'    => $data['allergen_name'],
				'attachment_name' => $data['allergen_icon']['name'],
			)
		);

		return ( isset( $wpdb->insert_id ) ) ? true : false;
	}

	public function checkAllergyAttachmentExists( string $allergy_name ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

		$sql = $wpdb->prepare(
			"SELECT * FROM $table_name WHERE allergy_name = %s",
			$allergy_name
		);

		$result = $wpdb->get_results( $sql );

		return ( ! empty( $result ) ) ? true : false;
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