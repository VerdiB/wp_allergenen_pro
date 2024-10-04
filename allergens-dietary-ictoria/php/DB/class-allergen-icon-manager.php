<?php
if (!defined('ABSPATH')) {
    exit;
}

class Allergen_Icon_Manager
{

    public static function update_allergen_icons()
    {
        $updated_icons = false;

        if (isset($_FILES['allergen_icon'])) {
            if (isset($_POST['allergen_icon_hidden'])) {
                $allergen_icon_hidden = $_POST['allergen_icon_hidden'];

                foreach ($_FILES['allergen_icon']['name'] as $allergy_name => $file_name) {
                    if (array_key_exists($allergy_name, $allergen_icon_hidden)) {
                        $allergen_prev_attachment_name = $allergen_icon_hidden[$allergy_name];

                        if ($_FILES['allergen_icon']['error'][$allergy_name] === UPLOAD_ERR_OK) {

                            $tmp_name = $_FILES['allergen_icon']['tmp_name'][$allergy_name];
                            $upload_dir = wp_upload_dir();
                            $attachment_path = $upload_dir['path'] . '/' . basename($file_name);
                            $attachment_name = basename($file_name);
                            $sanitized_allergy_name = sanitize_text_field($allergy_name);
                            $sanitized_prev_attachment_name = sanitize_file_name($allergen_prev_attachment_name);
                            $sanitized_attachment_path = esc_url($attachment_path);

                            if (move_uploaded_file($tmp_name, $sanitized_attachment_path)) {
                                global $wpdb;
                                $wpdb->query('START TRANSACTION');

                                $table_name_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';
                                $table_name_allergy_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

                                $existing_attachment = $wpdb->get_var($wpdb->prepare(
                                    "SELECT attachment_name FROM $table_name_attachment WHERE attachment_name = %s",
                                    $sanitized_prev_attachment_name
                                ));

                                if ($existing_attachment) {
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

                                    $wpdb->query($wpdb->prepare(
                                        "UPDATE $table_name_allergy_attachment
                                        SET attachment_name = %s
                                        WHERE allergy_name = %s",
                                        $attachment_name,
                                        $sanitized_allergy_name
                                    ));
                                }

                                $wpdb->query('COMMIT');
                                $updated_icons = true;
                            }
                        }
                    }
                }
            }

            if ($updated_icons) {
                echo '<script type="text/javascript">location.reload();</script>';
                echo '<p>' . __('Icons updated successfully. Reloading page...', 'allergens-dietary-ictoria') . '</p>';
            } else {
                echo '<p>' . __('No icons were updated.', 'allergens-dietary-ictoria') . '</p>';
            }
        }
    }
}
