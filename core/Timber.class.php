<?php namespace core; defined('ABSPATH') || exit;

class Timber extends \app\Timber {
    static public function init () {
        add_filter('timber_context', [self::class, 'addToContext']);

        add_filter('timber/twig', [self::class, 'addMethodsToTwig']);
    }

    static public function addToContext ($context) {
        return array_merge(self::context(), $context);
    }

    static public function addMethodsToTwig ($twig) {
        foreach (self::$twig_methods as $name => $location) {
            $twig->addFunction(new \Timber\Twig_Function($name, $location));
        }

        return $twig;
    }
}