<?php namespace core; defined('ABSPATH') || exit;

use app\Applications;

class RemoveCoreFeatures extends Applications {
    static private $remove_core_features;

    static private function setRemoveCoreFeatures () {
        self::$remove_core_features = [
            // wordpress generator
            'wp_generator'                        => true,
            'the_generator'                       => true,
            //rss feed
            'do_feed'                             => true,
            'do_feed_rdf'                         => true,
            'do_feed_rss'                         => true,
            'do_feed_rss2'                        => true,
            'do_feed_atom'                        => true,
            'do_feed_rss2_comments'               => true,
            'do_feed_atom_comments'               => true,
            'feed_links_extra'                    => true,
            'feed_links'                          => true,
            // dns prefetch
            'emoji_svg_url'                       => true,
            // emojies
            'head_print_emoji_detection_script'   => true,
            'admin_print_emoji_detection_script'  => true,
            'print_emoji_styles'                  => true,
            // wordpress embed
            'wp_embed'                            => true,
            // gutenberg block library
            'wp_block_library'                    => true,
            'wp_block_library_theme'              => true,
            // REST API link tag
            'rest_output_link_wp_head'            => true,
            // oEmbed Discovery Links
            'wp_oembed_add_discovery_links'       => true,
            // REST API link in HTTP headers
            'rest_output_link_header'             => true,
            // rsd xml link
            'rsd_link'                            => true,
            // wlwmanifest link
            'wlwmanifest_link'                    => true,
            // shorlink
            'wp_shortlink_wp_head'                => true,
            'wp_shortlink_header'                 => true,
            // css/js versions
            'css_js_versions'                     => true,
            // jquery migrate
            'jquery_migrate'                      => true,
            // dashicons
            'amethyst_dashicons_style'            => false,
            'dashicons'                           => false,
            // wordpress nodes
            'wp_nodes'                            => true,
            // dns-prefetch Link (Frontend)
            'wp_resource_hints'                   => true,
            // global inline style css
            'global_styles'                       => true,
            // w3 svg
            'wp_global_styles_render_svg_filters' => true,
            // automatic updates
            'auto_update_plugin'                  => true,
            'auto_update_theme'                   => true,
            // classic editor
            'use_block_editor_for_post'           => true,
        ];
    }

    static private function setApplications () {
        self::$remove_core_features = array_merge(self::$remove_core_features, self::$apps_core_features);
    }

    static public function init () {
        self::setRemoveCoreFeatures();
        self::setApplications();

        // wordpress generator
        add_action('wp_head',       [self::class, 'wp_generator'], 9);
        add_filter('the_generator', [self::class, 'the_generator']);

        // rss feed
        add_action('do_feed',               [self::class, 'do_feed'],               1);
        add_action('do_feed_rdf',           [self::class, 'do_feed_rdf'],           1);
        add_action('do_feed_rss',           [self::class, 'do_feed_rss'],           1);
        add_action('do_feed_rss2',          [self::class, 'do_feed_rss2'],          1);
        add_action('do_feed_atom',          [self::class, 'do_feed_atom'],          1);
        add_action('do_feed_rss2_comments', [self::class, 'do_feed_rss2_comments'], 1);
        add_action('do_feed_atom_comments', [self::class, 'do_feed_atom_comments'], 1);
        add_action('wp_head',               [self::class, 'feed_links_extra'],      2);
        add_action('wp_head',               [self::class, 'feed_links'],            1);

        // dns prefetch
        add_filter('emoji_svg_url', [self::class, 'emoji_svg_url']);

        // emojies
        add_action('wp_head',             [self::class, 'head_print_emoji_detection_script'],  6);
        add_action('admin_print_scripts', [self::class, 'admin_print_emoji_detection_script'], 9);
        add_action('wp_print_styles',     [self::class, 'print_emoji_styles'],                 9);
        add_action('admin_print_styles',  [self::class, 'print_emoji_styles'],                 9);

        // wordpress embed
        add_action('wp_footer', [self::class, 'wp_embed']);

        // gutenberg block library
        add_action('wp_enqueue_scripts', [self::class, 'wp_block_library']);
        add_action('wp_enqueue_scripts', [self::class, 'wp_block_library_theme']);

        // REST API link tag
        add_action('wp_head', [self::class, 'rest_output_link_wp_head'], 9);

        // oEmbed Discovery Links
        add_action('wp_head', [self::class, 'wp_oembed_add_discovery_links'], 9);

        // REST API link in HTTP headers
        add_action('template_redirect', [self::class, 'rest_output_link_header']);

        // rsd xml link
        add_action('wp_head', [self::class, 'rsd_link'], 9);

        // wlwmanifest link
        add_action('wp_head', [self::class, 'wlwmanifest_link'], 9);

        // shorlink
        add_action('wp_head', [self::class, 'wp_shortlink_wp_head'], 9);
        add_action('template_redirect', [self::class, 'wp_shortlink_header']);

        // css/js versions
        add_filter('style_loader_src', [self::class, 'css_js_versions']);
        add_filter('script_loader_src', [self::class, 'css_js_versions']);

        // jquery migrate
        add_action('wp_default_scripts', [self::class, 'jquery_migrate']);

        // dashicons
        add_action('wp_print_styles', [self::class, 'amethyst_dashicons_style']);
        add_action('wp_print_styles', [self::class, 'dashicons']);

        // wordpress nodes
        add_action('admin_bar_menu', [self::class, 'wp_nodes'], 80);

        // dns-prefetch Link (Frontend)
        add_action('wp_head', [self::class, 'wp_resource_hints'], 1);

        // global inline style css
        add_action('wp_enqueue_scripts', [self::class, 'global_styles']);

        // w3 svg
        add_action('wp_body_open', [self::class, 'wp_global_styles_render_svg_filters'], 9);

        // automatic updates
        add_filter('auto_update_plugin', [self::class, 'auto_update_plugin']);
        add_filter('auto_update_theme', [self::class, 'auto_update_theme']);

        // classic editor
        add_filter('use_block_editor_for_post', [self::class, 'use_block_editor_for_post']);
    }

