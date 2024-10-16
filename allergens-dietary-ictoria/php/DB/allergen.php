<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * @class Allergens_Dietary_Ictoria_Allergen_Queries
 * @brief This class is a singleton that handles all the queries for the allergens and dietary restrictions DB table.
 * @author V.B.
 * @date 11-9-2024
 * @since 1.0.0
 */
class Allergens_Dietary_Ictoria_Allergen_Queries {
	private static ?self $_instance = null;

	/**
	 * @brief This method returns the instance of the class.
	 * @return Allergens_Dietary_Ictoria_Allergen_Queries
	 * @author V.B.
	 * @since 1.0.0
	 * @date 11-9-2024
	 */
	public static function getInstance() {
		if ( self::$_instance === null ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {}

	/**
	 * @brief This method adds an allergen to the DB.
	 * @param array $data
	 * @return bool
	 * @since 1.0.0
	 * @date 11-9-2024
	 * @author V.B.
	 */
	public function addAllergens( array $data ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$wpdb->insert(
			$table_name,
			array(
				'allergy_name'        => $data['allergen_name'],
				'allergy_description' => $data['allergen_description'],
				'is_allergy'          => $data['type'],
			),
			array(
				'%s',
				'%s',
				'%d',
			)
		);
		return ( isset( $wpdb->insert_id ) ) ? true : false;
	}

	public function checkAllergenExists( string $allergenName ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$sql = $wpdb->prepare(
			"SELECT * FROM $table_name WHERE allergy_name = %s",
			$allergenName
		);

		$result = $wpdb->get_results( $sql );

		return ( count( $result ) > 0 ) ? true : false;
	}

	public function updateAllergens( array $data ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$wpdb->update(
			$table_name,
			array(
				'allergy_name'        => $data['allergen_name'],
				'allergy_description' => $data['allergen_description'],
				'is_allergy'          => $data['type'],
			),
			array(
				'allergy_name' => $data['allergen_name_hidden'],
			)
		);
	}

	public function getAllergen( string $allergenName ) {
		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';

		$sql = $wpdb->prepare(
			"SELECT * FROM $table_name WHERE allergy_name = %s",
			$allergenName
		);

		$result = $wpdb->get_results( $sql );

		return $result;
	}

	public static function update_allergen_icons()
    {
        $updated_icons = false;

        if (!isset($_FILES['allergen_icon'], $_POST['allergen_icon_hidden'])) {
            return;
        }

        $allergen_icon_hidden = $_POST['allergen_icon_hidden'];

        foreach ($_FILES['allergen_icon']['name'] as $allergy_name => $file_name) {
            if (!array_key_exists($allergy_name, $allergen_icon_hidden) || 
                $_FILES['allergen_icon']['error'][$allergy_name] !== UPLOAD_ERR_OK) {
                continue;
            }

            $file_type = wp_check_filetype($_FILES['allergen_icon']['name'][$allergy_name]);
            if (!in_array($file_type['type'], self::MIME_TYPES)) {
                echo '<p>' . __('The file is not a valid image.', 'allergens-dietary-ictoria') . '</p>';
                continue;
            }

            $allergen_prev_attachment_name = $allergen_icon_hidden[$allergy_name];
            $tmp_name = $_FILES['allergen_icon']['tmp_name'][$allergy_name];
            $upload_dir = wp_upload_dir();
            $attachment_path = $upload_dir['path'] . '/' . basename($file_name);
            $attachment_name = basename($file_name);
            $sanitized_allergy_name = sanitize_text_field($allergy_name);
            $sanitized_prev_attachment_name = sanitize_file_name($allergen_prev_attachment_name);
            $sanitized_attachment_path = esc_url($attachment_path);

            if (!move_uploaded_file($tmp_name, $sanitized_attachment_path)) {
                continue;
            }

            global $wpdb;
            $wpdb->query('START TRANSACTION');

            $table_name_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';
            $table_name_allergy_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

            $attachment_count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(attachment_name) FROM $table_name_allergy_attachment WHERE attachment_name = %s LIMIT 1",
                $sanitized_prev_attachment_name
            ));

            if ($attachment_count > 0) {
                $wpdb->query($wpdb->prepare(
                    "UPDATE $table_name_attachment
                    SET attachment_name = %s, 
                        attachment_path = %s
                    WHERE attachment_name = %s",
                    $attachment_name,
                    $sanitized_attachment_path,
                    $sanitized_prev_attachment_name 
                ));
            } else {
                $wpdb->query($wpdb->prepare(
                    "INSERT INTO $table_name_attachment (attachment_name, attachment_path)
                    VALUES (%s, %s)",
                    $attachment_name,
                    $sanitized_attachment_path
                ));
            }

            $wpdb->query($wpdb->prepare(
                "UPDATE $table_name_allergy_attachment
                SET attachment_name = %s
                WHERE allergy_name = %s",
                $attachment_name,
                $sanitized_allergy_name
            ));

            $wpdb->query('COMMIT');
            $updated_icons = true;
        }

        echo $updated_icons 
            ? '<script type="text/javascript">location.reload();</script><p>' . __('Icons updated successfully. Reloading page...', 'allergens-dietary-ictoria') . '</p>'
            : '<p>' . __('No icons were updated.', 'allergens-dietary-ictoria') . '</p>';
    }

	public static function includeItems(){
		if ( ! class_exists( 'Allergens_Dietary_Ictoria_Allergy_Attachment_Queries' ) ) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/allergy_attachment.php';
		}
		if ( ! class_exists( 'Allergens_Dietary_Ictoria_Attachment_Queries' ) ) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/DB/attachment.php';
		}

		//get arrays
		Allergens_Dietary_Ictoria_Activator::initialize();

		$allergens_result = Allergens_Dietary_Ictoria_Activator::allergens_options();
		$icon_allergy_result = Allergens_Dietary_Ictoria_Activator::allergy_icon_options();
		$icon_result = Allergens_Dietary_Ictoria_Activator::icon_options();

		global $wpdb;

		//get database table
		$table_allergens = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
				
		$sql = $wpdb->prepare(
			"SELECT * FROM $table_allergens WHERE allergy_name = 'alcohol'"
		);

		$exists = $wpdb->get_var( $sql );

		//checks if database record of the standard allergies already exists
		if ($exists == 0){

	foreach($allergens_result as $key => $value){
		$sql = $wpdb->prepare(
			"SELECT * FROM $table_allergens WHERE allergy_name = '" . $value['title'] . "'"
		);
			$exists = $wpdb->get_var( $sql );
		if ($exists == 0){
			$isallergy = 0;

		if ($value['category'] == "allergen"){
				$isallergy = 1;
			}else{
				$isallergy = 0;
		}
		//insert allergies
		$wpdb->insert(
			$table_allergens,
			array(
				'allergy_name'  => $value['title'],
				'allergy_description'    => $value['description'],
				'is_allergy' => 	$isallergy,
			)
			); 
			}
		}
	}

	//activate other inserters
	Allergens_Dietary_Ictoria_Attachment_Queries::attachment_insert( $icon_allergy_result );
	Allergens_Dietary_Ictoria_Allergy_Attachment_Queries::allergy_connection( $icon_result );
}
}