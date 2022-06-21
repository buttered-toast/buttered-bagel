<?php namespace tools\http\methods; defined('ABSPATH') || exit;

use tools\http\base\Curl;

class Get extends Curl {
    protected $route = '';
    
    protected $query = [];

    protected $headers = [];

    public function __construct ($route, $options = []) {
        $this->setProperties($route, $options);
        $this->prepareQuery();
        $this->initializeCurl();
        $this->setHeaders();
        $this->exec();
    }
    
    private function setProperties ($route, $options) {
        $this->route = $route;

        foreach ($options as $property => $value) {
            $this->$property = $value;
        }
    }

    private function prepareQuery () {
        if (!$this->query) return;
        
        $this->route .= '?' . http_build_query($this->query);
    }
}