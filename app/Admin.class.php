<?php namespace app; defined('ABSPATH') || exit;

class Admin {
    static protected $login_error_message = 'User details or password are incorrect';

    static protected $dashboard_copyright_text = 'Developed and maintained by %s';

    static protected $dashboard_copyright_text_vsprintf_args = [
        BUBA_SITE_DEVELOPER,
    ];

    static public function init () {
        
    }
}