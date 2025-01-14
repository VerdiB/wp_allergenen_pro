<?php

// exit if user can access this file directly
if (!defined('ABSPATH')) {
	exit;
}

// if (!interface_exists('I_Allergens_Dietary_Pro_Form')) {
// 	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/forms/Iallergen_form.php';
// }

if (!interface_exists('I_Allergens_Dietary_Form')) {
	require_once ALLERGENS_DIETARY_FREE_DIRNAME . '/php/forms/Iallergen_form.php';
}

if (!class_exists('Allergens_Dietary_Pro_Allergy_Attachment_Queries')) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/DB/allergy_attachment.php';
}

if (!class_exists('Allergens_Dietary_Pro_Attachment_Queries')) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/DB/attachment.php';
}

if (!class_exists('Allergens_Dietary_Pro_Allergen_Queries')) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/DB/allergen.php';
}
/**
 * @brief This shows the tabs on add/update allergens .
 * @author Ictoria
 * @since 1.0.0
 * @date 18-9-2024
 */
/**
 * @brief This shows the tabs on add/update allergens .
 * @author Ictoria
 * @since 1.0.0
 * @date 18-9-2024
 */

if (!class_exists('Allergens_Dietary_Pro_Tabs')) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/tabs/allergen_tabs.php';
}
if (!enum_exists('Mime_Types')) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/lists/mime_types.php';
}


if (!class_exists('Allergens_Dietary_Pro_Notices')) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/notice/notice.php';
}

if (! enum_exists('Notice_Types')) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/notice/notice_Types.php';
}
/********************************************************************/
/********************************************************************/

class Allergens_Dietary_Pro_Allergen_Form implements I_Allergens_Dietary_Form
{


	protected ?array $_allergen = null;
	protected bool $editing = false;
	protected array $MIME_TYPES;
	protected array $MIME_NAMES;
	protected static string $message = '';
	protected static Notice_Types $_type;

	public function __construct()
	{
		$this->MIME_TYPES = Mime_Types::get_mime_types();
		$this->MIME_NAMES = array_map(fn($case) => $case->name, Mime_Types::cases());
	}

	/**
	 * @param string|null $allergenName
	 * @brief This method shows the form to add/update allergens .
	 * @return void
	 * @author Ictoria
	 * @since 1.0.0
	 * @date 11-9-2024
	 */
	public function showForm(?string $allergenName = null)
	{
		if (isset($_GET['action']) && isset($_GET['item'])) {
			$allergen = sanitize_text_field(wp_unslash($_GET['item']));
			if (Allergens_Dietary_Pro_Allergen_Queries::getInstance()->is_default_allergen($allergen)){
				wp_die(__('You are unable to update any default allergens.', 'allergens-dietary-pro'));
			}
			$this->_allergen = Allergens_Dietary_Pro_Allergy_Attachment_Queries::getInstance()->getallergyAttachment($allergen);
			$this->editing = true;
		}

?>
		<table style="width: 100%">
			<fieldset id="the-list" class="inline-edit-product.quick-edit-row">
				<fieldset class="inline-edit-col-left">
					<div class="inline-edit-row">
						<legend style="font-weight: bold;" class="inline-edit-legend">
							<?php echo __('Add allergen', 'allergens-dietary-pro') ?>
						</legend>
						<br>
						<div class="inline-edit-wrapper" aria-labelledby="quick-edit-legend">
							<tr>
								<th class="align-header" scope="row">
									<label for="allergen_name"> <?php echo __('Allergen name', 'allergens-dietary-pro') ?></label>
								</th>
								<td>
									<input type="text" name="allergen_name" id="allergen_name" style="width: 100%;" value="<?php echo (!empty($this->_allergen)) ? $this->_allergen['allergy_name'] : '' ?> " required />
								</td>
							</tr>
							<tr>
								<th class="align-header" scope="row"><label for="type"><?php echo __('Type', 'allergens-dietary-pro') ?></label></th>
								<td>
									<select name="type" id="type" style="width: 100%" required>
										<?php echo self::do_dropdown(); ?>
									</select>
								</td>
							</tr>
				</fieldset>
			</fieldset>
		</table>
		<br>
		<label class="bold" for="allergen_description">
			<span style="display: block; margin-bottom: 10px;"><?php echo __('Allergen description', 'allergens-dietary-pro') ?></span>
		</label>
		<textarea class="update_" name="allergen_description" id="allergen_description" style="width: 100%; max-width: 400px; min-height: 100px; resize: none;" maxlength="255"><?php echo (!empty($this->_allergen)) ? $this->_allergen['allergy_description'] : '' ?></textarea>
		<table>
			<div class="item">
				<fieldset class="inline-edit-col-right drag-drop-buttons">
					<div class="item">
						<tr class="item-row">
							<td>
								<label class="label-quick-edit wp-core-ui button">
									<input type="file" class="update_ allergen_icon_file_input" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" name="allergen_icon" id="allergen_icon_file_input">
									<input type="hidden" class="update_" name="allergen_icon_hidden" value=" <?php echo (!empty($this->_allergen)) ? $this->_allergen['attachment_name'] : '' ?>">
									<span><?php echo __($this->editing ? 'Update image' : 'Set image', 'allergens-dietary-pro') ?></span>
								</label>
							</td>
							<td class="item-header">
								<figure style="text-align: center;">
									<br>
									<img class="update_ add_allergen_icon_img" id="allergen_icon_img" style="max-height: 40px; max-width: 40px;" src="<?php echo $this->editing ? $this->_allergen['attachment_path'] : get_home_url() . '/wp-content/plugins/allergens-dietary-pro/assets/icons/no_icon_selected.png' ?>" alt="no_icon_selected.png">
									<figcaption style="font-size: 10px; max-width: 200px; font-weight: bold; color: gray;"> <?php echo __('Max size of an icon is 40x40 pixels.', 'allergens-dietary-pro') ?> </figcaption>
								</figure>
							</td>
						</tr>
					</div>
				</fieldset>
		</table>
		<td>
			<br>
			<br>
			<input type="submit" name="submit" class="button button-primary" value="<?php echo __($this->editing ? 'Save allergen' : 'Add allergen', 'allergens-dietary-pro') ?>" />
		</td>
		</div>

<?php
	}


