<?php namespace tools; defined('ABSPATH') || exit;

use Timber;
use Timber\Image;
use tools\Debug;

class Images {
    static private $image;

    static private $width;

    static private $height;

    static private $alt;

    static private $attributes = '';

    static private $picture_srcset = '';

    static private $picture_media_rule = 'min-width:';

    // static private $img_srcset = '';

    static private $options = [
        'until_size' => 'all',
        'from_to'    => 'lts',
        'lazyload'   => true,
    ];

    static private $sizes_parse = [
        'full',
        '1536x1536',
        'large',
        'medium_large',
        'bt-image-500',
        'bt-image-400',
        'medium',
    ];

    static private $lts_media_rules = [
        '1537',
        '1025',
        '769',
        '501',
        '401',
        '301',
        '0',
    ];

    static private $stl_media_rules = [
        '300',
        '400',
        '500',
        '768',
        '1024',
        '1536',
        '9999',
    ];

    static private function setImage ($image) {
        if (is_numeric($image))                                        self::$image = new Image($image);
        if (is_object($image) && get_class($image) === 'Timber\Image') self::$image = $image;

        self::$width  = self::$image->width();
        self::$height = self::$image->height();
        self::$alt    = self::$image->alt();
    }

    static public function getPicture ($image, $options = []) {
        self::setImage($image);
        self::setOptions($options);
        self::setAttributes($options);
        self::preparePictureSrcset();
        
        return self::pictureHtml();
    }

    static public function thePicture ($image, $options = []) {
        echo self::getPicture($image, $options);
    }

    static private function setOptions ($options) {
        if (empty($options)) return;

        foreach (['until_size', 'from_to', 'lazyload',] as $option) {
            if (isset($options[$option])) self::$options[$option] = $options[$option];
        }
    }

    static private function setAttributes ($options) {
        if (empty($options['attributes'])) return;

        foreach ($options['attributes'] as $attribute => $value) {
            self::$attributes .= " {$attribute}=\"{$value}\"";
        }
    }

    static private function preparePictureSizes () {
        if (self::$options['from_to'] === 'stl') {
            self::$sizes_parse        = array_reverse(self::$sizes_parse);
            self::$picture_media_rule = 'max-width:';
        }

        $media_rules = self::$options['from_to'] . '_media_rules';

        self::$sizes_parse = array_combine(self::$$media_rules, self::$sizes_parse);
    }

    static private function preparePictureSrcset () {
        self::preparePictureSizes();

        foreach (self::$sizes_parse as $media => $size) {
            self::$picture_srcset .= '<source media="(' . self::$picture_media_rule . $media . 'px)" srcset="' . self::$image->src($size) . '">';

            if (self::$options['until_size'] === $size) break;
        }
    }

    static private function pictureHtml () {
        $sources    = self::$picture_srcset;
        $width      = self::$width;
        $height     = self::$height;
        $src        = self::$image->src;
        $alt        = self::$alt;
        $attributes = self::$attributes;

        return <<<PICTURE
            <picture{$attributes}>
                {$sources}
                <img width="{$width}" height="{$height}" src="{$src}" alt="{$alt}">
            </picture>
        PICTURE;
    }
}