<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Allergen_Icon_Manager {

    public static function get_allergens_with_icons() {
        global $wpdb;
        $table_allergy = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
        $table_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';
        $table_allergy_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';

        // First select allergies and attachments
        $allergy_query = "SELECT allergy_name FROM $table_allergy";
        $attachment_query = "SELECT attachment_name, attachment_path FROM $table_attachment";

        // Get allergies and attachments
        $allergies = $wpdb->get_results($allergy_query);
        $attachments = $wpdb->get_results($attachment_query);

        // link allergens with their attachments
        $query = "
            SELECT allergy.allergy_name, attachments.attachment_name, attachments.attachment_path
            FROM $table_allergy AS allergy
            LEFT JOIN $table_allergy_attachment AS attachment_rel ON allergy.allergy_name = attachment_rel.allergy_name
            LEFT JOIN $table_attachment AS attachments ON attachment_rel.attachment_name = attachments.attachment_name";
        
        return $wpdb->get_results($query);
    }

    public static function display_allergen_icon_form() {
        $allergens = self::get_allergens_with_icons();
        ?>
        <form method="post" enctype="multipart/form-data">
            <?php foreach ($allergens as $allergen): ?>
                <div>
                    <label for="allergen_<?php echo esc_attr($allergen->allergy_name); ?>">
                        <?php echo esc_html($allergen->allergy_name); ?>:
                    </label>
                    <?php if ($allergen->attachment_path): ?>
                        <img src="<?php echo esc_url($allergen->attachment_path . '?v=' . time()); ?>" alt="icon" width="50" height="50">
                    <?php endif; ?>
                    <input type="file" name="allergen_icon[<?php echo esc_attr($allergen->allergy_name); ?>]" />
                </div>
            <?php endforeach; ?>
            <input type="submit" name="submit_icons" value="<?php esc_attr_e('Update Icons', 'allergens-dietary-ictoria'); ?>">
        </form>
        <?php
    }

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
                                    'allergy_name' => sanitize_text_field($allergy_name),
                                    'attachment_name' => sanitize_file_name($attachment_name)
                                ),
                                array('%s', '%s')
                            );
                        }
    
                        $wpdb->query('COMMIT');
                        $updated_icons = true;
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
}
}