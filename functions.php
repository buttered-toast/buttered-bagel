<?php defined('ABSPATH') || exit;

foreach ([
    'BUBA_THEME_DOMAIN'         => 'buba',
    'BUBA_THEME_DIRECTORY_NAME' => 'buttered-bagel',
    'BUBA_BDIR'                 => __DIR__,
    'BUBA_THEME_DIR'            => WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . 'buttered-bagel' . DIRECTORY_SEPARATOR,
    'BUBA_SITE_DEVELOPER'       => 'buttered_toast',
] as $name => $value) !defined($name) ? define($name, $value) : '';

foreach ([
    'vendor' . DIRECTORY_SEPARATOR . 'autoload.php',
    'core' . DIRECTORY_SEPARATOR . 'autoload.php',
] as $path) require BUBA_THEME_DIR . $path;

core\Bootstrap::init();