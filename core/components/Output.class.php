<?php namespace core\components; defined('ABSPATH') || exit;

use Timber;

class Output {
    private $context;

    public function __construct ($context) {
        $this->context = $context;
    }

    public function render ($twig_path) {
        Timber::render($twig_path, $this->context);
    }

    public function compile ($twig_path) {
        return Timber::compile($twig_path, $this->context);
    }
}