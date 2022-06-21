<?php namespace core\wpcli\components; defined('ABSPATH') || exit;

use WP_CLI;
use core\wpcli\Base;
use core\wpcli\validation\Validator;

class Create extends Base {
    static private $component_name;

    static private $is_child = false;

    public function __construct ($args, $assoc_args) {
        if (!Validator::isValidComponentsCreate($args, $assoc_args)) return;

        self::createComponent($args, $assoc_args);
    }

    static private function createComponent ($args, $assoc_args) {
        self::startProgress('Creating new component', self::ticksCalculator($assoc_args));

        self::createTwig($args[0], isset($assoc_args['child']) || isset($assoc_args['c']));
        self::createAssets($args[0]);
        self::createMethod($args[0]);
        self::connectToHierarchy($args[0], $assoc_args);
    }

    static private function ticksCalculator ($assoc_args) {
        self::$ticks = 3;

        if (!empty($assoc_args['hierarchy']) || !empty($assoc_args['h'])) self::$ticks++;

        return self::$ticks;
    }

    static private function createTwig ($name, $is_child = false) {
        $path = 'views/components/';

        if ($is_child) $path .= 'component/';

        WP_CLI::line('');

        if (!touch(BUBA_THEME_DIR . $path . "{$name}.twig")) WP_CLI::error(__('Couldn\'t create component', BUBA_THEME_DOMAIN));
        else WP_CLI::success(__('Component created', BUBA_THEME_DOMAIN));

        self::tick();
    }

    static private function createAssets ($name) {
        $path          = 'assets/components/';
        $complete_path = BUBA_THEME_DIR . $path . $name;

        WP_CLI::line('');

        if (!mkdir($complete_path)) WP_CLI::error(__('Couldn\'t create assets', BUBA_THEME_DOMAIN));
        else {
            touch($complete_path . '/index.php');
            touch($complete_path . "/{$name}.scss");
            touch($complete_path . "/{$name}.js");

            WP_CLI::success(__('Assets created', BUBA_THEME_DOMAIN));
        }

        self::tick();
    }

    static private function createMethod ($name) {
        $path = BUBA_THEME_DIR . 'app/components/Components.class.php';

        WP_CLI::line('');

        if (!file_exists($path)) WP_CLI::error(__('Components.class.php doesn\'t exist', BUBA_THEME_DOMAIN));
        else {
            $placeholder = self::$placeholder;

            $components_class = fopen($path, 'r');
            $components_class_content = fread($components_class, filesize($path));

            $components_class_content = str_replace(
                $placeholder,
                <<<METHOD
                static protected function {$name} () {
                        return '{$name}';
                    }

                    {$placeholder}
                METHOD,
                $components_class_content,
            );

            fclose($components_class);

            $components_class = fopen($path, 'w');
            fwrite($components_class, $components_class_content);
            fclose($components_class);
        }

        WP_CLI::success(__('Created component method', BUBA_THEME_DOMAIN));

        self::tick();
    }

    static private function connectToHierarchy ($name, $assoc_args) {
        if (empty($assoc_args['hierarchy']) && empty($assoc_args['h'])) return;

        WP_CLI::line('');

        $class_names = array_unique(explode(',', ($assoc_args['hierarchy'] ?? $assoc_args['h'])));

        foreach ($class_names as $class_name) {
            if (!file_exists(BUBA_THEME_DIR . "app/components/hierarchy/{$class_name}.class.php")) {
                WP_CLI::error(sprintf(__('Hierarchy class %s doesn\'t exist', BUBA_THEME_DOMAIN), $class_name));
                continue;
            }

            self::writeToHierarchyFiles($class_name, $name, $assoc_args);
        }

        WP_CLI::success(__('Connected to hierarchy', BUBA_THEME_DOMAIN));

        self::tick();
    }

    static private function writeToHierarchyFiles ($class_name, $name, $assoc_args) {
        $placeholder = self::$placeholder;

        $files = [
            [
                'path'    => BUBA_THEME_DIR . "app/components/hierarchy/{$class_name}.class.php",
                'content' => <<<CLASS
                '{$name}',
                        $placeholder
                CLASS,
            ],
            [
                'path'    => BUBA_THEME_DIR . "assets/hierarchy/{$class_name}/{$class_name}.js",
                'content' => <<<CLASS
                require('../../components/{$name}/{$name}.js');
                $placeholder
                CLASS,
            ],
            [
                'path'    => BUBA_THEME_DIR . "assets/hierarchy/{$class_name}/{$class_name}.scss",
                'content' => <<<CLASS
                @import '../../components/{$name}/{$name}.scss';
                $placeholder
                CLASS,
            ],
        ];

        foreach ($files as $file_data) {
            $file = fopen($file_data['path'], 'r');
            $file_content = fread($file, filesize($file_data['path']));

            $file_content = str_replace(
                self::$placeholder,
                $file_data['content'],
                $file_content,
            );

            fclose($file);

            $file = fopen($file_data['path'], 'w');
            fwrite($file, $file_content);
            fclose($file);
        }
    }
}