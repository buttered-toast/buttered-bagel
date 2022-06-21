<?php namespace app\components; defined('ABSPATH') || exit;

use Timber;
use Timber\Menu;
use core\components\Base;

class Site extends Base {
    static private $context_methods = [
        'page',
        'menus',
        /*!wp-cli component create placeholder!*/
    ];

    static public function context () {
        self::prepareContext(self::$context_methods, 'global');
        return self::$context;
    }

    static protected function page () {
        return Timber::get_post();
    }

    static protected function menus () {
        return [
            'header' => new Menu('main-header-menu'),
            'footer' => new Menu('main-footer-menu'),
        ];
    }

    /*!wp-cli component create placeholder!*/
}