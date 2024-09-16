<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Allergens_Dietary_Ictoria_Activator {
	private static $counter = 0;

	public static function activate() {
		if ( self::$counter === 0 ) {
			self::create_tables();
			// self::add_fk_tables();
			++self::$counter;
		}
		if ( self::$counter > 0 ) {
			return;
		}
	}

	private static function create_tables() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$sql_attachments = $wpdb->query(
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}allergens_dietary_ictoria_attachments(
        attachment_name VARCHAR(255) NOT NULL PRIMARY KEY,
        attachment_path VARCHAR(255))"
		);

		$sql_allergy = $wpdb->query(
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}allergens_dietary_ictoria_allergy(
        allergy_name VARCHAR(50) NOT NULL PRIMARY KEY,
        allergy_description VARCHAR(255),
        is_allergy BOOLEAN NOT NULL DEFAULT 1)"
		);

		$sql_allergy_attachment = $wpdb->query(
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}allergens_dietary_ictoria_allergy_attachment(
        allergy_name VARCHAR(50) NOT NULL,
        attachment_name VARCHAR(255) NOT NULL,
        PRIMARY KEY (allergy_name, attachment_name),
        CONSTRAINT FK_AllergyAttch_Allergy
        FOREIGN KEY (allergy_name) REFERENCES {$wpdb->prefix}allergens_dietary_ictoria_allergy(allergy_name),
        CONSTRAINT FK_AllergyAttch_Attch
        FOREIGN KEY (attachment_name) REFERENCES {$wpdb->prefix}allergens_dietary_ictoria_attachments(attachment_name))
        "
		);

		$sql_allergy_product = $wpdb->query(
			"CREATE TABLE IF NOT EXISTS {$wpdb->prefix}allergens_dietary_ictoria_allergy_product(
        product_id BIGINT NOT NULL,
        allergy_name VARCHAR(50) NOT NULL,
        PRIMARY KEY (product_id, allergy_name),
        CONSTRAINT FK_AllergyProduct_WCproduct
        FOREIGN KEY (product_id) REFERENCES {$wpdb->prefix}wc_product_meta_lookup(product_id),
        CONSTRAINT FK_AllergyProduct_Allergy
        FOREIGN KEY (allergy_name) REFERENCES {$wpdb->prefix}allergens_dietary_ictoria_allergy(allergy_name))"
		);
		dbDelta( $sql_attachments );
		dbDelta( $sql_allergy );
		dbDelta( $sql_allergy_attachment );
		dbDelta( $sql_allergy_product );
	}
}
