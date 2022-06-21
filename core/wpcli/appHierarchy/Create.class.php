<?php namespace core\wpcli\appHierarchy; defined('ABSPATH') || exit;

use WP_CLI;
use core\wpcli\Base;
use core\wpcli\validation\Validator;

class Create extends Base {
    public function __construct ($args, $assoc_args) {
        if (!Validator::isValidappHierarchyCreate($args, $assoc_args)) return;

        self::$ticks = 7;

        self::startProgress('Creating new app hierarchy', self::$ticks);

        self::createClass($args[0]);
        self::createAssets($args[0]);
        self::createPhpTemlate($args[0]);
        self::createTwig($args[0]);
        self::addEnqueue($args[0]);
        self::tailwindConfig($args[0]);
        self::webpackConfig($args[0]);
    }

    static private function createClass ($class_name) {
        WP_CLI::line('');

        $path = BUBA_THEME_DIR . "app/components/hierarchy/{$class_name}.class.php";

        if (file_exists($path)) WP_CLI::error(__('Class already exists', BUBA_THEME_DOMAIN));
        else {
            $file = fopen($path, 'w');
            fwrite($file, <<<CLASS
            <?php namespace app\components\hierarchy; defined('ABSPATH') || exit;

            use app\components\Components;

            class {$class_name} extends Components {
                static private \$context_methods = [
                    /*!wp-cli component create placeholder!*/
                ];

                static public function context () {
                    self::prepareContext(self::\$context_methods, '{$class_name}');
                    
                    return self::\$context;
                }
            }
            CLASS);
            fclose($file);

            WP_CLI::success(__('App hierarchy class created', BUBA_THEME_DOMAIN));
        }


        self::tick();
    }

    static private function createAssets ($name) {
        WP_CLI::line('');

        $complete_path = BUBA_THEME_DIR . 'assets/hierarchy/' . $name;

        if (!mkdir($complete_path)) WP_CLI::error(__('Couldn\'t create assets', BUBA_THEME_DOMAIN));
        else {
            $files = [
                [
                    'path'    => $complete_path . "/{$name}.js",
                    'content' => <<<CONTENT
                    require('../../global/global');
                    /*!wp-cli component create placeholder!*/
                    CONTENT,
                ],
                [
                    'path'    => $complete_path . "/{$name}.scss",
                    'content' => <<<CONTENT
                    @import '../../global/global';
                    /*!wp-cli component create placeholder!*/
                    CONTENT,
                ],
                [
                    'path'    => $complete_path . '/index.php',
                    'content' => <<<CONTENT
                    <?php
                    CONTENT,
                ],
            ];
    
            foreach ($files as $file_data) {
                $file = fopen($file_data['path'], 'w');
                fwrite($file, $file_data['content']);
                fclose($file);
            }

            WP_CLI::success(__('Assets created', BUBA_THEME_DOMAIN));
        }

        self::tick();
    }

    static private function createPhpTemlate ($name) {
        $path = BUBA_THEME_DIR . "{$name}.php";
        
        WP_CLI::line('');

        if (file_exists($path)) WP_CLI::error(__('Root php template already exists', BUBA_THEME_DOMAIN));
        else {
            $file = fopen($path, 'w');
            fwrite($file, <<<PHP
            <?php defined('ABSPATH') || exit;
    
            // Template name: {$name}
    
            core\components\Components::prepare('{$name}')->render('hierarchy/{$name}.twig');
            PHP);
            fclose($file);

            WP_CLI::success(__('Root php template created', BUBA_THEME_DOMAIN));
        }

        self::tick();
    }

    static private function createTwig ($name) {
        $path = BUBA_THEME_DIR . "views/hierarchy/{$name}.twig";
        
        WP_CLI::line('');

        if (file_exists($path)) WP_CLI::error(__('Twig template already exists', BUBA_THEME_DOMAIN));
        else {
            $file = fopen($path, 'w');
            fwrite($file, <<<TWIG
            {% extends 'base.twig' %}

            {% block main_content_content %}
                {$name} main content block
            {% endblock main_content_content %}
            TWIG);
            fclose($file);

            WP_CLI::success(__('Twig template created', BUBA_THEME_DOMAIN));
        }

        self::tick();
    }

    static private function addEnqueue ($name) {
        $path = BUBA_THEME_DIR . 'app/Enqueue.class.php';

        WP_CLI::line('');

        if (!file_exists($path)) WP_CLI::error(__('App enqueue class is missing, this shouldn\'t even be possible', BUBA_THEME_DOMAIN));
        else {
            $file = fopen($path, 'r');
            $file_content = fread($file, filesize($path));

            $file_content = str_replace(
                self::$placeholder,
                <<<ENQUEUE
                [
                                    'condition' => is_page_template('{$name}.php'),
                                    'css' => [
                                        ['{$name}', 'public/css/{$name}.css'],
                                    ],
                                    'js' => [
                                        ['{$name}', 'public/js/{$name}.js'],
                                    ],
                                ],
                                /*!wp-cli component create placeholder!*/
                ENQUEUE,
                $file_content,
            );

            fclose($file);

            $file = fopen($path, 'w');
            fwrite($file, $file_content);
            fclose($file);

            WP_CLI::success(__('Assets added to enqueue', BUBA_THEME_DOMAIN));
        }

        self::tick();
    }

    static private function tailwindConfig ($name) {
        $path = BUBA_THEME_DIR . "tailwind/{$name}.config.js";

        WP_CLI::line('');

        if (file_exists($path)) WP_CLI::error(__('Tailwind config already exists', BUBA_THEME_DOMAIN));
        else {
            $file = fopen($path, 'w');
            fwrite($file, <<<CONFIG
            const config = require('./base.config.js');

            config.content.push('./views/hierarchy/{$name}.twig');

            module.exports = config;
            CONFIG);
            fclose($file);

            WP_CLI::success(__('Tailwind config created', BUBA_THEME_DOMAIN));
        }

        self::tick();
    }

    static private function webpackConfig ($name) {
        $path = BUBA_THEME_DIR . 'webpack.mix.js';

        WP_CLI::line('');

        if (!file_exists($path)) WP_CLI::error(__('What?! How is webpack.mix.js is missing...', BUBA_THEME_DOMAIN));
        else {
            $file = fopen($path, 'r');
            $file_content = fread($file, filesize($path));

            $file_content = str_replace(
                self::$placeholder,
                <<<CONFIG
                mix.js('./assets/hierarchy/{$name}/{$name}.js', 'public/js')
                    .extract()
                    .sass('./assets/hierarchy/{$name}/{$name}.scss', 'public/css')
                    .options({
                        postCss: [tailwindcss('./tailwind/{$name}.config.js')],
                    });

                /*!wp-cli component create placeholder!*/
                CONFIG,
                $file_content,
            );

            fclose($file);

            $file = fopen($path, 'w');
            fwrite($file, $file_content);
            fclose($file);

            WP_CLI::success(__('webpack config updated', BUBA_THEME_DOMAIN));
        }

        self::tick();
    }
}