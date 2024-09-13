<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Allergens_Dietary_Ictoria_Allergy_Attachment_Queries {
	private static ?self $_instance = null;

	public static function getInstance() {
		if ( self::$_instance === null ) {
			self::$_instance = new self();
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

	public function getallergyAttachment( string $allergy_name ) {
		global $wpdb;

		$sql = $wpdb->prepare(
			"SELECT a.allergy_name, a. attachment_name
            FROM  {$wpdb->prefix}allergens_dietary_ictoria_allergy_attachment as aa
            JOIN {$wpdb->prefix}allergens_dietary_ictoria_allergy as a
            ON aa.allergy_name = a.allergy_name

            WHERE aa.allergy_name = %s",
			$allergy_name
		);

		return (array) $wpdb->get_results( $sql );
	}

	public function updateallergyAttachment( array $data ) {
		global $wpdb;
 
		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

		$wpdb->update(
			$table_name,
			array(
				'allergy_name'    => $data['allergen_name'],
				'attachment_name' => $data['allergen_icon']['name']
			),
			array(
				'allergy_name'    => $data['allergen_name'],
				'attachment_name' => $data['allergen_icon']['name']
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
}
