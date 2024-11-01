<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Allergens_Dietary_Ictoria_Attachment_Queries {
	private static ?self $_instance = null;
	private const PATH = ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/assets/icons/custom/';
	private string $_url;

	public static function getInstance() {
		if ( self::$_instance === null ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		$this->_url = get_home_url() . '/wp-content/plugins/allergens-dietary-ictoria/assets/icons/custom/';
	}

	public function addAttachment( array $data ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

		$wpdb->insert(
			$table_name,
			array(
				'attachment_name' => $data['name'],
				'attachment_path' => $this->_url . $data['full_path'],
			)
		);

		$this->placeAttachment( $data );

		return ( isset( $wpdb->insert_id ) ) ? true : false;
	}

	public function deleteAttachment( string $attachment ) {
		global $wpdb;

		error_log("deleting");
		error_log($attachment);
		$table_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

		$sqlConnection = $wpdb->prepare(
			"DELETE FROM $table_attachment
     		WHERE attachment_name = %s",
    		$attachment
			);

		$wpdb->query($sqlConnection);
	}

	public function checkAttachmentExists( string $attachmentName ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

		$sql = $wpdb->prepare(
			"SELECT attachment_name FROM $table_name WHERE attachment_name = %s",
			$attachmentName
		);

		$result = $wpdb->get_row( $sql, ARRAY_A );

		return ( ! empty( $result ) ) ? true : false;
	}


	public function updateAttachment( array $data, string $oldName ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

		$wpdb->update(
			$table_name,
			array(
				'attachment_name' => $data['name'],
				'attachment_path' => $this->_url . $data['name'],
			),
			array(
				'attachment_name' => $oldName,
			)
		);

		$this->placeAttachment( $data );
	}

	/**
	 * @brief This method places an attachment in the plugin directories
	 * under assets/icons/custom
	 * @param array $data
	 * @since 1.0.0
	 * @date 11-9-2024
	 * @author V.B.
	 */
	private function placeAttachment( array $data ) {
		$upload_dir = wp_upload_dir();
		$upload_dir = $upload_dir['basedir'] . '/allergens-dietary-ictoria/icons/custom/';

		if ( false === file_exists( self::PATH ) ) {
			mkdir( self::PATH, 0777, true );
		}

		$full_path = self::PATH . $data['full_path'];
		// $full_path = $_SERVER['HTTP_HOST'] . '/wp-content/plugins/allergens-dietary-ictoria/assets/icons/custom/' . $data['full_path'];
		if ( false === file_exists( $full_path ) ) {
			move_uploaded_file( $data['tmp_name'], $full_path );
		}
	}

	public static function attachment_insert( array $result ){
		global $wpdb;

		//get database table
		$table_icons = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

			foreach($result as $value){
				//insert allergies
					$wpdb->insert(
						$table_icons,
					array(
						'attachment_path'  => $value['path'],
						'attachment_name'   => $value['name'],
					)
				); 
			}
	}
}


