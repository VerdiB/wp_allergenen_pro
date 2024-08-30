<?php

use PHPUnit\Framework\TestCase;

require_once '/var/www/html/wp-content/plugins/allergens-dietary-ictoria/php/wc_integration.php';

/**
 * @author V.B.
 * @covers Allergens_Dietary_Ictoria_Wc_Integration_Settings
 * @brief
 * Unit tests class for the Woocommerce integration class.
 * @version 1.0
 * @date 2024-08-30 modified: 2024-08-30
 * @modified by V.B.
 */

class WcIntegrationTest extends TestCase
{

    public function test_init_form_fields_method_exists()
    {
        $this->assertTrue(method_exists('Allergens_Dietary_Ictoria_Wc_Integration_Settings', 'init_form_fields'));
    }

    public function test_init_form_fields_is_private()
    {
        $reflector = new ReflectionClass('Allergens_Dietary_Ictoria_Wc_Integration_Settings');
        $method = $reflector->getMethod('init_form_fields');
        $this->assertTrue($method->isPrivate());
    }

    public function test_generate_allergensdietary_html_method_exists()
    {
        $this->assertTrue(method_exists('Allergens_Dietary_Ictoria_Wc_Integration_Settings', 'generate_allergensdietary_html'));
    }

    public function test_generate_allergensdietary_html_is_public()
    {
        $reflector = new ReflectionClass('Allergens_Dietary_Ictoria_Wc_Integration_Settings');
        $method = $reflector->getMethod('generate_allergensdietary_html');
        $this->assertTrue($method->isPublic());
    }

    public function test_generate_allergensdietary_html_returns_string()
    {
        $reflector = new ReflectionClass('Allergens_Dietary_Ictoria_Wc_Integration_Settings');
        $method = $reflector->getMethod('generate_allergensdietary_html');
        $this->assertIsString($method->invoke(new Allergens_Dietary_Ictoria_Wc_Integration_Settings()));
    }

    public function test_plugin_action_links_method_exists()
    {
        $this->assertTrue(method_exists('Allergens_Dietary_Ictoria_Wc_Integration_Settings', 'plugin_action_links'));
    }

    public function test_plugin_action_links_is_public()
    {
        $reflector = new ReflectionClass('Allergens_Dietary_Ictoria_Wc_Integration_Settings');
        $method = $reflector->getMethod('plugin_action_links');
        $this->assertTrue($method->isPublic());
    }

    public function test_plugin_action_links_returns_array()
    {
        $reflector = new ReflectionClass('Allergens_Dietary_Ictoria_Wc_Integration_Settings');
        $method = $reflector->getMethod('plugin_action_links');
        $this->assertIsArray($method->invoke(new Allergens_Dietary_Ictoria_Wc_Integration_Settings(), []));
    }


}
 ?>