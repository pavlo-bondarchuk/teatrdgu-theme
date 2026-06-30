<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', function (): void {
    load_theme_textdomain('dgutheater', DGUTHEME_DIR . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('editor-styles');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_image_size('dgut-card', 720, 900, true);
    add_image_size('dgut-wide', 1280, 720, true);
    add_image_size('hero-slider', 1440, 640, true);
    register_nav_menus([
        'primary' => __('Primary menu', 'dgutheater'),
    ]);
});

add_action('init', function (): void {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
});

add_filter('wp_lazy_loading_enabled', function (bool $default, string $tag_name, string $context): bool {
    if ($context === 'the_post_thumbnail') {
        return false;
    }
    return $default;
}, 10, 3);
