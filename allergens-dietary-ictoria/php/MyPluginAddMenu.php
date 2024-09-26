<?php
// exit if user can access this file directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MyPluginAddMenu {

	private static $instance = null;

	/***
	 * Main instance
	 *
	 * @staticvar   array   $instance
	 * @return      The one true instance
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new MyPluginAddMenu();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action(
			'admin_menu',
			array(
				$this,
				'addMyAdminMenu',
			)
		);
		// return self::addMyAdminMenu();
	}

	public function addMyAdminMenu() {
		
		add_menu_page(
			__( 'Allergens and Dietary', 'allergens-dietary-ictoria' ),
			'Ictoria',
			'manage_options',
			'allergens-dietary-options',
			array($this, 'myAdminPage')
		);

		add_submenu_page(
			'allergens-dietary-options',
			__( 'License key', 'allergens-dietary-ictoria' ),
			__( 'License key', 'allergens-dietary-ictoria' ),
			'manage_options',
			'allergens-dietary-license',
			array($this, 'licenseForm')
		);

		add_submenu_page(
			'allergens-dietary-options',
			__( 'Add allergen', 'allergens-dietary-ictoria' ),
			__( 'Add allergen', 'allergens-dietary-ictoria' ),
			'manage_options',
			'allergens-dietary-add-allergen',
			array($this, 'addallergens')
		);

		add_submenu_page(
			'allergens-dietary-options',
			__( 'Update allergen', 'allergens-dietary-ictoria' ),
			__( 'Update allergen', 'allergens-dietary-ictoria' ),
			'manage_options',
			'allergens-dietary-update-allergen',
			array($this, 'updateallergens')
		);
	}
	

	public function myAdminPage() {
		Allergen_Icon_Manager::display_allergen_icon_form();
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			Allergen_Icon_Manager::update_allergen_icons();
		}
	}

	public function licenseForm() {
		if ( ! class_exists( 'Allergens_Dietary_Ictoria_Form' ) ) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/allergen_form.php';
		}
		Allergens_Dietary_Ictoria_Form::setFormType( FormType::LICENSE );
		Allergens_Dietary_Ictoria_Form::getInstance()->showForm();
	}

	public function addallergens() {
		if ( ! class_exists( 'Allergens_Dietary_Ictoria_Form' ) ) {
			require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/allergen_form.php';
		}
		Allergens_Dietary_Ictoria_Form::setFormType( FormType::ALLERGENS );
		Allergens_Dietary_Ictoria_Form::getInstance()->showForm();
	}

	public function updateallergens() {
		global $wpdb;
		$table_name_allergy = $wpdb->prefix . 'allergens_dietary_ictoria_allergy';
		$table_name_attachment = $wpdb->prefix . 'allergens_dietary_ictoria_allergy_attachment';
	
		$allergens = $wpdb->get_results("SELECT a.allergy_name, a.allergy_description, at.attachment_name
										  FROM $table_name_allergy AS a
										  LEFT JOIN $table_name_attachment AS at ON a.allergy_name = at.allergy_name");
		?>
		<h1><?php _e('Update Allergen Icons', 'allergens-dietary-ictoria'); ?></h1>
		<form method="POST" enctype="multipart/form-data">
			<?php foreach ($allergens as $allergen) : ?>
				<div>
					<h2><?php echo esc_html($allergen->allergy_name); ?></h2>
					<p><?php echo esc_html($allergen->allergy_description); ?></p>
					<label for="allergen_icon_<?php echo esc_attr($allergen->allergy_name); ?>">
						<?php _e('Choose an icon for', 'allergens-dietary-ictoria'); ?> <?php echo esc_html($allergen->allergy_name); ?>:
					</label>
					<input type="file" name="allergen_icon[<?php echo esc_attr($allergen->allergy_name); ?>]" id="allergen_icon_<?php echo esc_attr($allergen->allergy_name); ?>">
					<span>
						<?php echo $allergen->attachment_name ? esc_html($allergen->attachment_name) : 'No file chosen'; ?>
					</span>
				</div>
			<?php endforeach; ?>
			<input type="submit" value="<?php _e('Update Icons', 'allergens-dietary-ictoria'); ?>">
		</form>
		<?php
	
		// Verwerk de POST-aanroep
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			Allergen_Icon_Manager::update_allergen_icons();
		}
	}
}	

// call the class and add the menus automatically
// $MyPluginAddMenu = MyPluginAddMenu::instance();
