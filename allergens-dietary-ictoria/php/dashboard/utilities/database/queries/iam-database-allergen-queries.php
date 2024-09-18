<?php
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Database_Allergen_Queries
{
    private static $_instance = null;

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new IAM_Database_Allergen_Queries();
        }
    }

    private $wpdb;
    public static $table_base = this::$wpdb->prefix . 'allergens_dietary_ictoria';
    
    public static $tables = [
      $table_base . '_' . 'allergens'
      $table_base . '_' . 'allergen_attachments'
      $table_base . '_' . 'attachments'
    ];
    public static $sql_queries = [];


    public function __construct(){
        global $wpdb;
        $this->wpdb = $wpdb;
    }
}
