<?php namespace tools\debug; defined('ABSPATH') || exit;

class DD {
    private $values;

    private $opening_tag = '<div dir="ltr"><pre>';

    private $closeing_tag = '</pre></div>';

    public function __construct ($values) {
        $this->values = $values;

        return $this;
    }

    private function loop ($type) {
        foreach ($this->values as $value) {
            $type($value);
            echo PHP_EOL . PHP_EOL;
        }
    }

    private function output ($type) {
        echo $this->opening_tag;
        $this->loop($type);
        echo $this->closeing_tag;
    }

    public function print () {
        $this->output('print_r');
    }
    
    public function export () {
        $this->output('var_export');
    }
    
    public function dump () {
        $this->output('var_dump');
    }
}