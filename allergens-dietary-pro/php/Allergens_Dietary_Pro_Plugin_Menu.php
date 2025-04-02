<?php
// exit if user can access this file directly
if (!defined('ABSPATH')) {
	exit;
}
if (!class_exists('Allergens_Dietary_Plugin_Menu')){
	require_once ALLERGENS_DIETARY_FREE_DIRNAME . '/php/Allergens_Dietary_Plugin_Menu.php' ;
}
class Allergens_Dietary_Pro_Plugin_Menu extends Allergens_Dietary_Plugin_Menu
{


	public function __construct()
	{
		add_action('admin_menu', array($this, 'addMyAdminMenu'));
	}

	// #[\Override]
	public function addMyAdminMenu()
	{
		parent::addMyAdminMenu();

		add_submenu_page(
			'allergens-dietary-options',
			__('Add allergen', 'allergens-dietary-pro'),
			__('Add allergen', 'allergens-dietary-pro'),
			'manage_options',
			'allergens-dietary-add-allergen',
			array($this, 'addallergens')
		);
		
		add_submenu_page(
			'allergens-dietary-options',
			__('Update allergen', 'allergens-dietary-pro'),
			__('Update allergen', 'allergens-dietary-pro'),
			'manage_options',
			'allergens-dietary-update-allergen',
			array($this, 'updateallergens')
		);

		add_submenu_page(
			'allergens-dietary-options',
			__('License key', 'allergens-dietary-pro'),
			__('License key', 'allergens-dietary-pro'),
			'manage_options',
			'allergens-dietary-license-form',
			array($this, 'licenseForm')
		);
	}
	
	public function addallergens()
	{
		if (!class_exists('Allergens_Dietary_Pro_Form') && !class_exists('Allergens_Dietary_Pro_Tabs')) {
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/forms/allergen_form.php';
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/tabs/allergen_tabs.php';
		}
		Allergens_Dietary_Pro_Tabs::getInstance()->showtabs();
		Allergens_Dietary_Pro_Form::setFormType(FormType::ALLERGENS);
		Allergens_Dietary_Pro_Form::getInstance()->showForm();
	}

	public function updateallergens()
	{
		if (!class_exists('Allergens_Dietary_Pro_Form')) {
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/forms/allergen_form.php';
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/tabs/allergen_tabs.php';
		}

		Allergens_Dietary_Pro_Tabs::getInstance()->showtabs();
		Allergens_Dietary_Pro_Form::setFormType(FormType::UPDATE);
		Allergens_Dietary_Pro_Form::getInstance()->showForm();
	}

	// #[\Override]
	public function licenseForm()
	{
		if (!class_exists('Allergens_Dietary_Pro_Form')) {
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/forms/allergen_form.php';
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/tabs/allergen_tabs.php';
		}
		// Allergens_Dietary_Pro_Tabs::getInstance()->showtabs();
		Allergens_Dietary_Pro_Form::setFormType(FormType::LICENSE);
		Allergens_Dietary_Pro_Form::getInstance()->showForm();
	}

	public function showallergens()
	{
		if (!class_exists('Allergens_Dietary_Pro_Form')) {
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/tables/allergen_show_allergen.php';
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/tabs/allergen_tabs.php';
		}
		Allergens_Dietary_Pro_Tabs::getInstance()->showtabs();
		$table = Allergens_Dietary_Pro_Show_Allergens::getInstance();
		$table->prepare_items();
		echo '<form method="POST" id="show_allergens_form" enctype="multipart/form-data">';
        wp_nonce_field('allergen_table_action', 'allergen_val');
		$table->search_box('Search', 'show_allergens');

		$table->display();
		echo '</form>';
	}

	public function info()
	{
		if (!class_exists('Allergens_Dietary_Pro_Form')) {
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/lists/allergen_info.php';
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/tabs/allergen_tabs.php';
		}
		Allergens_Dietary_Pro_Tabs::getInstance()->showtabs();
		Allergens_Dietary_Pro_Info::getInstance()->showInfo();
	}
}

// call the class and add the menus automatically
// $Allergens_Dietary_Pro_Plugin_Menu = Allergens_Dietary_Pro_Plugin_Menu::instance();