<?php namespace app\components\hierarchy; defined('ABSPATH') || exit;

use app\components\Components;

class FrontPage extends Components {
    static private $context_methods = [
        /*!wp-cli component create placeholder!*/
    ];

    static public function context () {
        self::prepareContext(self::$context_methods, 'FrontPage');
        
        return self::$context;
    }
}