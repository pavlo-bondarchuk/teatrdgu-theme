<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', function (): void {
    if (is_page_template('template-coming-soon.php')) {
        wp_enqueue_style('dgut-coming-soon', DGUTHEME_URI . '/assets/css/coming-soon.css', [], DGUTHEME_VERSION);
        wp_enqueue_script('dgut-coming-soon', DGUTHEME_URI . '/assets/js/coming-soon.js', [], DGUTHEME_VERSION, true);
        wp_script_add_data('dgut-coming-soon', 'defer', true);
        return;
    }

    wp_enqueue_style(
        'dgut-fonts',
        'https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=Inter:wght@300;400;500;600&display=swap',
        [],
        null
    );
    wp_enqueue_style('dgut-common', DGUTHEME_URI . '/assets/css/common.css', ['dgut-fonts'], DGUTHEME_VERSION);
    wp_enqueue_script('dgut-common', DGUTHEME_URI . '/assets/js/common.js', [], DGUTHEME_VERSION, true);
    wp_script_add_data('dgut-common', 'defer', true);
    if (is_front_page()) {
        wp_enqueue_style('dgut-front-page', DGUTHEME_URI . '/assets/css/front-page.css', ['dgut-common'], DGUTHEME_VERSION);
        wp_enqueue_script('dgut-front-page', DGUTHEME_URI . '/assets/js/front-page.js', ['dgut-common'], DGUTHEME_VERSION, true);
        wp_script_add_data('dgut-front-page', 'defer', true);
    }
    if (is_singular('performance')) {
        wp_enqueue_style('dgut-event', DGUTHEME_URI . '/assets/css/event.css', ['dgut-common'], DGUTHEME_VERSION);
    }
}, 20);

add_filter('style_loader_tag', function (string $html, string $handle): string {
    if ($handle === 'dgut-common') {
        return str_replace("media='all'", "media='all'", $html);
    }
    return $html;
}, 10, 2);
