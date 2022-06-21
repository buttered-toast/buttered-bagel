<?php namespace tools\debug; defined('ABSPATH') || exit;

class Script {
    private $values;

    private $opening_tag = '<script>';

    private $closing_tag = '</script>';

    public function __construct ($values) {
        $this->values = $values;

        return $this;
    }

    private function loop ($type) {
        foreach ($this->values as $key => $value) {
            $value = json_encode($value);
            echo "const {$type}{$key} = {$value};";
            echo "console.log('{$type}{$key}');";
            echo "console.{$type}({$value});";
        }
    }

    private function output ($type) {
        echo $this->opening_tag;
        $this->loop($type);
        echo $this->closing_tag;
    }

    public function log () {
        $this->output('log');
    }

    public function table () {
        $this->output('table');
    }

    public function debug () {
        $this->output('debug');
    }

    public function warn () {
        $this->output('warn');
    }

    public function error () {
        $this->output('error');
    }
}