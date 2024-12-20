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
		// $this->addMyAdminMenu();
		// parent::__construct();
		remove_action('admin_menu', ['Allergens_Dietary_Plugin_Menu', 'addMyAdminMenu'], 6);
		add_action('admin_menu', [$this, 'addMyAdminMenu'], 10);

		// return self::addMyAdminMenu();
	}

	// #[\Override]
	public function addMyAdminMenu()
	{
		error_log("asdasdasdasdads");

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

	public function licenseForm()
	{
		if (!class_exists('Allergens_Dietary_Pro_Form')) {
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/forms/allergen_form.php';
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/tabs/allergen_tabs.php';
		}
		Allergens_Dietary_Pro_Tabs::getInstance()->showtabs();
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
		$singleton = Allergens_Dietary_Pro_Show_Allergens::getInstance();
		$singleton->table_page();
	}

	public function info()
	{
		if (!class_exists('Allergens_Dietary_Pro_Form')) {
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/forms/allergen_form.php';
			require_once ALLERGENS_DIETARY_PRO_DIRNAME . '/php/tabs/allergen_tabs.php';
		}
		Allergens_Dietary_Pro_Tabs::getInstance()->showtabs();
		error_log("daea");
		Allergens_Dietary_Info::getInstance()->showInfo();
	}
}

// call the class and add the menus automatically
// $Allergens_Dietary_Pro_Plugin_Menu = Allergens_Dietary_Pro_Plugin_Menu::instance();