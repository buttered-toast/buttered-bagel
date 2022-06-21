<?php namespace core\components; defined('ABSPATH') || exit;

class Base {
    static protected $context = [];

    static protected function prepareContext ($methods, $base_prop) {
        foreach ($methods as $res_prop => $method) {
            if (!method_exists(static::class, $method)) return;
            
            $res_prop = is_numeric($res_prop) ? $method : $res_prop;
            self::$context[$base_prop][$res_prop ?: $method] = static::$method();
        }
    }
}