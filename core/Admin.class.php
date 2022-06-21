<?php namespace core; defined('ABSPATH') || exit;

class Admin extends \app\Admin {
    static public function init () {
        if (method_exists('\app\Admin', 'init')) parent::init();

        add_filter('login_errors', [self::class, 'modifyLoginErrors']);

        add_filter('admin_footer_text', [self::class, 'modifyDashboardCopyright']);
    }

    static public function modifyLoginErrors ($error) {
        if (empty(self::$login_error_message)) return $error;
        return esc_html__(self::$login_error_message, BUBA_THEME_DOMAIN);
    }

    static public function modifyDashboardCopyright ($text) {
        if (empty(self::$dashboard_copyright_text)) return $text;
        return vsprintf(esc_html__(self::$dashboard_copyright_text, BUBA_THEME_DOMAIN), self::$dashboard_copyright_text_vsprintf_args);
    }
}