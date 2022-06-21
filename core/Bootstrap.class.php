<?php namespace core; defined('ABSPATH') || exit;

use app\Applications;

class Bootstrap extends Applications {
    // all classes to initialize
    static private $classes_init;

    // sets classes to initialize
    static private function setClassesInit () {
        self::$classes_init = [
            'new' => [
                '\Timber\Timber',
            ],
            'init' => [
                '\core\RemoveCoreFeatures',
                '\core\Enqueue',
                '\core\Register',
                '\core\Timber',
                '\core\Admin',
            ],
            'wp-cli' => [
                'components'   => '\core\wpcli\Components',
                'appHierarchy' => '\core\wpcli\AppHierarchy',
            ],
        ];
    }

    static private function setApplications () {
        foreach (['new', 'init',] as $type) {
            $app = "apps_{$type}";

            self::$classes_init[$type] = array_merge(self::$classes_init[$type], self::$$app);
        }
    }

    // initialize theme
    static public function init () {
        self::setClassesInit();
        self::setApplications();

        foreach (self::$classes_init as $type => $classes) {
            foreach($classes as $key => $class) {
                switch ($type) {
                    case 'new':  new $class();   break;
                    case 'init': $class::init(); break;
                    case 'wp-cli':
                        if (class_exists('WP_CLI')) \WP_CLI::add_command("buba {$key}", $class);
                        break;
                }
            }
        }
    }
}