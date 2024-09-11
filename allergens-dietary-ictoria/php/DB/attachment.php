<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Allergens_Dietary_Ictoria_Attachment_Queries {
	private static ?self $_instance = null;

	public static function getInstance() {
		if ( self::$_instance === null ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {}

	public function addAttachment( array $data ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

		$wpdb->insert(
			$table_name,
			array(
				'attachment_name' => $data['attachment_name'],
				'attachment_path' => $data['attachment_path'],
			)
		);

		$this->placeAttachment( $data );

		return ( isset( $wpdb->insert_id ) ) ? true : false;
	}

	public function checkAttachmentExists( string $attachmentName ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

		$sql = $wpdb->prepare(
			"SELECT attachment_name FROM $table_name WHERE attachment_name = %s",
			$attachmentName
		);

		$result = $wpdb->get_results( $sql );

		return ( ! empty( $result ) ) ? true : false;
	}


	public function updateAttachment( array $data ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

		$wpdb->update(
			$table_name,
			array(
				'attachment_name' => $data['attachment_name'],
				'attachment_path' => $data['attachment_path'],
			),
			array(
				'attachment_name' => $data['attachment_name'],
			)
		);
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
		$upload_dir = $upload_dir['basedir'] . '/allergens-dietary-ictoria/assets/icons/custom/';
		$file       = $upload_dir . basename( $data['attachment_path'] );

		if ( ! file_exists( $upload_dir ) ) {
			mkdir( $upload_dir, 0777, true );
		}

		if ( ! move_uploaded_file( $data['attachment_path'], $file ) ) {
			throw new Exception( __( 'The file could not be moved' ) );
		}
	}
}
