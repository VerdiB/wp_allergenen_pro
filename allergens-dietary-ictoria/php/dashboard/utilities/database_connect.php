<?php
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Database_Connect
{
    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new IAM_Database_Connect();
            // self::populate_allergen_table();
        }
    }

}
IAM_Database_Connect::instance();