    static private function toRemove ($prop) {
        return self::$remove_core_features[$prop] ?? false;
    }

    static public function wp_generator () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('wp_head', __FUNCTION__);
    }

    static public function the_generator ($type) {
        if (!self::toRemove(__FUNCTION__)) return $type;
        return '';
    }

    static private function rssFeedDie () {
        wp_send_json([
            'message' => apply_filters('buba_no_rss_feed_description', esc_html__('No feed is available for this site', BUBA_THEME_DOMAIN)),
        ]);
    }

    static public function do_feed () {
        if (!self::toRemove(__FUNCTION__)) return;
        self::rssFeedDie();
    }

    static public function do_feed_rdf () {
        if (!self::toRemove(__FUNCTION__)) return;
        self::rssFeedDie();
    }

    static public function do_feed_rss () {
        if (!self::toRemove(__FUNCTION__)) return;
        self::rssFeedDie();
    }

    static public function do_feed_rss2 () {
        if (!self::toRemove(__FUNCTION__)) return;
        self::rssFeedDie();
    }

    static public function do_feed_atom () {
        if (!self::toRemove(__FUNCTION__)) return;
        self::rssFeedDie();
    }

    static public function do_feed_rss2_comments () {
        if (!self::toRemove(__FUNCTION__)) return;
        self::rssFeedDie();
    }

    static public function do_feed_atom_comments () {
        if (!self::toRemove(__FUNCTION__)) return;
        self::rssFeedDie();
    }

    static public function feed_links_extra () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('wp_head', __FUNCTION__, 3);
    }

    static public function feed_links () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('wp_head', __FUNCTION__, 3);
    }

    static public function emoji_svg_url ($url) {
        if (!self::toRemove(__FUNCTION__)) return $url;
        return '';
    }

    static public function head_print_emoji_detection_script () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('wp_head', 'print_emoji_detection_script', 7);
    }

    static public function admin_print_emoji_detection_script () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
    }

    static public function print_emoji_styles () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('wp_print_styles',    __FUNCTION__);
        remove_action('admin_print_styles', __FUNCTION__);
    }

    static public function wp_embed () {
        if (!self::toRemove(__FUNCTION__)) return;
        wp_dequeue_script('wp-embed');
    }

    static public function wp_block_library () {
        if (!self::toRemove(__FUNCTION__)) return;
        wp_dequeue_style('wp-block-library');
    }

    static public function wp_block_library_theme () {
        if (!self::toRemove(__FUNCTION__)) return;
        wp_dequeue_style('wp-block-library-theme');
    }

    static public function rest_output_link_wp_head () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('wp_head', __FUNCTION__);
    }

    static public function wp_oembed_add_discovery_links () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('wp_head', __FUNCTION__);
    }

    static public function rest_output_link_header () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('template_redirect', __FUNCTION__, 11);
    }

    static public function rsd_link () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('wp_head', __FUNCTION__);
    }

    static public function wlwmanifest_link () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('wp_head', __FUNCTION__);
    }

    static public function wp_shortlink_wp_head () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('wp_head', __FUNCTION__);
    }

    static public function wp_shortlink_header () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('template_redirect', __FUNCTION__, 11);
    }

    static public function css_js_versions ($src) {
        if (!self::toRemove(__FUNCTION__)) return $src;
        if (!is_admin() && strpos($src, 'ver=')) return remove_query_arg('ver', $src);
        return $src;
    }

    static public function jquery_migrate ($scripts) {
        if (!self::toRemove(__FUNCTION__)) return;
        if (!is_admin() && isset($scripts->registered['jquery'])) {
            $script = $scripts->registered['jquery'];

            if ($script->deps) $script->deps = array_diff($script->deps, ['jquery-migrate']);
        }
    }

    static public function amethyst_dashicons_style () {
        if (!self::toRemove(__FUNCTION__)) return;
        wp_deregister_style('amethyst-dashicons-style'); 
    }

    static public function dashicons () {
        if (!self::toRemove(__FUNCTION__)) return;
        wp_deregister_style(__FUNCTION__); 
    }

    static public function wp_nodes () {
        if (!self::toRemove(__FUNCTION__)) return;
        global $wp_admin_bar;

        $wp_admin_bar->remove_node('new-content');
    }

    static public function wp_resource_hints () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('wp_head', __FUNCTION__, 2);
    }

    static public function global_styles () {
        if (!self::toRemove(__FUNCTION__)) return;
        wp_dequeue_style('global-styles');
    }

    static public function wp_global_styles_render_svg_filters () {
        if (!self::toRemove(__FUNCTION__)) return;
        remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
    }

    static public function auto_update_plugin ($status) {
        if (!self::toRemove(__FUNCTION__)) return $status;
        return false;
    }

    static public function auto_update_theme ($status) {
        if (!self::toRemove(__FUNCTION__)) return $status;
        return false;
    }

    static public function use_block_editor_for_post ($status) {
        if (!self::toRemove(__FUNCTION__)) return $status;
        return false;
    }
}