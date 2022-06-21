<?php namespace app\http\builders; defined('ABSPATH') || exit;

use tools\http\methods\Get;
use tools\http\methods\Post;
use tools\http\methods\Put;
use tools\http\methods\Patch;
use tools\http\methods\Delete;
use tools\http\builders\Auth;

class Headers {
    private $headers = [];

    public function __construct ($headers) {
        $this->headers = $headers;
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
}