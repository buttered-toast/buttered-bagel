<?php namespace core; defined('ABSPATH') || exit;

use tools\Debug;

class Enqueue extends \app\Enqueue {
    static protected $assets;

    static private $template_directory_uri;

    static public function init () {
        self::$template_directory_uri = get_template_directory_uri() . '/';

        if (current_user_can('manage_options')) self::$version = time(); 

        add_action('wp_enqueue_scripts', function () {
            self::setAssets();
            self::enqueueAssets();
        });
    }

    static private function enqueueAssets () {
        self::enqueueWebpackFiles();

        foreach (static::$assets as $assets_block) {
            foreach ($assets_block as $asset_block) {
                if (isset($asset_block['condition']) && !$asset_block['condition']) continue;

                if (!empty($asset_block['css'])) self::enqueueCss($asset_block['css']);
                if (!empty($asset_block['js']))  self::enqueueJs($asset_block['js']);

                if (isset($asset_block['condition'])) break;
            }
        }
    }

    static private function enqueueWebpackFiles () {
        wp_enqueue_script(BUBA_THEME_DOMAIN .'-manifest', self::$template_directory_uri . 'public/js/manifest.js' . '?v=' . self::$version, [], false, true);
        if (file_exists(BUBA_THEME_DIR . 'public/js/vendor.js')) wp_enqueue_script(BUBA_THEME_DOMAIN . '-vendor', self::$template_directory_uri . 'public/js/vendor.js' . '?v=' . self::$version, [], false, true);
    }

    static private function enqueueCss ($css_files) {
        foreach ($css_files as $file) {
            call_user_func_array('wp_enqueue_style', self::prepareFile($file, 'css'));
        }
    }

    static private function enqueueJs ($js_files) {
        foreach ($js_files as $file) {
            call_user_func_array('wp_enqueue_script', self::prepareFile($file, 'js'));
        }
    }

    static private function prepareFile ($file, $type) {
        if (empty($file[1])) return $file;
        if (strpos($file[1], 'http') === false) $file[1] = self::$template_directory_uri . $file[1] . '?v=' . self::$version;

        $file[2] = !empty($file[2]) ? $file[2] : [];
        $file[3] = !empty($file[3]) ? $file[3] : false;

        if ($type === 'js') $file[4] = (!empty($file[4]) || isset($file[4]) && $file[4] === false) ? $file[4] : self::$js_in_footer;

        return $file;
    }
}