<?php
if (!defined('ABSPATH')) {
    exit;
}

class Allergen_Icon_Manager
{
    private const MIME_TYPES = array('image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml', 'image/webp');

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
}
