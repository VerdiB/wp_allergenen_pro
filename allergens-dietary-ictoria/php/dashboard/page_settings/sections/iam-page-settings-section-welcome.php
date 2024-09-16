<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class IAM_Page_Settings_Section_Welcome extends IAM_Base_Section
{
    private static $field_name = '_' . 'field_name';

    public function __construct()
    {
        parent::__construct(
            strtolower(__CLASS__),
            __('Welcome Title in settings', 'text-domain'),
            'iam-settings-section-welcome'
        );

    }

    public function section_callback()
    {
        echo '<p>Section description here.</p>';
    }

}
