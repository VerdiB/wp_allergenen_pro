<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( 'I_Allergens_Dietary_Ictoria_Form' ) ) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/Iallergen_form.php';
}

if ( ! class_exists( 'Allergens_Dietary_Ictoria_License_Form' ) ) {
	require_once ALLERGENS_DIETARY_ICTORIA_DIRNAME . '/php/forms/allergen_form_license.php';
}

enum FormType {
	case ALLERGENS;
	case LICENSE;
}

/**
 * @class Allergens_Dietary_Ictoria_Form
 * @brief This class is a singleton strategy
 * that creates a form for the allergens and dietary restrictions plugin.
 * @author V.B.
 * @date 2-9-2024
 * @since 1.0.0
 */

class Allergens_Dietary_Ictoria_Form {
	private static ?self $_instance = null;
	private static FormType $_formType;
	private static I_Allergens_Dietary_Ictoria_Form $_formObject;

	private function __construct() {
		if ( FormType::ALLERGENS === self::$_formType ) {
			// self::$_formObject = new Allergens_Dietary_Ictoria_Allergen_Form();
			throw new Exception( 'FormType not yet supported/implemented' );
		}
		if ( FormType::LICENSE === self::$_formType ) {
			self::$_formObject = new Allergens_Dietary_Ictoria_License_Form();
		}
	}

	public static function getInstance() {
		if ( self::$_instance === null ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public static function setFormType( FormType $formType ) {
		self::$_formType = $formType;
	}

	public static function getFormType() {
		return self::$_formType;
	}

	public function showForm( string $allergenName = null ) {
		echo "<script>console.log('form added')</script>";
		if ( ! empty( $_POST['submit'] ) ) {
			self::$_formObject->submit( $_POST );
		}

		echo '<div class="allergens_form"><form action="" method="post" class="add_allergens_form">';
		self::$_formObject->showForm( $allergenName );
		echo '</form></div>';
	}
}

echo "<script>console.log('form added')</script>";
