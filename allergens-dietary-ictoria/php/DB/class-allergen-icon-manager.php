<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Allergen_Icon_Manager {

    public static function get_allergens_with_icons() {
        global $wpdb;
        $table_allergy = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
        $table_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';
        $table_attachments = $wpdb->prefix . 'allergens_dietary_ictoria_attachments';

        $query = "
            SELECT allergy.allergy_name, attachment.attachment_name, attachments.attachment_path
            FROM $table_allergy AS allergy
            LEFT JOIN $table_attachment AS attachment ON allergy.allergy_name = attachment.allergy_name
            LEFT JOIN $table_attachments AS attachments ON attachment.attachment_name = attachments.attachment_name";
        
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
            <input type="submit" name="submit_icons" value="Update Icons">
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
    
                        // Controleer of de attachment_name al bestaat
                        $existing_attachment = $wpdb->get_var($wpdb->prepare(
                            "SELECT attachment_name FROM $table_name_attachment WHERE attachment_name = %s",
                            sanitize_file_name($attachment_name)
                        ));
    
                        if (!$existing_attachment) {
                            // Voeg de nieuwe bijlage toe aan de `attachments`-tabel
                            $wpdb->insert(
                                $table_name_attachment,
                                array(
                                    'attachment_name' => sanitize_file_name($attachment_name),
                                    'attachment_path' => sanitize_text_field($attachment_path)
                                ),
                                array('%s', '%s')
                            );
    
                            // Verwijder de oude bijlage uit de `allergy_attachment`-tabel
                            $old_attachment_name = $wpdb->get_var($wpdb->prepare("SELECT attachment_name FROM $table_name_allergy_attachment WHERE allergy_name = %s", sanitize_text_field($allergy_name)));
    
                            if ($old_attachment_name) {
                                $wpdb->delete(
                                    $table_name_allergy_attachment,
                                    array('attachment_name' => sanitize_file_name($old_attachment_name)),
                                    array('%s')
                                );
    
                                $wpdb->delete(
                                    $table_name_attachment,
                                    array('attachment_name' => sanitize_file_name($old_attachment_name)),
                                    array('%s')
                                );
                            }
    
                            // Update de `allergy_attachment`-tabel met de nieuwe bijlage
                            $existing_allergy = $wpdb->get_var($wpdb->prepare("SELECT attachment_name FROM $table_name_allergy_attachment WHERE allergy_name = %s", sanitize_text_field($allergy_name)));
    
                            if ($existing_allergy) {
                                $wpdb->update(
                                    $table_name_allergy_attachment,
                                    array('attachment_name' => sanitize_file_name($attachment_name)),
                                    array('allergy_name' => sanitize_text_field($allergy_name)),
                                    array('%s'),
                                    array('%s')
                                );
                            } else {
                                $wpdb->insert($table_name_allergy_attachment,
                                    array(
                                        'allergy_name' => sanitize_text_field($allergy_name),
                                        'attachment_name' => sanitize_file_name($attachment_name)
                                    ),
                                    array('%s', '%s')
                                );
                            }
    
                            $wpdb->query('COMMIT');
                            $updated_icons = true;
                        } else {
                            echo '<p>The attachment name "' . esc_html($attachment_name) . '" already exists. Please choose a different file.</p>';
                        }
                    } else {
                        echo '<p>Failed to upload file for ' . esc_html($allergy_name) . '.</p>';
                    }
                }
            }
    
            if ($updated_icons) {
                echo '<script type="text/javascript">location.reload();</script>';
                echo '<p>Icons updated successfully. Reloading page...</p>';
            }
        }
    }    
}
