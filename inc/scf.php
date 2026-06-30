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
