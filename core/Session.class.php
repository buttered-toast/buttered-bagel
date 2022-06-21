<?php namespace core; defined('ABSPATH') || exit;

class Session {
    static private $instances = [];

    private $token;

    private function __construct () {
        add_action('init', [$this, 'initializeSession']);
    }

    public function initializeSession () {
        if (!session_id()) {
            session_name('bubasession');
            session_start();
        }

        $this->generateToken();
    }

    public function set ($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get ($key) {
        return $_SESSION[$key] ?? '';
    }

    public function remove ($key) {
        if ($this->exist($key)) unset($_SESSION[$key]);
    }

    public function exists ($key) {
        if (isset($_SESSION[$key])) return true;
        return false;
    }

    private function generateToken () {
        $this->token = substr(str_shuffle(MD5(microtime())), 0, 30);

        $_SESSION['token'] = $this->token;
    }

    public function isTokenValid () {
        if ($this->token !== $_SESSION['token']) {
            $this->generateToken();
            return false;
        }
        $this->generateToken();
        return true;
    }

    static public function init () {
        self::getInstance();
    }

    static public function getInstance () : Session {
        $cls = static::class;
        if (!isset(self::$instances[$cls])) self::$instances[$cls] = new static();

        return self::$instances[$cls];
    }
}