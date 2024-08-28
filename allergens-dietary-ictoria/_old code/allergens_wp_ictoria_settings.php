<?php
//exit if user can access this file directly.
if(!defined('ABSPATH')){
	exit;
}


function allergens_list_910_styles_and_scripts_to_backend() {
    
    if (isset($_REQUEST['page'])) {
        $page = $_REQUEST['page'];
    } else {
        $page = '';
    }

    if (in_array($page, array('wc-settings'))) {
    
        $langs = array(
                'title'  => __('Title', 'allergens-list'),
                'icon'   => __('Icon url', 'allergens-list'),
                'url'    => __('Info url', 'allergens-list'),
                'save'   => __('Save', 'allergens-list'),
                'cancel' => __('Cancel', 'allergens-list')
            );
        
        wp_register_style( 'allergens-list-admin-css', plugins_url( 'allergens-wp-ictoria/assets/css/allergens-list-admin.css', ALLERGENS_LIST_910_ROOT ) );
    	wp_enqueue_style(  'allergens-list-admin-css' );
    	wp_enqueue_script('jquery-ui-sortable', '', array('jquery'));
        wp_register_script('allergens-list-admin-js', plugins_url( 'allergens-wp-ictoria/assets/js/allergens-list-admin.js', ALLERGENS_LIST_910_ROOT ), array('jquery'));
        wp_localize_script('allergens-list-admin-js', 'allergens_list_910_langs', $langs);
    	wp_enqueue_script( 'allergens-list-admin-js');
 
    }
}

class Allergens_Wp_Ictoria_Settings extends WC_Integration{
	private $options;
	
	public function __construct() {
		global $woocommerce;
		$this->id                 = 'allergens_wp_ictoria';
		$this->method_title       = __( 'Allergens', 'allergens-wp-ictoria' );
		$this->method_description = __( 'You can change the order using the "drag and drop" method!', 'allergens-list' );

		$page = '';
		if (isset($_REQUEST['page'])) { $page = sanitize_text_field($_REQUEST['page']); }
		$tab = '';
		if (isset($_REQUEST['tab'])) { $tab = sanitize_text_field($_REQUEST['tab']); }
		$section = '';
		if (isset($_REQUEST['section'])) { $section = sanitize_text_field($_REQUEST['section']); }
		
		if (($page == 'wc-settings') and ($tab == 'integration') and ($section == 'allergens_list')) {
			$this->init_form_fields();
		}
		add_action( 'woocommerce_update_options_integration_' .  $this->id, array( $this, 'process_admin_options' ) );
		//add_action( 'admin_enqueue_scripts', 'allergens_list_910_styles_and_scripts_to_backend' );
	}

	public function init_form_fields() {
		$this->form_fields = array(
			'sorted_items' => array(
				'type'              => 'allergens',
				'desc_tip'          => false,
			)                    
		);            
	 }
	 
	public function generate_allergens_html( $key, $data ) {
	
		ob_start();
		$functions = 'Allergens_Wp_Ictoria_Functions';
		$data = $functions::get_options();
		echo '<div class="allergens-list">' . "\r\n" . 
			 '  <div>'. "\r\n" .
			 '      <ul id="allergens-items" class="sortable">' . "\r\n";
		if (!empty($data)) {
			foreach ($data as $key => $item) {
				if (!isset($item['url'])) { $item['url'] = ''; }
				echo '<li id="'.$key.'" onclick="allergens_list_910_edit(jQuery(this));">' .
					   '<img src="'.$item['icon'].'" class="allergen-icon" />' .
					   '<span class="allergen-title">'.$item['title'].'</span>' .
					   '<span class="allergen-url" style="display:none;">'.$item['url'].'</span>' .
					   '<span onclick="allergens_list_910_delete(jQuery(this));"" class="dashicons dashicons-trash"></span>' .
					 '</li>';
			}
		}
		echo '      </ul>' . "\r\n" .
			 '      <input type="hidden" id="woocommerce_allergens_list_sorted_items" name="woocommerce_allergens_list_sorted_items" value="" />' . "\r\n" .
			 '      <div style="clear:both;height:1px;"></div>' . "\r\n" .
			 '      <button onclick="allergens_list_910_add_new();" type="button" class="button secondary">' . __( 'Add new allergen', 'allergens-list' ) . '</button>' . "\r\n" .
			 '      <hr />' . "\r\n" .
			 '  </div>' . "\r\n" .
			 '</div>' . "\r\n";
		return ob_get_clean();
	}   
	
	public function validate_allergens_field( $key, $value ) {

		if ($key == 'sorted_items') {
	
			$data = sanitize_text_field($value);
			$data = json_decode(stripslashes($data), true);
	
			if (empty($data)) {
				delete_option('allergens_wp_ictoria_options');            
			} else {
				$allergens = array();
				foreach ($data as $item) {
					$allergens[$item['id']] = array('title' => $item['title'], 'icon' => $item['icon'], 'url' => $item['url']);
				}
				update_option('allergens_wp_ictoria_options', $allergens);
			}
		} 

		return $value;
	}
}


?>