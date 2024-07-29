<?php
class Allergens_Dietary_Ictoria_Activator{


    public static function activata(){
        self::create_tables();
    }

    private static function create_tables(){
        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sql_attachments = $wpdb->prepare("CREATE IF NOT EXISTS {$wpdb->prefix } allergens_dietary_ictoria_attachments(
        attachment_name VARCHAR(255) NOT NULL PRIMARY KEY,
        attachment_path VARCHAR(255)) DEFAULT CHARACTER SET DEFAULT CHARACTER SET %i COLLATE %i",
			$wpdb->charset,
			$wpdb->collate
        );

        $sql_allergy = $wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix } allergens_dietary_ictoria_allergy(
        allergy_name VARCHAR(50) NOT NULL PRIMARY KEY,
        allergy_description VARCHAR(255))CHARACTER SET DEFAULT CHARACTER SET %i COLLATE %i",
            $wpdb->charset,
            $wpdb->collate
        );

        $sql_allergy_attachment = $wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix } allergens_dietary_ictoria_allergy_attachment(
        allergy_name VARCHAR(50) NOT NULL,
        attachment_name VARCHAR(255) NOT NULL,
        PRIMARY KEY (allergy_name, attachment_name)) CHARACTER SET DEFAULT CHARACTER SET %i COLLATE %i",
            $wpdb->charset,
            $wpdb->collate
        );
        $sql_allergy_attachment_fk = $wpdb->prepare("ALTER TABLE IF EXISTS {$wpdb->prefix } allergens_dietary_ictoria_allergy_attachment(
        FOREIGN KEY (allergy_name) REFERENCES {$wpdb->prefix}allergens_dietary_ictoria_allergy(allergy_name),
        FOREIGN KEY (attachment_name) REFERENCES {$wpdb->prefix}allergens_dietary_ictoria_attachments(attachment_name))"
            );

        $sql_allergy_product = $wpdb->prepare("CREATE TABLE IF NOT EXISTS {$wpdb->prefix } allergens_dietary_ictoria_allergy_product(
        product_id INT NOT NULL,
        allergy_name VARCHAR(50) NOT NULL,
        PRIMARY KEY (product_id, allergy_name)) CHARACTER SET DEFAULT CHARACTER SET %i COLLATE %i",
            $wpdb->charset,
            $wpdb->collate
        );
        $sql_allergy_product_fk = $wpdb->prepare("ALTER TABLE IF EXISTS {$wpdb->prefix } allergens_dietary_ictoria_allergy_product(
        FOREIGN KEY (product_id) REFERENCES {$wpdb->prefix}_wc_product_meta_lookup(product_id),
        FOREIGN KEY (allergy_name) REFERENCES {$wpdb->prefix}allergens_dietary_ictoria_allergy(allergy_name))"
            );
            
        dbDelta( $sql_attachments );
        dbDelta( $sql_allergy );
        dbDelta( $sql_allergy_attachment );
        dbDelta( $sql_allergy_attachment_fk );
        dbDelta( $sql_allergy_product );
        dbDelta( $sql_allergy_product_fk );
    }
    
}

?>