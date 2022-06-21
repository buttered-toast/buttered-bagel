<?php namespace tools\http\methods; defined('ABSPATH') || exit;

use tools\http\base\Curl;

class Put extends Curl {
    protected $route = '';

    protected $body = [];

    protected $type = '';

    protected $headers = [];

    protected $curl_options = [];

    public function __construct ($route, $options = []) {
        $this->setProperties($route, $options);
        $this->initializeCurl();
        $this->setCurlOptions();
        $this->setBody();
        $this->setHeaders();
        $this->exec();
    }

    private function setProperties ($route, $options) {
        $this->route = $route;
        
        foreach ($options as $property => $value) {
            $this->$property = $value;
        }
    }
}