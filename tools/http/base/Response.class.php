<?php namespace tools\http\base; defined('ABSPATH') || exit;

class Response {
    protected $res;

    protected $server;

    protected $headers;

    public function raw () {
        return $this->res;
    }

    public function json () {
        return json_decode($this->res);
    }

    public function server () {
        return $this->server;
    }

    public function headers () {
        return $this->headers;
    }

    public function extractResponse () {
        $response_parts = explode("\r\n\r\n", $this->res);

        $this->parseHeaders($response_parts[0]);

        $this->res = $response_parts[1];
    }

    private function parseHeaders ($response_headers) {
        $headers = [];

        foreach (explode("\r\n", $response_headers) as $i => $line) {
            if ($i === 0) $headers['http_code'] = $line;
            else {
                list($key, $value) = explode(': ', $line);
                $headers[$key]     = $value;
            }
        }

        $this->headers = $headers;
    }
}