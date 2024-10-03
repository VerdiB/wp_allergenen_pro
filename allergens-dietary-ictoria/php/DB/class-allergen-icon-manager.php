<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Allergen_Icon_Manager {

    public static function update_allergen_icons() {
        $updated_icons = false;
    
        if (isset($_FILES['allergen_icon'])) {
            foreach ($_FILES['allergen_icon']['name'] as $allergy_name => $file_name) {

                if ($_FILES['allergen_icon']['error'][$allergy_name] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['allergen_icon']['tmp_name'][$allergy_name];
                    $upload_dir = wp_upload_dir();
                    $attachment_path = $upload_dir['path'] . '/' . basename($file_name);
                    $attachment_name = basename($file_name);
    
                    if (move_uploaded_file($tmp_name, $attachment_path)) {
                        global $wpdb;
                        $wpdb->query('START TRANSACTION');
    
                        $table_name_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';
                        $table_name_allergy_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';
    
                        // Check if attachment_name already exists
                        $existing_attachment = $wpdb->get_var($wpdb->prepare(
                            "SELECT attachment_name FROM $table_name_attachment WHERE attachment_name = %s",
                            sanitize_file_name($attachment_name)
                        ));
    
                        if (!$existing_attachment) {
                            // Insert into the attachments table
                            $wpdb->insert(
                                $table_name_attachment,
                                array(
                                    'attachment_name' => sanitize_file_name($attachment_name),
                                    'attachment_path' => sanitize_text_field($attachment_path)
                                ),
                                array('%s', '%s')
                            );
                        } else {
                            // Update the attachment path if it already exists
                            $wpdb->update(
                                $table_name_attachment,
                                array('attachment_path' => sanitize_text_field($attachment_path)),
                                array('attachment_name' => sanitize_file_name($attachment_name)),
                                array('%s', '%s')

                            );
                        }
    
                        // check the allergy-attachment relation
                        $existing_allergy_attachment = $wpdb->get_var($wpdb->prepare(
                            "SELECT attachment_name FROM $table_name_allergy_attachment WHERE allergy_name = %s",
                            sanitize_text_field($allergy_name)
                        ));
    
                        // Update or insert the allergy attachment relation
                        if ($existing_allergy_attachment) {
                            // Update existing attachment_name
                            $wpdb->update(
                                $table_name_allergy_attachment,
                                array('attachment_name' => sanitize_file_name($attachment_name)),
                                array('allergy_name' => sanitize_text_field($allergy_name)),
                                array('%s'),
                                array('%s')
                            );
                        } else {
                            // Insert new allergy attachment
                            $wpdb->insert(
                                $table_name_allergy_attachment,
                                array(
                                    'attachment_name' => sanitize_file_name($attachment_name),
                                    'allergy_name' => sanitize_text_field($allergy_name)
                                ),
                                array('%s', '%s')
                            );
                        }
    
                        $wpdb->query('COMMIT');
                        $updated_icons = true;
            }
    
            if ($updated_icons) {
                // echo '<script type="text/javascript">location.reload();</script>';
                echo '<p>' . __('Icons updated successfully. Reloading page...', 'allergens-dietary-ictoria') . '</p>';
            } else {
                echo '<p>' . __('No icons were updated.', 'allergens-dietary-ictoria') . '</p>';
            }
        }
    }    
}
}
}