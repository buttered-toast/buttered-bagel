<?php namespace app; defined('ABSPATH') || exit;

class Enqueue {
    static protected $version = '1.0.0';

    static protected $js_in_footer = true;

    static protected function setAssets () {
        static::$assets = [
            [
                [
                    'condition' => is_front_page(),
                    'css' => [
                        ['FrontPage', 'public/css/FrontPage.css'],
                    ],
                    'js' => [
                        ['FrontPage', 'public/js/FrontPage.js'],
                    ],
                ],
                /*!wp-cli component create placeholder!*/
            ],
        ];
    }
}