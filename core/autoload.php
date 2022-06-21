<?php defined('ABSPATH') || exit;

spl_autoload_register(function ($class) {
    $file = str_replace('\\', '/', BUBA_THEME_DIR . $class . '.class.php');
    if (file_exists($file)) require $file;
});