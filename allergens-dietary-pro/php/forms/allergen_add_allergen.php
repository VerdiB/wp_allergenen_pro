<?php

// exit if user can access this file directly
if (!defined('ABSPATH')) {
	exit;
}

if (!interface_exists('Allergens_Dietary_Form_I')) {
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

class Allergens_Dietary_Pro_Allergen_Form implements Allergens_Dietary_Form_I
{


	protected ?array $_allergen = null;
	protected array $MIME_TYPES;
	protected array $MIME_NAMES;
	protected static string $message = '';
	protected static Notice_Types $_type;
	protected string $return_page;

	public function __construct()
	{
		$this->MIME_TYPES = Mime_Types::get_mime_types();
		$this->MIME_NAMES = array_map(fn($case) => $case->name, Mime_Types::cases());
		
		if(isset($_COOKIE['return-page'])){
			$this->return_page = sanitize_text_field(wp_unslash($_COOKIE['return-page']));
		}
		if(isset($_COOKIE['Error'])){
			$message = sanitize_text_field(wp_unslash($_COOKIE['Error']));
			Allergens_Dietary_Pro_Notices::getInstance()->display_admin_notice(Notice_Types::ERROR, esc_html(__($message, 'allergens-dietary-pro')) );
			setcookie('Error', '', time() - 60 );
		}
		if(isset($_COOKIE['Success'])){
			$message = sanitize_text_field(wp_unslash($_COOKIE['Success']));
			Allergens_Dietary_Pro_Notices::getInstance()->display_admin_notice(Notice_Types::SUCCESS, esc_html(__($message, 'allergens-dietary-pro')) );
			setcookie('Success', '', time() - 60 );
		}

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
		$editing = false;
		if (isset($_GET['action']) && isset($_GET['item'])) { // Check to see if editing allergen or adding.
			if($_GET['action'] !== 'edit'){
				wp_die(esc_html(__('You are unable to edit', 'allergens-dietary-pro')));
			}
			$allergen = sanitize_text_field(wp_unslash($_GET['item']));
			if (Allergens_Dietary_Pro_Allergen_Queries::getInstance()->is_default_allergen($allergen)){
				wp_die(esc_html(__('You are not permitted to make any changes to default allergens', 'allergens-dietary-pro')));
			}

			$this->_allergen = Allergens_Dietary_Pro_Allergy_Attachment_Queries::getInstance()->getallergyAttachment($allergen);
			$editing = true;	
		}

?>
		<table style="width: 100%">
			<fieldset id="the-list" class="inline-edit-product.quick-edit-row">
				<fieldset class="inline-edit-col-left">
					<div class="inline-edit-row">
						<legend style="font-weight: bold;" class="inline-edit-legend">
							<?php echo esc_html(__('Add allergen', 'allergens-dietary-pro')) ?>
						</legend>
						<br>
						<div class="inline-edit-wrapper" aria-labelledby="quick-edit-legend">
							<tr>
								<th class="align-header" scope="row">
									<label for="allergen_name"> <?php echo esc_html(__('Allergen name', 'allergens-dietary-pro')) ?></label>
								</th>
								<td>
									<input type="text" name="allergen_name" id="allergen_name" style="width: 100%;" value="<?php echo esc_html((!empty($this->_allergen)) ? $this->_allergen['allergy_name'] : '') ?>" required />
									<input type="hidden" name="allergen_name_hidden" id="allergen_name_hidden" value="<?php echo esc_html((!empty($this->_allergen)) ? $this->_allergen['allergy_name'] : '') ?>" required />
								</td>
							</tr>
							<tr>
								<th class="align-header" scope="row"><label for="type"><?php echo esc_html(__('Type', 'allergens-dietary-pro')) ?></label></th>
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
			<span style="display: block; margin-bottom: 10px;"><?php echo esc_html(__('Allergen description', 'allergens-dietary-pro')) ?></span>
		</label>
		<textarea class="update_" name="allergen_description" id="allergen_description" style="width: 100%; max-width: 400px; min-height: 100px; resize: none;" maxlength="255"><?php echo esc_html((!empty($this->_allergen)) ? $this->_allergen['allergy_description'] : '') ?></textarea>
		<table>
			<div class="item">
				<fieldset class="inline-edit-col-right drag-drop-buttons">
					<div class="item">
						<tr class="item-row">
							<td>
								<label class="label-quick-edit wp-core-ui button">
									<input type="file" class="update_ allergen_icon_file_input" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" name="allergen_icon" id="allergen_icon_file_input">
									<input type="hidden" class="update_" name="allergen_icon_hidden" value=" <?php echo esc_html((!empty($this->_allergen)) ? $this->_allergen['attachment_name'] : '') ?>">
									<span><?php echo esc_html(__($editing ? 'Update image' : 'Set image', 'allergens-dietary-pro')) ?></span>
								</label>
							</td>
							<td class="item-header">
								<figure style="text-align: center;">
									<br>
									<img class="update_ add_allergen_icon_img" id="allergen_icon_img" style="max-height: 40px; max-width: 40px;" src="<?php echo esc_html((true === $editing && !empty($this->_allergen['attachment_path'])) ? $this->_allergen['attachment_path'] : get_home_url() . '/wp-content/plugins/allergens-dietary-pro/assets/icons/no_icon_selected.png') ?>" alt="no_icon_selected.png">
									<figcaption style="font-size: 10px; max-width: 200px; font-weight: bold; color: gray;"> <?php echo esc_html(__('Max size of an icon is 40x40 pixels.', 'allergens-dietary-pro')) ?> </figcaption>
								</figure>
							</td>
						</tr>
					</div>
				</fieldset>
		</table>
		<td>
			<br>
			<br>
			<div style="display: flex; justify-content:space-between;">
				<input type="submit" name="submit" class="button button-primary" value="<?php echo esc_html(__($editing ? 'Save' : 'Add Allergen', 'allergens-dietary-pro')) ?>" />
				<?php if($editing){?> <input type="submit" name="submit[submit-return]" class="button button-secondary" value="<?php echo esc_html(__('Save and return', 'allergens-dietary-pro'))?>" /><?php } ?>
			</div>
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

		$no_icon_selected = false;
		$return_to_page = false;
		$editing = false;
		
		if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['item'])) {			
			$editing = true;
		}

		$file_type_input = wp_check_filetype($data['allergen_icon']['name']);
		$valid_icon = in_array($file_type_input['type'], $this->MIME_TYPES) ? true : false;
		$empty_file_input = empty($data['allergen_icon']['name']) ? true : false;
		
		// DB Query's
		$all_query = Allergens_Dietary_Pro_Allergen_Queries::getInstance();
		$att_query = Allergens_Dietary_Pro_Attachment_Queries::getInstance();
		$all_att_query = Allergens_Dietary_Pro_Allergy_Attachment_Queries::getInstance();

		$skip = $this->valid_input($data, $editing, $all_query, $att_query, $valid_icon, $empty_file_input);
		
		if(true === $skip){
			if(true === $editing){
				$this->handle_edit($data, $all_query, $att_query, $all_att_query, $empty_file_input);
			}else{
				$this->handle_add($data, $all_query, $att_query, $all_att_query, $empty_file_input, $no_icon_selected);
			}
		}

		if(isset($data['submit']['submit-return'])){
			$return_to_page = true;
		}

		$this->handle_redirect($data, $return_to_page, $editing, $skip);
	}

	/**
	 * @brief This method redirects the user to a different or the same page with some conditions.
	 * @return void
	 * @author Ictoria
	 * @since 1.0.0
	 * @date 16-1-2025
	 */
	protected function handle_redirect(array $data, bool $return_to_page, bool $editing, bool $skip){
		if(true === $return_to_page){
			$return_page = !empty($this->return_page) ? $this->return_page : 'allergens-dietary-show-allergens';
			wp_redirect(admin_url('admin.php?page=' . $return_page ));
		}elseif(true === $editing){
			wp_redirect(admin_url('admin.php?page=allergens-dietary-add-allergen&action=edit&item=' . (false === $skip ? $data['allergen_name_hidden'] : $data['allergen_name'])));			
		}else{
			wp_redirect(admin_url('admin.php?page=allergens-dietary-add-allergen'));
		}
		exit;
	}
	
	/**
	 * @brief This method validates input made by the user and check for errors.
	 * If there is an error then it return false, else true.
	 * @return true|false
	 * @author Ictoria
	 * @since 1.0.0
	 * @date 16-1-2025
	 */
	protected function valid_input(array $data, bool $editing, $all_query, $att_query, bool $valid_icon, bool $empty_file_input): bool{
		if (empty($data) || !isset($data)) {
			setcookie('Error', 'Form has not been set!', time() + 30);
			return false;
		} elseif (empty($data['allergen_name'])) {
			setcookie('Error', "Allergen name can't be empty or blank!", time() + 30);
			return false;
		}

		if ($all_query->checkAllergenExists($data['allergen_name']) && $data['allergen_name'] !== $data['allergen_name_hidden']) {
			setcookie('Error', 'Allergen name already exists', time() + 30);
			return false;
		}
		if (!$valid_icon && !$empty_file_input) {
			setcookie('Error', 'The file is not a valid image. Supported image types are: '  . implode(', ', $this->MIME_NAMES), time() + 30);
			return false;
		}
		if($att_query->checkAttachmentExists($data['allergen_icon']['name']) && $data['allergen_icon']['name'] !== 'no_icon_selected.png'){
			setcookie('Error', 'The new image already exists', time() + 30);
			return false;
		}
		if(empty($data['allergen_name_hidden']) && true === $editing){ // Can't update the allergen if previous isn't set.
			setcookie('Error', 'There has to be a previous allergen to update', time() + 30);
			return false;
		}

		return true;
	}

	/**
	 * @brief This method handles the edit allergen logic.
	 * @return void
	 * @author Ictoria
	 * @since 1.0.0
	 * @date 16-1-2025
	 */
	protected function handle_edit(array $data, $all_query, $att_query, $all_att_query, bool $empty_file_input){
		$all_query->updateAllergens($data);
		if (!$empty_file_input) { // update only the new allergen data when not uploading a new image. name, description etc.
			if ($att_query->checkAttachmentExists($data['allergen_icon']['name'])) { // if the attachment exists, set to existing img and only remove attachment when not used.
				$all_att_query->updateAllergyAttachment($data['allergen_name'], $data['allergen_icon']['name']);
				if ($data['allergen_icon_hidden'] !== 'no_icon_selected.png' && !$all_att_query->attachmentIsUsed($data['allergen_icon_hidden'])) {
					$att_query->deleteAttachment($data['allergen_icon_hidden']);
				}
			} else {
				// Prevent losing no_icon_selected.png as image in DB, and if there are multiple of the old img don't change all of them.
				if ($data['allergen_icon_hidden'] === 'no_icon_selected.png' || $all_att_query->checkMultipleAttachmentsExists($data['allergen_icon_hidden'])) {
					$att_query->addAttachment($data['allergen_icon']);
					$all_att_query->updateAllergyAttachment($data['allergen_name'], $data['allergen_icon']['name']);
				} else {
					$att_query->updateAttachment($data['allergen_icon'], $data['allergen_icon_hidden']);
				}
			}
		}

		setcookie('Success', 'Succesfully updated/saved allergen', time() + 30);
	}

	/**
	 * @brief This method handles the add allergen logic.
	 * @return void
	 * @author Ictoria
	 * @since 1.0.0
	 * @date 16-1-2025
	 */
	protected function handle_add(array $data, $all_query, $att_query, $all_att_query, bool $empty_file_input, bool $no_icon_selected){
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
		if (false === $no_icon_selected) { // Don't add attachment to the table, image already exists there.
			$att_query->addAttachment($data['allergen_icon']);
		}
		$all_att_query->addAllergyAttachment($data);			
		setcookie('Success', 'Succesfully added new allergen: ' . $data['allergen_name'] . '.', time() + 30);
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
		$data['allergen_name_hidden'] = sanitize_text_field(wp_unslash($data['allergen_name_hidden']));
		$data['allergen_icon_hidden'] = sanitize_text_field(wp_unslash($data['allergen_icon_hidden']));
		$data['allergen_description'] = sanitize_text_field(wp_unslash($data['allergen_description']));
		$data['type'] = absint(sanitize_text_field(wp_unslash($data['type'])));
		$data['allergen_icon']['name'] = sanitize_file_name($data['allergen_icon']['name']);

		return $data;
	}
	private function do_dropdown()
	{
		$html = '';

		$html .= '<option value="1" ' . (!empty($this->_allergen['type']) && $this->_allergen['type']  === 1 ? 'selected' : '') . '>' . esc_html(__('Allergy', 'allergens-dietary-pro')) . '</option>';
		$html .= '<option value="0" ' . (!empty($this->_allergen['type']) && $this->_allergen['type']  === 1 ? 'selected' : '') . '>' . esc_html(__('Dietary restriction', 'allergens-dietary-pro')) . '</option>';
		return $html;


		return $html;
	}
}
