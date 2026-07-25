<?php
if (!defined('ABSPATH')) {
    exit;
}

add_filter('acf/settings/save_json', function (): string {
    return DGUTHEME_DIR . '/acf-json';
});

add_filter('acf/settings/load_json', function (array $paths): array {
    $paths[] = DGUTHEME_DIR . '/acf-json';

    return array_values(array_unique($paths));
});

add_action('init', function (): void {
    if (!function_exists('get_field') || !function_exists('pll_register_string')) {
        return;
    }

    $header_cta = get_field('header_cta_link', 'option');
    if (!is_array($header_cta)) {
        return;
    }

    $label = trim((string) ($header_cta['title'] ?? ''));
    if ($label !== '') {
        pll_register_string('Header CTA label', $label, 'Theme settings');
    }
}, 20);
