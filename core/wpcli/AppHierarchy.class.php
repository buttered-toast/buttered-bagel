<?php namespace core\wpcli; defined('ABSPATH') || exit;

use core\wpcli\appHierarchy\Create;

class AppHierarchy {
    public function create ($args, $assoc_args) {
        new Create($args, $assoc_args);
    }
}