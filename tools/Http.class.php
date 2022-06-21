<?php namespace tools; defined('ABSPATH') || exit;

use tools\http\methods\Get;
use tools\http\methods\Post;
use tools\http\methods\Put;
use tools\http\methods\Patch;
use tools\http\methods\Delete;
use tools\http\builders\Auth;
use tools\http\builders\Headers;

class Http {
    static public function get ($route, $query = []) {
        return new Get($route, [
            'query' => $query
        ]);
    }
    
    static public function post ($route, $body = [], $type = 'json') {
        return new Post($route, [
            'body'             => $body,
            'type'             => $type,
            'curl_options'     => [
                'CURLOPT_POST' => true
            ]
        ]);
    }

    static public function put ($route, $body = [], $type = 'json') {
        return new Put($route, [
            'body'                      => $body,
            'type'                      => $type,
            'curl_options'              => [
                'CURLOPT_CUSTOMREQUEST' => 'PUT'
            ],
        ]);
    }

    static public function patch ($route, $body = [], $type = 'json') {
        return new Patch($route, [
            'body'                      => $body,
            'type'                      => $type,
            'curl_options'              => [
                'CURLOPT_CUSTOMREQUEST' => 'PATCH'
            ]
        ]);
    }

    static public function delete ($route, $query = []) {
        return new Delete($route, [
            'query'                     => $query,
            'curl_options'              => [
                'CURLOPT_CUSTOMREQUEST' => 'DELETE'
            ]
        ]);
    }

    static public function tokenAuth ($token) {
        return new Auth($token, 'token');
    }

    static public function basicAuth ($user, $secret) {
        return new Auth($user . ':' . $secret, 'basic');
    }

    static public function headers ($headers) {
        return new headers($headers);
    }
}