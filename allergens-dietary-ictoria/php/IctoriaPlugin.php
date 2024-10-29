<?php
// exit if user can access this file directly
if (! defined('ABSPATH')) {
	exit;
}

class IctoriaPlugin
{

	private static $instance = null;

	// Moet nog worden veranderd naar de goede image. Image MOET 24x24 pixels zijn.
	private const ictoria_plugin_icon = 'allergens_soya.png'; 

	/***
	 * Main instance
	 *
	 * @staticvar   array   $instance
	 * @return      The one true instance
	 */


	public static function instance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new IctoriaPlugin();
		}
		return self::$instance;
	}

	private function __construct()
	{
		add_action(
			'admin_menu',
			array(
				$this,
				'addMyAdminMenu',
			)
		);
		// return self::addMyAdminMenu();
	}

	public function addMyAdminMenu()
	{

		add_menu_page(
			__('Allergens and Dietary', 'allergens-dietary-ictoria'),
			'Ictoria',
			'manage_options',
			'allergens-dietary-options',
			array(
				$this,
				'myAdminPage',
			),
			plugins_url('assets/icons/' . self::ictoria_plugin_icon, ALLERGENS_DIETARY_ICTORIA_FILE)
		);

		add_submenu_page(
			'allergens-dietary-options',
			__('License key', 'allergens-dietary-ictoria'),
			__('License key', 'allergens-dietary-ictoria'),
			'manage_options',
			'allergens-dietary-license',
			array(
				$this,
				'licenseform',
			)
		);

		add_submenu_page(
			'allergens-dietary-options',
			__('Add allergen', 'allergens-dietary-ictoria'),
			__('Add allergen', 'allergens-dietary-ictoria'),
			'manage_options',
			'allergens-dietary-add-allergen',
			array(
				$this,
				'addallergens',
			)
		);

		add_submenu_page(
			'allergens-dietary-options',
			__('Info', 'allergens-dietary-ictoria'),
			__('Info', 'allergens-dietary-ictoria'),
			'manage_options',
			'allergens-dietary-Info',
			array(
				$this,
				'Info',
			)
		);

		add_submenu_page(
			'allergens-dietary-options',
			__('Update allergen', 'allergens-dietary-ictoria'),
			__('Update allergen', 'allergens-dietary-ictoria'),
			'manage_options',
			'allergens-dietary-update-allergen',
			array(
				$this,
				'updateallergens',
			)
		);
		add_submenu_page(
			'allergens-dietary-options',
			__('Show allergens', 'allergens-dietary-ictoria'),
			__('Show allergens', 'allergens-dietary-ictoria'),
			'manage_options',
			'allergens-dietary-show-allergens',
			array(
				$this,
				'showallergens',
			),
		);
	}

	public function myAdminPage()
	{
		// echo the HTML here ......
	}

	public function licenseForm()
	{
		if (! class_exists('Allergens_Dietary_Ictoria_Form')) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/allergen_form.php';
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/tabs/allergen_tabs.php';
		}
		Allergens_Dietary_Ictoria_Tabs::getInstance()->showtabs();
		Allergens_Dietary_Ictoria_Form::setFormType(FormType::LICENSE);
		Allergens_Dietary_Ictoria_Form::getInstance()->showForm();
	}

	public function addallergens()
	{
		if (! class_exists('Allergens_Dietary_Ictoria_Form') && ! class_exists('Allergens_Dietary_Ictoria_Tabs')) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/allergen_form.php';
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/tabs/allergen_tabs.php';
		}
		Allergens_Dietary_Ictoria_Tabs::getInstance()->showtabs();
		Allergens_Dietary_Ictoria_Form::setFormType(FormType::ALLERGENS);
		Allergens_Dietary_Ictoria_Form::getInstance()->showForm();
	}

	public function updateallergens()
	{
		if (! class_exists('Allergens_Dietary_Ictoria_Form')) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/allergen_form.php';
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/tabs/allergen_tabs.php';
		}
		Allergens_Dietary_Ictoria_Tabs::getInstance()->showtabs();
		Allergens_Dietary_Ictoria_Form::setFormType(FormType::ALLERGENS);
		Allergens_Dietary_Ictoria_Form::getInstance()->showForm();
	}

	public function showallergens()
	{
		if (! class_exists('Allergens_Dietary_Ictoria_Show_Allergens')) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/tables/allergen_show_allergen.php';
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/tabs/allergen_tabs.php';
		}
		Allergens_Dietary_Ictoria_Tabs::getInstance()->showtabs();
		Allergens_Dietary_Ictoria_Show_Allergens::getInstance()->table_page();
	}

	public function info()
	{
		if (! class_exists('Allergens_Dietary_Ictoria_Info')) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/lists/allergen_info.php';
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/tabs/allergen_tabs.php';
		}
		Allergens_Dietary_Ictoria_Tabs::getInstance()->showtabs();
		Allergens_Dietary_Ictoria_Info::getInstance()->showInfo();
	}
}

// call the class and add the menus automatically
// $IctoriaPlugin = IctoriaPlugin::instance();