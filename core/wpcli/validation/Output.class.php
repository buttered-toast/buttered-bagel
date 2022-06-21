<?php namespace core\wpcli\validation; defined('ABSPATH') || exit;

use WP_CLI;

class Output {
    static public function coloredError ($message) {
        self::coloredLog($message, 'error');
    }

    static public function coloredWarning ($message) {
        self::coloredLog($message, 'warning');
    }

    static private function coloredLog ($message, $alert) {
        switch ($alert) {
            case 'error':   $alert = '%rError: %n'; break;
            case 'warning': $alert = '%yWarning: %n'; break;
        }

        WP_CLI::log(WP_CLI::colorize("{$alert}{$message}"));
    }
}