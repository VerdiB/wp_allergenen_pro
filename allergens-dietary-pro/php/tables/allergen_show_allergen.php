<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Allergens_Dietary_Pro_Allergen_Queries' ) ) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/DB/allergen.php';
}

if ( ! class_exists( 'Allergens_Dietary_Pro_Allergy_Product_Queries' ) ) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/DB/allergy_product.php';
}

if ( ! class_exists( 'Allergens_Dietary_Pro_Form' ) ) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/forms/allergen_form.php';
}

if ( ! class_exists( 'Allergens_Dietary_Pro_Notices' ) ) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/notice/notice.php';
}

if ( ! class_exists( 'Allergens_Dietary_Show_Allergens' ) ) {
	require_once ALLERGENS_DIETARY_FREE_DIRNAME . '/php/tables/allergen_show_allergen.php';
}

/**
 * @class Allergens_Dietary_Pro_Show_Allergens
 * @brief Class that shows the allergens
 * the user can see the already created allergies
 * @author Verdi-B
 * @date 24-9-2024
 * @since 1.0.0
 */

class Allergens_Dietary_Pro_Show_Allergens extends Allergens_Dietary_Show_Allergens 
{    
    /**
     * @author Verdi-B
     * @return void
     * @since V0.18.6.0
     * @version V0.18.6.0
     */
    protected function __construct()
    {
        parent::__construct();
		
        if (!empty($_COOKIE['Error']) && isset($_COOKIE['Error'])){
            $msg = __(
				sanitize_text_field(
					wp_unslash(
						$_COOKIE['Error'])
					),
				'allergens-dietary-pro');
			$notice = Allergens_Dietary_Notices::getInstance();
			$notice->display_admin_notice(Notice_Types::ERROR, $msg);
			setcookie('Error', '0', time() - 30);
		}

		if (!empty($_COOKIE['Success']) && isset($_COOKIE['Success'])){
            $msg = __(
				sanitize_text_field(
					wp_unslash(
						$_COOKIE['Success'])
					),
				'allergens-dietary-pro');
			$notice = Allergens_Dietary_Notices::getInstance();
			$notice->display_admin_notice(Notice_Types::SUCCESS, $msg);
			setcookie('Success', '0', time() - 30);
		}
    }

    /**
     * @author Verdi-B
     * @return array
     * @since V0.18.6.0
     * @version V0.18.6.0
     */
    protected function get_bulk_actions()
    {
		$parent_array = parent::get_bulk_actions();
		$return_array = array();
		foreach ($parent_array as $key => $value){
			$return_array[$key] = $value;
		}
		$return_array['bulk-delete'] = __('Delete', 'allergens-dietary-pro');
        return $return_array;
    }

    /**
     * @author Verdi-B
     * @brief defines a custom response on column rows for allergens
     * In this case only to change its status
     * @param array|object $item
     * @return string
     * @since V0.18.6.0
     * @version V0.18.6.0
     */
    protected function column_allergy_name(array|object $item){
		// $status = parent::column_allergy_name($item);
        $delete_nonce = esc_attr(wp_create_nonce("delete-" . $item['allergy_name']));
        $edit_nonce = esc_attr(wp_create_nonce("edit-" . $item['allergy_name']));

		// $default_item = Allergens_Dietary_Pro_Allergen_Queries::getInstance()->is_default_allergen($item['allergy_name']);
		$default_item = $item['is_default_option'];

        $delete_url = add_query_arg(
			array(
                'page'      =>  static::PAGE,
                'action'    =>  'delete',
                'item'      =>  $item['allergy_name'],
                'paged'     =>  $this->get_pagenum(),
                '_wpnonce'  =>  $delete_nonce
            ),
            admin_url('admin.php')
        );
        $edit_url = add_query_arg(
			array(
                'page'      =>  static::PAGE,
                'action'    =>  'edit',
                'item'      =>  $item['allergy_name'],
                'paged'     =>  $this->get_pagenum(),
                '_wpnonce'  =>  $edit_nonce
            ),
            admin_url('admin.php')
        );

        $actions = array(
			// 'change status' => $status
			'change status' => parent::column_allergy_name($item)
        );
		if($default_item){
			$actions['delete'] = sprintf(
				'<span>%s</span>',
				__('Delete', 'allergens-dietary-pro')
			);
			$actions['edit'] = sprintf(
				'<span>%s</span>',
				__('Edit', 'allergens-dietary-pro')
			);
		}else{
			$actions['delete'] = sprintf(
				'<a href="%s">%s</a>',
				$delete_url,
				__('Delete', 'allergens-dietary-pro')
			);
			$actions['edit'] = sprintf(
				'<a href="%s">%s</a>',
				$edit_url,
				__('Edit', 'allergens-dietary-pro')
			);
		}
        return sprintf('%1$s %2$s',$item['allergy_name'] , $this->row_actions($actions));
    }

