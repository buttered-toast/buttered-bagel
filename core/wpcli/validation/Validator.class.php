<?php namespace core\wpcli\validation; defined('ABSPATH') || exit;

use WP_CLI;

class Validator extends Messages {
    static public function isValidComponentsCreate ($args, $assoc_args) {
        if (!self::hasArgs($args, self::$components_create_no_args)) return false;
        elseif (!self::isArgsCountEquals($args, 1, self::$components_create_too_many_args)) return false;

        if ((empty($assoc_args) || (!empty($assoc_args) && empty($assoc_args['child']) && empty($assoc_args['c']))) && self::isComponentExists($args)) return false;
        elseif (!empty($assoc_args) && (!empty($assoc_args['child']) || !empty($assoc_args['c'])) && self::isComponentExists($args, true)) return false;

        if (!empty($assoc_args) && (!empty($assoc_args['hierarchy']) || !empty($assoc_args['h'])) && !self::isHierarchyExists($assoc_args)) return false;

        self::confirmComponentCreate($args, $assoc_args);

        return true;
    }

    static public function isValidappHierarchyCreate ($args, $assoc_args) {
        if (!self::hasArgs($args, self::$app_hierarchy_create_no_args)) return false;
        elseif (!self::isArgsCountEquals($args, 1, self::$app_hierarchy_create_too_many_args)) return false;
        elseif (!self::isAppHierarchyexist($args[0])) return false;

        self::confirmAppHierarchyCreate($args, $assoc_args);

        return true;
    }

    static private function isAppHierarchyexist ($class_name) {
        if (!file_exists(BUBA_THEME_DIR . "app/components/hierarchy/{$class_name}.class.php")) return true;

        Output::coloredError(
            Prepare::coloredCommands(
                __('The app hierarchy class %s already exists', BUBA_THEME_DOMAIN),
                [
                    $class_name,
                ],
            ),
        );

        return false;
    }

    static private function hasArgs ($args, $message) {
        if (!empty($args)) return true;

        Output::coloredError(
            Prepare::coloredCommands(
                __('The %s command expects a name argument for the component you want to create. Example: %s', BUBA_THEME_DOMAIN),
                $message,
            ),
        );

        return false;
    }

    static private function isArgsCountEquals ($args, $amount, $message) {
        if (($args_count = count($args)) == $amount) return true;

        $message[] = $args_count;

        Output::coloredError(
            Prepare::coloredCommands(
                __('The %s command expects one name argument, you passed %s', BUBA_THEME_DOMAIN),
                $message,
            ),
        );

        return false;
    }

    static private function isHierarchyExists ($assoc_args) {
        $class_names = array_unique(explode(',', ($assoc_args['hierarchy'] ?? $assoc_args['h'])));

        foreach ($class_names as $class_name) {
            if (file_exists(BUBA_THEME_DIR . "app/components/hierarchy/{$class_name}.class.php")) continue;

            Output::coloredError(
                Prepare::coloredCommands(
                    __('The %s class doesn\'t exist in %s', BUBA_THEME_DOMAIN),
                    [
                        $class_name,
                        'app/components/hierarchy/',
                    ],
                ),
            );

            return false;
        }

        return true;
    }

    static private function isComponentExists ($args, $is_child = false) {
        switch ($is_child) {
            case true:
                if (!file_exists(BUBA_THEME_DIR . "views/components/component/{$args[0]}.twig")) return false;

                Output::coloredError(
                    Prepare::coloredCommands(
                        __('The %s component already exists in %s', BUBA_THEME_DOMAIN),
                        [
                            $args[0],
                            'views/components/component/',
                        ],
                    ),
                );

                return true;

                break;
            case false:
                if (!file_exists(BUBA_THEME_DIR . "views/components/{$args[0]}.twig")) return false;

                Output::coloredError(
                    Prepare::coloredCommands(
                        __('The %s component already exists in %s', BUBA_THEME_DOMAIN),
                        [
                            $args[0],
                            'views/components/',
                        ],
                    ),
                );

                return true;
                break;
        }
    }

    static private function confirmComponentCreate ($args, $assoc_args) {
        $question = __("You are about to create a new component named '{$args[0]}'", BUBA_THEME_DOMAIN);

        if (!empty($assoc_args['child']) || !empty($assoc_args['c'])) $question .= sprintf(__(' in \'%s\'', BUBA_THEME_DOMAIN), 'views/components/component/');
        else $question .= sprintf(__(' in \'%s\'', BUBA_THEME_DOMAIN), 'views/components/');

        if (!empty($assoc_args['hierarchy']) || !empty($assoc_args['h'])) $question .= sprintf(__(' for hierarchy class \'%s\'', BUBA_THEME_DOMAIN), $assoc_args['hierarchy'] ?? $assoc_args['h']);

        WP_CLI::confirm($question);
    }

    static private function confirmAppHierarchyCreate ($args, $assoc_args) {
        WP_CLI::confirm(sprintf(__('You are about to create a new app hierarchy named %s', BUBA_THEME_DOMAIN), $args[0]));
    }
}