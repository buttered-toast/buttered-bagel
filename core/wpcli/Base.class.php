<?php namespace core\wpcli; defined('ABSPATH') || exit;

class Base {
    static protected $placeholder = '/*!wp-cli component create placeholder!*/';

    static protected $progress;

    static protected $ticks;

    static protected $current_tick = 0;

    static protected function startProgress ($label, $ticks) {
        self::$progress = \WP_CLI\Utils\make_progress_bar($label, $ticks);
    }

    static protected function tick () {
        if (++self::$current_tick < self::$ticks) {
            self::$progress->tick();
            usleep(5e5);
        }
        else self::$progress->finish();
    }
}