    /**
     * @author Verdi-B
     * @brief Handles bulk action on all allergens
     * where as for now only changes the status of an allergy/dietary
     * @return void
     * @since V0.18.6.0
     * @version V0.18.6.0
     */
    protected function process_bulk_action(){
		parent::process_bulk_action();
		//check the nonce
        if(!isset($_POST['_wpnonce']) || empty($_POST['_wpnonce'])){
			return;
		}

		//sanitize the nonce
		$nonce = sanitize_text_field(wp_unslash($_POST['_wpnonce']));
		$action = 'bulk-' . $this->_args['plural'];
		
		//verify nonce
		if(!wp_verify_nonce($nonce, $action)) {
			wp_die(esc_html(__('Security check failed!', 'allergens-dietary-pro')));
		}
		
		//check if there are allergens in array sends query to db
		if(!isset($_POST['allergens']) || empty($_POST['allergens'])){
			return;
		}
		
		
		if ('bulk-delete' === $this->current_action()){
			$to_delete = array_map('sanitize_text_field', wp_unslash($_POST['allergens']));

			$allergen_query_arr = array();

			$all_att_query = Allergens_Dietary_Pro_Allergy_Attachment_Queries::getInstance();
			$all_query = Allergens_Dietary_Pro_Allergen_Queries::getInstance();
			
			foreach($this->_allergens as $allergen){
				if (in_array($allergen['allergy_name'], $to_delete)){
                    $allergen_query_arr[]= $allergen;
                } 
			}			
			
			foreach($allergen_query_arr as $allergen_name){
				$tmp = $all_query->is_default_allergen($allergen_name['allergy_name']) ? 'true' : 'false'; 
				if( true == $tmp){
					setcookie('Error', 'You are not permitted to delete default allergens: ' . $allergen_name['allergy_name'], time() + 30);
					wp_redirect(admin_url('admin.php?page=' . static::PAGE . '&paged='. $this->get_pagenum()));
					exit;
				}

				$data = $all_att_query->getallergyAttachment($allergen_name['allergy_name'], true);

				$attachment = $data['attachment_name'];
				if($all_att_query->checkMultipleAttachmentsExists($attachment) || $attachment === 'no_icon_selected.png'){
					$all_att_query->deleteAllergyAndConnection($allergen_name['allergy_name']);
				}else{
					$all_att_query->deleteAllergyAttachment($allergen_name['allergy_name']);
				}
				setcookie('Success','Succesfully deleted one or more allergens', time() + 30);
			}

			wp_redirect(admin_url('admin.php?page=' . static::PAGE . '&paged='. $this->get_pagenum()));
			exit;
		}
    }

    /**
     * @author Verdi-B
     * @overload from parrent method and can be overloaded still
     * @brief handles custom row actions on the allergen table
     * for this version of the plug-in it will only handle status changes
     * @param object|array $item
     * @param string $column_name
     * @param string $primary
     * @return void
     * @since V0.18.6.0
     * @version V0.18.6.0
     */
    protected function handle_row_actions($item, $column_name, $primary)
    {        
		parent::handle_row_actions($item, $column_name, $primary);
        if (empty($_GET['item']) ||
            empty($_GET['action']) ||
            empty($_GET['page'])
        ){
			return;
		}
		$allergen_name = preg_replace('/^https?:\/\//','',sanitize_url(wp_unslash($_GET['item'])));
		$table_action = preg_replace('/^https?:\/\//','', sanitize_url(wp_unslash($_GET['action'])));
		$nonce = preg_replace('/^https?:\/\//','', sanitize_url(wp_unslash($_GET['_wpnonce'])));

		if(false !== wp_verify_nonce($nonce, "delete-" . $allergen_name)){
			if ($table_action === 'delete'){
				if(!Allergens_Dietary_Pro_Allergen_Queries::getInstance()->is_default_allergen($allergen_name)){
					$all_att_query = Allergens_Dietary_Pro_Allergy_Attachment_Queries::getInstance();
					$data = $all_att_query->getallergyAttachment($allergen_name, true);
					$attachment = $data['attachment_name'];
					
					if($all_att_query->checkMultipleAttachmentsExists($attachment,true) || $attachment === 'no_icon_selected.png'){
						$all_att_query->deleteAllergyAttachment($allergen_name);
						Allergens_Dietary_Pro_Allergy_Product_Queries::getInstance()->deleteAllergiesProduct($allergen_name);
						Allergens_Dietary_Pro_Allergen_Queries::getInstance()->deleteAllergen($allergen_name);
					}else{
						$all_att_query->deleteAllergyAttachment($allergen_name);
						Allergens_Dietary_Pro_Attachment_Queries::getInstance()->deleteAttachment($attachment);
						Allergens_Dietary_Pro_Allergy_Product_Queries::getInstance()->deleteAllergiesProduct($allergen_name);
						Allergens_Dietary_Pro_Allergen_Queries::getInstance()->deleteAllergen($allergen_name);					
					}
					setcookie('Success','Succesfully deleted allergen', time() + 30);
				}else{
					setcookie('Error','You are not permitted to delete default allergens', time() + 30);
				}
                wp_redirect(admin_url('admin.php?page=' . static::PAGE . '&paged='. $this->get_pagenum()));
                exit;
			}
		}        
		if(false !== wp_verify_nonce($nonce, "edit-" . $allergen_name)){
			if ($table_action === 'edit'){
				setcookie('return-page', static::PAGE . '&paged='. $this->get_pagenum(), time()+60 *60 * 24);
				wp_redirect(admin_url('admin.php?page=allergens-dietary-add-allergen&action=' . $table_action . '&item=' . $allergen_name));
				exit;
			}
		}        
    }
}
