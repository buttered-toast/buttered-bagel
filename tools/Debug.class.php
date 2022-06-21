<?php namespace tools; defined('ABSPATH') || exit;

use tools\debug\DD;
use tools\debug\EL;
use tools\debug\Script;

class Debug {
    static public function dd (...$values) {
        return new DD($values);
    }
    
    static public function el (...$values) {
        return new EL($values);
    }
    
    static public function script (...$values) {
        return new Script($values);
    }
}