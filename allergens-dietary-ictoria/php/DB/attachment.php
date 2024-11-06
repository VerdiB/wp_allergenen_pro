<?php

if (!defined('ABSPATH')) {
	exit;
}

class Allergens_Dietary_Ictoria_Attachment_Queries
{
	private static ?self $_instance = null;
	private const PATH = ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/assets/icons/custom/';
	private string $_url;

	public static function getInstance()
	{
		if (self::$_instance === null) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct()
	{
		$this->_url = get_home_url() . '/wp-content/plugins/allergens-dietary-ictoria/assets/icons/custom/';
	}

	public function addAttachment(array $data)
	{
		if (is_null($data)) {
			return;
		}

		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

		$wpdb->insert(
			$table_name,
			array(
				'attachment_name' => $data['name'],
				'attachment_path' => $this->_url . $data['name'],
			)
		);

		$this->placeAttachment($data);

		return (isset($wpdb->insert_id)) ? true : false;
	}

	public function checkAttachmentExists(string $attachmentName)
	{
		if (empty($attachmentName)) {
			return;
		}

		global $wpdb;

		$table_name = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

		$sql = $wpdb->prepare(
			"SELECT attachment_name FROM $table_name WHERE attachment_name = %s",
			$attachmentName
		);

		$result = $wpdb->get_row($sql, ARRAY_A);

		return (!empty($result)) ? true : false;
	}


	public function updateAttachment(array $data, string $oldName)
	{
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

		$this->placeAttachment($data);
	}

	public function update_allergen_icons(array $data, array $MIME_TYPES)
	{
		if (!isset($data['name']) || !isset($data['allergen_icon_hidden'])) {
			return;
		}

		$updated_icons = false;
		$allergen_icon_hidden = $data['allergen_icon_hidden'];

		foreach ($data['allergen_icon']['name'] as $allergy_name => $file_name) {
			if (
				!array_key_exists($allergy_name, $allergen_icon_hidden) ||
				$data['allergen_icon']['error'][$allergy_name] !== UPLOAD_ERR_OK
			) {
				continue;
			}

			$file_type = wp_check_filetype($data['allergen_icon']['name'][$allergy_name]);
			if (!in_array($file_type['type'], $MIME_TYPES)) {
				echo '<p>' . __('The new file for: ' . $allergy_name . ' is not a valid image.', 'allergens-dietary-ictoria') . '</p>';
				continue;
			}

			$allergen_prev_attachment_name = sanitize_file_name($allergen_icon_hidden[$allergy_name]);
			$tmp_name = $_FILES['allergen_icon']['tmp_name'][$allergy_name];
			$attachment_path = get_home_url() . '/wp-content/plugins/allergens-dietary-ictoria/assets/icons/custom/' . $file_name;
			$attachment_name = basename($file_name);
			$sanitized_allergy_name = sanitize_text_field($allergy_name);

			global $wpdb;
			$wpdb->query('START TRANSACTION');

			$table_name_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';
			$table_name_allergy_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

			try {
				if (!self::checkAttachmentExists($attachment_name)) {
					$wpdb->query($wpdb->prepare(
						"INSERT INTO $table_name_attachment
							VALUES (%s, %s)",
						$attachment_name,
						$attachment_path
					));
				}

				$wpdb->query($wpdb->prepare(
					"UPDATE $table_name_allergy_attachment
							SET attachment_name = %s
							WHERE allergy_name = %s",
					$attachment_name,
					$sanitized_allergy_name
				));
				foreach ($data['allergen_icon'] as $icon) {
					self::placeAttachment($icon);
				}

				$wpdb->query('COMMIT'); // Both operations succeeded
				$updated_icons = true;

			} catch (Exception $e) {
				echo 'Error: ' . $e->getMessage();
				$updated_icons = false;
				$wpdb->query('ROLLBACK'); // Something went wrong, rollback all changes
			}
		}

		echo $updated_icons
			? '<p>' . __('Icons updated successfully.', 'allergens-dietary-ictoria') . '</p>'
			: '<p>' . __('No icons were updated.', 'allergens-dietary-ictoria') . '</p>';
	}

	/**
	 * @brief This method places an attachment in the plugin directories
	 * under assets/icons/custom
	 * @param array $data
	 * @since 1.0.0
	 * @date 11-9-2024
	 * @author V.B.
	 */
	private function placeAttachment(array $data)
	{

		if (false === file_exists(self::PATH)) {
			mkdir(self::PATH, 0777, true);
		}

		$full_path = self::PATH . $data['name'];
		// $full_path = $_SERVER['HTTP_HOST'] . '/wp-content/plugins/allergens-dietary-ictoria/assets/icons/custom/' . $data['full_path'];
		if (false === file_exists($full_path)) {
			move_uploaded_file($data['tmp_name'], $full_path);
		}
	}

	public static function attachment_insert(array $result)
	{
		global $wpdb;

		//get database table
		$table_icons = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';


		foreach ($result as $key => $value) {

			//insert allergies
			$wpdb->insert(
				$table_icons,
				array(
					'attachment_path' => $value['path'],
					'attachment_name' => $value['name'],
				)
			);
		}
	}
}
