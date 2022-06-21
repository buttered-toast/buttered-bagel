<?php namespace tools\http\builders; defined('ABSPATH') || exit;

use tools\http\methods\Get;
use tools\http\methods\Post;
use tools\http\methods\Put;
use tools\http\methods\Patch;
use tools\http\methods\Delete;
use tools\http\builders\Headers;

class Auth {
    private $secret = '';

    private $type = '';

    private $headers = [];

    public function __construct ($secret, $type) {
        $this->setProperties($secret, $type);
        $this->setAuth();
    }

    private function setProperties ($secret, $type) {
        $this->secret = $secret;
        $this->type   = $type;
    }

    private function setAuth () {
        switch ($this->type) {
            case 'token':
                $this->setBearerToken();
                break;
            case 'basic':
                $this->setBasicAuth();
                break;
        }
    }

    private function setBearerToken () {
        $this->headers[] = 'Authorization: Bearer ' . $this->secret;
    }

    private function setBasicAuth () {
        $this->headers[] = 'Authorization: Basic ' . base64_encode($this->secret);
    }

    public function get ($route, $query = []) {
        return new Get($route, [
            'query'   => $query,
            'headers' => $this->headers
        ]);
    }

    public function post ($route, $body = [], $type = 'json') {
        return new Post($route, [
            'body'             => $body,
            'type'             => $type,
            'curl_options'     => [
                'CURLOPT_POST' => true
            ],
            'headers'          => $this->headers
        ]);
    }

    public function put ($route, $body = [], $type = 'json') {
        return new Post($route, [
            'body'                      => $body,
            'type'                      => $type,
            'curl_options'              => [
                'CURLOPT_CUSTOMREQUEST' => 'PUT'
            ],
            'headers'                   => $this->headers
        ]);
    }

    public function patch ($route, $body = [], $type = 'json') {
        return new Patch($route, [
            'body'                      => $body,
            'type'                      => $type,
            'curl_options'              => [
                'CURLOPT_CUSTOMREQUEST' => 'PATCH'
            ],
            'headers'                   => $this->headers
        ]);
    }

    public function delete ($route, $query = []) {
        return new Delete($route, [
            'query'                     => $query,
            'curl_options'              => [
                'CURLOPT_CUSTOMREQUEST' => 'DELETE'
            ],
            'headers'                   => $this->headers
        ]);
    }

    public function headers ($headers) {
        $this->headers = array_merge($this->headers, $headers);

        return new headers($this->headers);
    }
}