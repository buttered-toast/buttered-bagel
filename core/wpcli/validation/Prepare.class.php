<?php namespace core\wpcli\validation; defined('ABSPATH') || exit;

use WP_CLI;

class Prepare {
    static public function coloredCommands ($message, $commands) {
        $commands = array_map(
            fn ($command) => "%6{$command}%n",
            $commands,
        );

        return vsprintf($message, $commands);
    }
}