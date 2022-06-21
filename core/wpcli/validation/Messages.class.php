<?php namespace core\wpcli\validation; defined('ABSPATH') || exit;

class Messages {
    static protected $components_create_no_args = [
        'components create',
        'wp buba components create hero',
    ];

    static protected $components_create_too_many_args = [
        'components create',
    ];

    static protected $app_hierarchy_create_no_args = [
        'appHierarchy create',
        'wp buba appHierarchy create hero',
    ];

    static protected $app_hierarchy_create_too_many_args = [
        'appHierarchy create',
    ];
}