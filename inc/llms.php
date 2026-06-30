<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('template_redirect', function (): void {
    $request_path = trim((string) wp_parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH), '/');

    if ($request_path !== 'llms.txt') {
        return;
    }

    $file = DGUTHEME_DIR . '/llms.txt';

    if (!is_readable($file)) {
        status_header(404);
        nocache_headers();
        exit;
    }

    status_header(200);
    header('Content-Type: text/plain; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    echo (string) file_get_contents($file);
    exit;
});
