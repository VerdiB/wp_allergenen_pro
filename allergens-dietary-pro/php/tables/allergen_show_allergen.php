<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Allergens_Dietary_Pro_Allergen_Queries' ) ) {
	require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/DB/allergen.php';
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
 * @author Ictoria
 * @date 24-9-2024
 * @since 1.0.0
 */

class Allergens_Dietary_Pro_Show_Allergens extends Allergens_Dietary_Show_Allergens 
{    
    /**
     * @author ictoriabv
     * @return void
     * @since V0.18.6.0
     * @version V0.18.6.0
     */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * @author ictoriabv
     * @return array
     * @since V0.18.6.0
     * @version V0.18.6.0
     */
    protected function get_bulk_actions()
    {
        return array(
            'bulk-change-status' => __('Change status', 'allergens-dietary'),
            'bulk-delete' => __('Delete', 'allergens-dietary'),
        );
    }

    /**
     * @author ictoriabv
     * @brief defines a custom response on column rows for allergens
     * In this case only to change its status
     * @param array|object $item
     * @return string
     * @since V0.18.6.0
     * @version V0.18.6.0
     */
    protected function column_allergy_name(array|object $item){
		$status = parent::column_allergy_name($item);
        $delete_nonce = esc_attr(wp_create_nonce("delete-" . $item['allergy_name']));

		$default_item = Allergens_Dietary_Pro_Allergen_Queries::getInstance()->is_default_allergen($item['allergy_name']);

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
                'page'      =>  'allergens-dietary-add-allergen',
                'action'    =>  'edit',
                'item'      =>  $item['allergy_name'],
            ),
            admin_url('admin.php')
        );

        $actions = array(
			$status
        );
		if($default_item){
			$actions['delete'] = sprintf(
				'<span>%s</span>',
				__('Delete', 'allergens-dietary')
			);
			$actions['edit'] = sprintf(
				'<span>%s</span>',
				__('Edit', 'allergens-dietary')
			);
		}else{
			$actions['delete'] = sprintf(
				'<a href="%s">%s</a>',
				$delete_url,
				__('Delete', 'allergens-dietary')
			);
			$actions['edit'] = sprintf(
				'<a href="%s">%s</a>',
				$edit_url,
				__('Edit', 'allergens-dietary')
			);
		}
        return sprintf('%1$s %2$s',$item['allergy_name'] , $this->row_actions($actions));
    }

    /**
     * @author ictoriabv
     * @return string
     * @since V0.18.6.0
     * @version V0.18.6.0
     */
    protected function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="allergens[]" value="%s" />',
            $item['allergy_name']
        );
    }

    /**
     * @author ictoriabv
     * @brief Handles bulk action on all allergens
     * where as for now only changes the status of an allergy/dietary
     * @return void
     * @since V0.18.6.0
     * @version V0.18.6.0
     */
    protected function process_bulk_action(){
        //check the nonce
        if(isset($_POST['_wpnonce']) && !empty($_POST['_wpnonce'])){
            //sanitize the nonce
            $nonce = sanitize_text_field(wp_unslash($_POST['_wpnonce']));
            $action = 'bulk-' . $this->_args['plural'];

            //verify nonce
            if(!wp_verify_nonce($nonce, $action)) {
                wp_die(esc_html(__('Security check failed!', 'allergens-dietary')));
            }

            //check if there are allergens in array sends query to db
            if(isset($_POST['allergens']) && !empty($_POST['allergens'])){
                if ('bulk-change-status' === $this->current_action()){
                    $to_change = array_map('sanitize_text_field', wp_unslash($_POST['allergens']));
                    $allergen_query_arr = array();
                    foreach($this->_allergens as $allergen){
                        if (in_array($allergen['allergy_name'], $to_change)){
                            $allergen_query_arr[]= $allergen;
                        }
                    }
                    foreach($allergen_query_arr as $allergen_query){
                        $allergen_query['is_active'] =  ($allergen_query['is_active'] == 1)? 0 : 1;
                        Allergens_Dietary_Allergen_Queries::getInstance()->change_status($allergen_query);
                    }
                    setcookie('notice-type','bulk-status', time() + 30);
                    wp_redirect(admin_url('admin.php?page=' . static::PAGE . '&paged='. $this->get_pagenum()));
                    exit;
                }
                if ('bulk-delete' === $this->current_action()){
                    $to_change = array_map('sanitize_text_field', wp_unslash($_POST['allergens']));
                    $allergen_query_arr = array();
                    foreach($this->_allergens as $allergen){
                        if (in_array($allergen['allergy_name'], $to_change)){
                            $allergen_query_arr[]= $allergen;
                        }
                    }
                    foreach($allergen_query_arr as $allergen_query){
                        $allergen_query['is_active'] =  ($allergen_query['is_active'] == 1)? 0 : 1;
                        Allergens_Dietary_Allergen_Queries::getInstance()->change_status($allergen_query);
                    }
                    setcookie('notice-type','bulk-status', time() + 30);
                    wp_redirect(admin_url('admin.php?page=' . static::PAGE . '&paged='. $this->get_pagenum()));
                    exit;
                }
            }
        }
    }

    /**
     * @author ictoriabv
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
        if (empty(sanitize_url(wp_unslash($_GET['item']))) ||
            empty(sanitize_url(wp_unslash($_GET['action']))) ||
            empty(sanitize_url(wp_unslash($_GET['page'])))
        ){
			return;
		}
		$allergen_name = preg_replace('/^https?:\/\//','',sanitize_url(wp_unslash($_GET['item'])));
		$table_action = preg_replace('/^https?:\/\//','', sanitize_url(wp_unslash($_GET['action'])));

		if(check_admin_referer("delete-" . $allergen_name)){
			var_dump("handle-row-delete");
			if ($table_action === 'delete'){
				$allergen_active = array();
				foreach ($this->_allergens as $allergen){
					if(array_search($allergen_name, $allergen)){
						$allergen_active = $allergen;
						break;
					}
				}
				$allergen_active['is_active'] = ($allergen_active['is_active'] == 1)? 0 : 1;
				Allergens_Dietary_Allergen_Queries::getInstance()->change_status($allergen_active);
				
				setcookie('notice-type','single-status', time() + 30);
				wp_redirect(admin_url('admin.php?page=' . static::PAGE . '&paged='. $this->get_pagenum()));
				exit;
			}
		}        
    }
}
