<?php
use PHPUnit\Framework\TestCase;

require_once '/var/www/html/wp-content/plugins/allergens-dietary-ictoria/php/functions.php';

/**
 * @author V.B.
 * @covers Allergens_Dietary_Ictoria_Functions
 * @brief 
 * This class will be outdated when the plugin
 * does not include anything anymore from the functions.php file.
 * this is also part of the planning in the future.
 * 
 * @sa functions.php
 */

class FunctionsTest extends TestCase
{
    public function test_get_options()
    {
        $this->assertTrue(class_exists('Allergens_Dietary_Ictoria_Functions'));
    }
}

?>