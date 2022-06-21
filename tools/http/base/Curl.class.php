<?php namespace tools\http\base; defined('ABSPATH') || exit;

use tools\http\base\Response;

class Curl extends Response {
    protected $curl;

    protected $route = '';

    protected $body = [];

    protected $headers = [];

    protected $curl_options = [];

    protected function initializeCurl () {
        $this->curl = curl_init($this->route);
        
        curl_setopt($this->curl, CURLOPT_URL, $this->route);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HEADER, true);
    }

    protected function setCurlOptions () {
        if (!$this->curl_options) return;

        foreach ($this->curl_options as $option => $value) {
            curl_setopt($this->curl, constant($option), $value);
        }
    }

    protected function setHeaders () {
        if (!$this->headers) return;

        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
    }

    protected function setBody () {
        if (!$this->body) return;

        switch ($this->type) {
            case 'json':
                $this->body      = json_encode($this->body);
                $this->headers[] = 'Content-Type: application/json; charset=UTF-8';
                break;
        }

        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->body);
    }

    protected function exec () {
        $this->res    = curl_exec($this->curl);
        $this->server = curl_getinfo($this->curl);

        $this->extractResponse();

        curl_close($this->curl);
    }
}