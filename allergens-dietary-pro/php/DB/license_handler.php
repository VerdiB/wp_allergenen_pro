<?php

if (!defined('ABSPATH')) {
	exit;
}

if(!class_exists('Allergens_Dietary_Pro_License_Handler.php')){
	require_once ALLERGENS_DIETARY_FREE_DIRNAME . '/php/DB/license_handler.php';
}



class Allergens_Dietary_Pro_License_Handler
{
    protected function __construct()
    {
      if (licenseActivator === 1)
      {
          //test condition is always set to true
          if(true)
          {

          }
      }
      else
      {
          $this->licenseActivator();
      }

      $this->exceededLicenseHandler();
    }

    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    protected function getLicense()
    {

    }

    protected function licenseActivator()
    {

    }

    // Functie voor het handelen van een aantal edge-cases. 
    protected function exceededLicenseHandler()
    {

    }

}