<?php namespace core; defined('ABSPATH') || exit;

use tools\Debug;

class Register extends \app\Register {
    static protected $theme_support;

    static public function init () {
        self::menus();
        self::themeSupport();
        self::imageSizes();

        add_filter('upload_mimes', [self::class, 'svgSupport']);

        add_filter('wp_check_filetype_and_ext', [self::class, 'svgsUploadCheck'], 10, 4);
    }

    static private function menus () {
        if (empty(self::$menus)) return;

        self::prepareMenus();

        register_nav_menus(self::$menus);
    }

    static protected function prepareMenus () {
        foreach (self::$menus as &$name) {
            $name = esc_html__($name, BUBA_THEME_DOMAIN);
        }
    }

    static private function themeSupport () {
        self::prepareThemeSupport();

        if (empty(self::$theme_support)) return;

        foreach (self::$theme_support as $support) {
            if (isset($support['condition']) && $support['condition'] === false) continue;
            if (!empty($support['args'])) {
                if (empty($support['event'])) call_user_func_array('add_theme_support', $support['args']);
                else {
                    add_action($support['event'], function () use ($support) {
                        call_user_func_array('add_theme_support', $support['args']);
                    });
                }
            }
            else call_user_func_array('add_theme_support', $support);
        }
    }

    static private function imageSizes () {
        if (empty(self::$image_sizes)) return;

        foreach (self::$image_sizes as $image_size) {
            call_user_func_array('add_image_size', $image_size);
        }
    }

    static public function svgSupport ($mime_types) {
        if (!current_user_can('administrator')) return $mime_types;

        $mime_types['svg'] 	= 'image/svg+xml';
        $mime_types['svgz'] = 'image/svg+xml';

        return $mime_types;
    }

    static public function svgsUploadCheck ($checked, $file, $filename, $mimes) {
        if (!$checked['type']) {
            $check_filetype	 = wp_check_filetype($filename, $mimes);
            $ext			 = $check_filetype['ext'];
            $type			 = $check_filetype['type'];
            $proper_filename = $filename;

            if ($type && 0 === strpos($type, 'image/') && $ext !== 'svg') $ext = $type = false;

            $checked = compact('ext', 'type', 'proper_filename');
        }
        
        return $checked;
    }
}