	/**
	 * @param array $data
	 * @brief This method submits the form data to the DB.
	 * @throws Exception if the file is not a valid image
	 * @return void
	 * @author Ictoria
	 * @since 1.0.0
	 * @date 11-9-2024
	 */
	public function submit(array $data)
	{
		$data = $this->sanitize($data);

		$file_type_input = wp_check_filetype($data['allergen_icon']['name']);
		$valid_icon = in_array($file_type_input['type'], $this->MIME_TYPES) ? true : false;
		$empty_file_input = empty($data['allergen_icon']['name']) ? true : false;

		// DB Query's
		$all_query = Allergens_Dietary_Pro_Allergen_Queries::getInstance();
		$att_query = Allergens_Dietary_Pro_Attachment_Queries::getInstance();
		$all_att_query = Allergens_Dietary_Pro_Allergy_Attachment_Queries::getInstance();

		$attachment_exists = $att_query->checkAttachmentExists($data['allergen_icon']['name']);
		$notice = Allergens_Dietary_Pro_Notices::getInstance();
		self::$_type = Notice_Types::ERROR;

		$no_icon_selected = false;

		if (empty($data) || !isset($data)) {
			self::$message = __('Form has not been set!', 'allergens-dietary-pro');
			$notice->display_admin_notice(self::$_type, self::$message);
			return;
		} elseif (empty($data['allergen_name'])) {
			self::$message = __('Allergen Name Can\'t be empty or blank!', 'allergens-dietary-pro');
			$notice->display_admin_notice(self::$_type, self::$message);
			return;
		}
		if ($all_query->checkAllergenExists($data['allergen_name'])) {
			self::$message = __('Allergen name already exists', 'allergens-dietary-pro');
			$notice->display_admin_notice(self::$_type, self::$message);
			return;
		}
		if (!$valid_icon && !$no_icon_selected) {
			self::$message = __('The file is not a valid image. Supported image types are: ', 'allergens-dietary-pro') . implode(', ', $this->MIME_NAMES);
			$notice->display_admin_notice(self::$_type, self::$message);
			return;
		}

		if ($this->editing) {
			

		} else {

			

			// Default image
			if ($empty_file_input) {
				$imagePath = get_home_url() . '/wp-content/plugins/allergens-dietary-pro/assets/icons/no_icon_selected.png';
				$imageName = sanitize_file_name(basename($imagePath));
				$data['allergen_icon'] = [
					'name' => $imageName,
					'tmp_name' => $imagePath,
					'type' => 'image/png',
				];
				$no_icon_selected = true;
			}

			

			$all_query->addAllergens($data);
			if (!$no_icon_selected && !$attachment_exists) { // Don't add attachment to the table, image already exists there.
				$att_query->addAttachment($data['allergen_icon']);
			}
			$all_att_query->addAllergyAttachment($data);			
		}
		self::$_type = Notice_Types::SUCCESS;
		self::$message = __('Succesfully added new allergen: ' . $data['allergen_name'] . '.', 'allergens-dietary-pro');
		$notice->display_admin_notice(self::$_type, self::$message);
	}

	/**
	 * @param array $data
	 * @brief This method sanitizes the form data for the DB.
	 * @return array $data
	 * @author Ictoria
	 * @since 1.0.0
	 * @date 11-9-2024
	 */
	public function sanitize(array $data)
	{
		$data['allergen_name'] = sanitize_text_field(wp_unslash($data['allergen_name']));
		$data['allergen_description'] = sanitize_text_field(wp_unslash($data['allergen_description']));
		$data['type'] = absint(sanitize_text_field(wp_unslash($data['type'])));
		$data['allergen_icon']['name'] = sanitize_file_name($data['allergen_icon']['name']);

		return $data;
	}
	private function do_dropdown()
	{
		$html = '';

		$html .= '<option value="1" ' . (!empty($this->_allergen['type']) && $this->_allergen['type']  === 1 ? 'selected' : '') . '>' . __('Allergy', 'allergens-dietary-pro') . '</option>';
		$html .= '<option value="0" ' . (!empty($this->_allergen['type']) && $this->_allergen['type']  === 1 ? 'selected' : '') . '>' . __('Dietary restriction', 'allergens-dietary-pro') . '</option>';
		return $html;


		return $html;
	}
}
