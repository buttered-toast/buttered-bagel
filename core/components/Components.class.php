<?php namespace core\components; defined('ABSPATH') || exit;

use Timber;
use core\components\Output;

class Components {
    static public function prepare ($context = [], $namespace = 'app\components\hierarchy\\') {
        if (!empty($context) && is_string($context)) $context = ($namespace . $context)::context();
        return new Output(array_merge($context, Timber::context()));
    }
}