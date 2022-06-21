<?php namespace app; defined('ABSPATH') || exit;

use app\components\Site;

class Timber extends Site {
    static protected $twig_methods = [
        'dd'     => '\tools\Debug::dd',
        'el'     => '\tools\Debug::el',
        'script' => '\tools\Debug::script',
    ];
}