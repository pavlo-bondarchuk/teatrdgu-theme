<?php
if (!defined('ABSPATH')) {
    exit;
}

function dgut_maintenance_enabled(): bool
{
    return (bool) dgut_option('dgut_maintenance_enabled', false);
}

function dgut_maintenance_page_id(): int
{
    $raw = trim((string) dgut_option('dgut_maintenance_page', 'coming-soon'));
    if ($raw === '') {
        return 0;
    }

    if (ctype_digit($raw)) {
        return (int) $raw;
    }

    $page = get_page_by_path(trim($raw, '/'));
    return $page instanceof WP_Post ? (int) $page->ID : 0;
}

function dgut_maintenance_bypass_requested(): bool
{
    if (!isset($_GET['dgut_maintenance_bypass'])) {
        return false;
    }

    $value = sanitize_text_field(wp_unslash($_GET['dgut_maintenance_bypass']));
    return in_array($value, ['1', 'true', 'yes'], true);
}

function dgut_is_maintenance_request_allowed(): bool
{
    $request_path = trim((string) wp_parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH), '/');

    if (dgut_maintenance_bypass_requested()) {
        return true;
    }

    if ($request_path === 'llms.txt') {
        return true;
    }

    if (is_admin() || wp_doing_ajax() || wp_doing_cron() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return true;
    }

    if (is_user_logged_in() && current_user_can('edit_theme_options')) {
        return true;
    }

    if (str_contains($_SERVER['REQUEST_URI'] ?? '', 'wp-login.php')) {
        return true;
    }

    return false;
}

add_action('template_redirect', function (): void {
    if (!dgut_maintenance_enabled() || dgut_is_maintenance_request_allowed()) {
        return;
    }

    $page_id = dgut_maintenance_page_id();
    if (!$page_id || is_page($page_id)) {
        return;
    }

    wp_safe_redirect(get_permalink($page_id), 302);
    exit;
}, 1);

add_action('after_switch_theme', function (): void {
    if (get_page_by_path('coming-soon')) {
        return;
    }

    $page_id = wp_insert_post([
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => __('Сайт незабаром відкриється', 'dgutheater'),
        'post_name' => 'coming-soon',
        'post_content' => __('Ми працюємо над новим сайтом сучасної культурної платформи — простором для театру, діалогу та натхнення. Скоро тут з’являться репертуар, події та новини.', 'dgutheater'),
    ]);

    if (!is_wp_error($page_id)) {
        update_post_meta((int) $page_id, '_wp_page_template', 'template-coming-soon.php');
    }
});

add_action('wp', function (): void {
    if (!dgut_maintenance_enabled() || dgut_maintenance_bypass_requested()) {
        return;
    }

    $page_id = dgut_maintenance_page_id();
    if ($page_id && is_page($page_id)) {
        status_header(503);
        nocache_headers();
    }
});
