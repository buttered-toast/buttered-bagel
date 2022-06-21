<?php namespace app; defined('ABSPATH') || exit;

class Register {
    static protected $menus = [
        'main-header-menu' => 'Header menu',
        'main-footer-menu' => 'Footer menu',
    ];

    static protected $image_sizes = [
        ['bt-image-400', 400],
        ['bt-image-500', 500],
    ];

    static protected function prepareThemeSupport () {
        static::$theme_support = [
            ['menus'],
            ['post-thumbnails'],
            ['html5', ['script', 'style']],
            ['title-tag'],
            ['custom-logo'],
            [
                'args'      => ['woocommerce'],
                'condition' => class_exists('woocommerce'),
            ],
        ];
    }
}