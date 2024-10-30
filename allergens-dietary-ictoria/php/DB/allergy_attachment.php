<?php

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
			error_log("this thing is not true");
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

		return $wpdb->get_row( $sql, ARRAY_A );
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

		error_log("updating1");

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

		$wpdb->update(
			$table_name,
			array(
				'allergy_name'    => $data['allergen_name'],
				'attachment_name' => $data['allergen_icon']['name'],
			),
			array(
				'allergy_name'    => $data['allergen_name_hidden'],
			)
		);

		return ( isset( $wpdb->insert_id ) ) ? true : false;
	}

	public function checkAllergyAttachmentExists( string $allergy_name ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

		$sql = $wpdb->prepare(
			"SELECT * FROM $table_name WHERE allergy_name = %s",
			$allergy_name,
		);

		$result = $wpdb->get_results( $sql );

		return ( ! empty( $result ) ) ? true : false;
	}

	public function deleteAllergyAttachment( string $allergy ) {
		global $wpdb;

		$table_aa = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';
		$table_am = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';
		$table_a = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$sql = $wpdb->prepare(
			"DELETE a, aa, am
			FROM $table_a AS a
			JOIN $table_am AS am
			ON
			WHERE allergy_name = %s",
			$allergy,
		);

		$wpdb->query($sql);
	}

	public function checkMultipleAttachmentsExists( string $icon ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

		$sql = $wpdb->prepare(
			"SELECT attachment_name FROM $table_name 
			WHERE attachment_name = %s HAVING COUNT(attachment_name)
			LIMIT 2",
			$icon,
		);

		$exists = $wpdb->get_var($sql);
		
		// Example usage
		$example_var = array("test" => 123, "foo" => "bar");
		$result = false;

		if ($exists > 2){
			error_log("this thing is super true");
			$result = true;
		}else{
			error_log("this thing is super false");
			$result = false;
		}

		return $result;
	}

	public function find_allergy( string $allergy_name ){
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

		$sql = $wpdb->prepare(
			"SELECT attachment_name, allergy_name FROM $table_name WHERE allergy_name = %s",
			$allergy_name
		);

		$checkresult = $wpdb->get_results( $sql );

		$found_allergy = false;

		(!empty( $checkresult ) ) ?
		$found_allergy = true :
		$found_allergy = false;

		if ($found_allergy == true){

			error_log("yesssssssss");
			$result = $wpdb->get_row($sql);

			print_r("hello5000");
			var_dump($wpdb->get_row( $sql ));
			print_r("hello5000");
			print_r($result->attachment_name);
			print_r("hello5000");

			return $result->attachment_name;
		}
	}

	public static function allergy_connection( array $result){

		global $wpdb;

		//get database table
		$table_allergens_icons = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

			//insert allergies
			foreach($result as $value){
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
	