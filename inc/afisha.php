<?php
if (!defined('ABSPATH')) {
    exit;
}

function dgut_performance_datetime(int $post_id): ?DateTimeImmutable
{
    $raw = trim((string) get_post_meta($post_id, 'dgut_performance_date', true));
    if ($raw === '') {
        return null;
    }

    foreach (['Y-m-d H:i:s', 'Y-m-d H:i'] as $format) {
        $date = DateTimeImmutable::createFromFormat('!' . $format, $raw, wp_timezone());
        $errors = DateTimeImmutable::getLastErrors();
        if ($date instanceof DateTimeImmutable && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))) {
            return $date;
        }
    }

    return null;
}

function dgut_afisha_uses_ukrainian_dates(): bool
{
    $language = function_exists('pll_current_language') ? (string) pll_current_language('slug') : '';
    $locale = strtolower((string) determine_locale());

    return in_array($language, ['ua', 'uk'], true)
        || str_starts_with($locale, 'ua')
        || str_starts_with($locale, 'uk');
}

function dgut_afisha_date_label(DateTimeImmutable $date, bool $with_day = true): string
{
    if (!dgut_afisha_uses_ukrainian_dates()) {
        return wp_date($with_day ? 'j F Y' : 'F Y', $date->getTimestamp(), wp_timezone());
    }

    $month = (int) $date->format('n');
    $months = $with_day
        ? [1 => 'січня', 'лютого', 'березня', 'квітня', 'травня', 'червня', 'липня', 'серпня', 'вересня', 'жовтня', 'листопада', 'грудня']
        : [1 => 'Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'];

    return $with_day
        ? sprintf('%s %s %s', $date->format('j'), $months[$month], $date->format('Y'))
        : sprintf('%s %s', $months[$month], $date->format('Y'));
}

function dgut_afisha_weekday_label(DateTimeImmutable $date): string
{
    if (!dgut_afisha_uses_ukrainian_dates()) {
        return wp_date('D', $date->getTimestamp(), wp_timezone());
    }

    $weekdays = [1 => 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Нд'];
    return $weekdays[(int) $date->format('N')];
}

function dgut_performance_date_label(int $post_id): string
{
    $date = dgut_performance_datetime($post_id);
    if ($date) {
        $label = dgut_afisha_date_label($date);
        if ($date->format('H:i') !== '00:00') {
            $label .= ' ' . $date->format('H:i');
        }
        return $label;
    }

    return trim((string) get_post_meta($post_id, 'dgut_performance_date_legacy', true));
}

function dgut_afisha_performance_data(WP_Post|int $performance): array
{
    $performance = is_int($performance) ? get_post($performance) : $performance;
    if (!$performance instanceof WP_Post || $performance->post_type !== 'performance') {
        return [];
    }

    $date = dgut_performance_datetime($performance->ID);
    if (!$date) {
        return [];
    }

    $terms = wp_get_post_terms($performance->ID, 'performance_genre', ['fields' => 'names']);

    return [
        'id' => $performance->ID,
        'title' => get_the_title($performance),
        'permalink' => get_permalink($performance),
        'excerpt' => trim((string) get_the_excerpt($performance)),
        'image_id' => (int) get_post_thumbnail_id($performance),
        'start' => $date,
        'month' => $date->format('Y-m'),
        'day' => wp_date('d', $date->getTimestamp(), wp_timezone()),
        'weekday' => dgut_afisha_weekday_label($date),
        'date' => dgut_afisha_date_label($date),
        'time' => $date->format('H:i') !== '00:00' ? $date->format('H:i') : '',
        'type' => !is_wp_error($terms) && !empty($terms) ? (string) $terms[0] : __('Вистава', 'dgutheater'),
    ];
}

function dgut_afisha_language_query_args(): array
{
    if (!function_exists('pll_current_language')) {
        return [];
    }

    $language = (string) pll_current_language('slug');
    return $language !== '' ? ['lang' => $language] : [];
}

function dgut_afisha_month_options(): array
{
    $posts = get_posts(array_merge([
        'post_type' => 'performance',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => 'dgut_performance_date',
        'orderby' => 'meta_value',
        'order' => 'DESC',
        'suppress_filters' => false,
        'no_found_rows' => true,
    ], dgut_afisha_language_query_args()));

    $months = [];
    foreach ($posts as $post_id) {
        $date = dgut_performance_datetime((int) $post_id);
        if (!$date) {
            continue;
        }
        $months[$date->format('Y-m')] = dgut_afisha_date_label($date, false);
    }

    krsort($months);
    return $months;
}

function dgut_afisha_selected_month(array $months): string
{
    $requested = isset($_GET['month']) ? sanitize_text_field(wp_unslash($_GET['month'])) : '';
    if (preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $requested) && isset($months[$requested])) {
        return $requested;
    }

    $current = wp_date('Y-m', null, wp_timezone());
    if (isset($months[$current])) {
        return $current;
    }

    $chronological = array_keys($months);
    sort($chronological);
    foreach ($chronological as $month) {
        if ($month > $current) {
            return $month;
        }
    }

    return $months ? (string) array_key_first($months) : $current;
}

function dgut_afisha_posts_for_month(string $month): array
{
    $from = $month . '-01 00:00:00';
    $from_date = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $from, wp_timezone());
    if (!$from_date) {
        return [];
    }
    $to = $from_date->modify('last day of this month')->setTime(23, 59, 59)->format('Y-m-d H:i:s');

    return get_posts(array_merge([
        'post_type' => 'performance',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_key' => 'dgut_performance_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'suppress_filters' => false,
        'meta_query' => [[
            'key' => 'dgut_performance_date',
            'value' => [$from, $to],
            'compare' => 'BETWEEN',
            'type' => 'DATETIME',
        ]],
        'no_found_rows' => true,
    ], dgut_afisha_language_query_args()));
}

function dgut_afisha_archive_url(): string
{
    if (function_exists('pll_current_language')) {
        $language = (string) pll_current_language('slug');
        $default_language = function_exists('pll_default_language') ? (string) pll_default_language('slug') : '';
        if ($language !== '' && $default_language !== '' && $language !== $default_language) {
            return home_url('/' . $language . '/afisha/');
        }
    }

    return home_url('/afisha/');
}

function dgut_is_afisha_archive(): bool
{
    return (bool) get_query_var('dgut_afisha_archive');
}

function dgut_normalize_legacy_performance_datetime(string $raw): string
{
    $raw = trim($raw);
    if ($raw === '') {
        return '';
    }

    foreach (['Y-m-d H:i:s', 'Y-m-d H:i'] as $format) {
        $date = DateTimeImmutable::createFromFormat('!' . $format, $raw, wp_timezone());
        $errors = DateTimeImmutable::getLastErrors();
        if ($date instanceof DateTimeImmutable && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))) {
            return $date->format('Y-m-d H:i:s');
        }
    }

    if (preg_match('/\b(\d{1,2})\.(\d{1,2})\.(\d{4})(?:\s+(\d{1,2}):(\d{2}))?\b/u', $raw, $matches)) {
        $day = (int) $matches[1];
        $month = (int) $matches[2];
        $year = (int) $matches[3];
        $hour = (int) ($matches[4] ?? 0);
        $minute = (int) ($matches[5] ?? 0);
        return checkdate($month, $day, $year) && $hour <= 23 && $minute <= 59
            ? sprintf('%04d-%02d-%02d %02d:%02d:00', $year, $month, $day, $hour, $minute)
            : '';
    }

    $ukrainian_months = [
        'січня' => 1, 'лютого' => 2, 'березня' => 3, 'квітня' => 4,
        'травня' => 5, 'червня' => 6, 'липня' => 7, 'серпня' => 8,
        'вересня' => 9, 'жовтня' => 10, 'листопада' => 11, 'грудня' => 12,
    ];
    if (preg_match('/\b(\d{1,2})\s+([а-яіїєґ]+)\s+(\d{4})(?:\s+(\d{1,2}):(\d{2}))?\b/ui', $raw, $matches)) {
        $month_name = mb_strtolower($matches[2]);
        if (isset($ukrainian_months[$month_name])) {
            $day = (int) $matches[1];
            $month = $ukrainian_months[$month_name];
            $year = (int) $matches[3];
            $hour = (int) ($matches[4] ?? 0);
            $minute = (int) ($matches[5] ?? 0);
            return checkdate($month, $day, $year) && $hour <= 23 && $minute <= 59
                ? sprintf('%04d-%02d-%02d %02d:%02d:00', $year, $month, $day, $hour, $minute)
                : '';
        }
    }

    if (preg_match('/\b(January|February|March|April|May|June|July|August|September|October|November|December)\s+(\d{1,2}),\s*(\d{4})(?:\s+(\d{1,2}):(\d{2}))?\b/i', $raw, $matches)) {
        $date = DateTimeImmutable::createFromFormat('!F j Y H:i', sprintf('%s %d %d %02d:%02d', $matches[1], (int) $matches[2], (int) $matches[3], (int) ($matches[4] ?? 0), (int) ($matches[5] ?? 0)), wp_timezone());
        return $date instanceof DateTimeImmutable ? $date->format('Y-m-d H:i:s') : '';
    }

    return '';
}

add_action('init', function (): void {
    add_rewrite_rule('^afisha/?$', 'index.php?dgut_afisha_archive=1', 'top');

    if (function_exists('pll_languages_list')) {
        $default_language = function_exists('pll_default_language') ? (string) pll_default_language('slug') : '';
        foreach ((array) pll_languages_list(['fields' => 'slug']) as $language) {
            $language = sanitize_key((string) $language);
            if ($language !== '' && $language !== $default_language) {
                add_rewrite_rule('^' . preg_quote($language, '/') . '/afisha/?$', 'index.php?dgut_afisha_archive=1&lang=' . $language, 'top');
            }
        }
    }
});

add_filter('query_vars', function (array $query_vars): array {
    $query_vars[] = 'dgut_afisha_archive';
    return $query_vars;
});

add_filter('template_include', function (string $template): string {
    if (!dgut_is_afisha_archive()) {
        return $template;
    }

    $afisha_template = locate_template('archive-afisha.php');
    return $afisha_template !== '' ? $afisha_template : $template;
}, 99);

add_filter('pre_get_document_title', function (string $title): string {
    return dgut_is_afisha_archive() ? __('Афіша', 'dgutheater') . ' - ' . get_bloginfo('name') : $title;
});

add_filter('wpseo_title', function (string $title): string {
    return dgut_is_afisha_archive() ? __('Афіша', 'dgutheater') . ' | ' . get_bloginfo('name') : $title;
});

add_filter('wpseo_canonical', function ($canonical) {
    return dgut_is_afisha_archive() ? dgut_afisha_archive_url() : $canonical;
});

add_filter('body_class', function (array $classes): array {
    if (dgut_is_afisha_archive()) {
        $classes[] = 'post-type-archive-performance';
        $classes[] = 'dgut-afisha-virtual-archive';
    }
    return $classes;
});

add_action('template_redirect', function (): void {
    if (dgut_is_afisha_archive()) {
        global $wp_query;
        $wp_query->is_404 = false;
        status_header(200);
        return;
    }

    $path = trim((string) wp_parse_url(wp_unslash($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH), '/');
    $redirects = [
        'afisha/sidur' => 'sidur',
        'afisha/vilni-stosunki' => 'vilni-stosunky',
        'afisha/povernennya-hlopchika-abo-ostannya-kazka-pro-gologo-korolya' => 'povernennya-khlopchyka',
    ];
    if (!isset($redirects[$path])) {
        return;
    }

    $performance = get_page_by_path($redirects[$path], OBJECT, 'performance');
    if ($performance instanceof WP_Post) {
        wp_safe_redirect(get_permalink($performance), 301);
        exit;
    }
});

add_action('init', function (): void {
    $migration_version = '1';
    if (get_option('dgut_performance_datetime_migration') === $migration_version) {
        return;
    }

    $performance_ids = get_posts([
        'post_type' => 'performance',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'suppress_filters' => true,
    ]);
    foreach ($performance_ids as $performance_id) {
        $raw = trim((string) get_post_meta((int) $performance_id, 'dgut_performance_date', true));
        if ($raw !== '' && get_post_meta((int) $performance_id, 'dgut_performance_date_legacy', true) === '') {
            update_post_meta((int) $performance_id, 'dgut_performance_date_legacy', $raw);
        }
    }

    global $wpdb;
    $legacy_event_ids = $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'dgut_event'");
    foreach ($legacy_event_ids as $event_id) {
        $performance_id = absint(get_post_meta((int) $event_id, 'dgut_event_performance', true));
        $event_date = dgut_normalize_legacy_performance_datetime((string) get_post_meta((int) $event_id, 'dgut_event_start', true));
        if ($performance_id <= 0 || $event_date === '') {
            continue;
        }

        $translation_ids = [$performance_id];
        if (function_exists('pll_get_post_translations')) {
            $translation_ids = array_merge($translation_ids, array_values((array) pll_get_post_translations($performance_id)));
        }
        foreach (array_unique(array_map('intval', $translation_ids)) as $translation_id) {
            if ($translation_id > 0) {
                update_post_meta($translation_id, 'dgut_performance_date', $event_date);
            }
        }
    }

    foreach ($performance_ids as $performance_id) {
        $performance_id = (int) $performance_id;
        $raw = trim((string) get_post_meta($performance_id, 'dgut_performance_date', true));
        $normalized = dgut_normalize_legacy_performance_datetime($raw);
        if ($normalized !== '') {
            update_post_meta($performance_id, 'dgut_performance_date', $normalized);
        } else {
            delete_post_meta($performance_id, 'dgut_performance_date');
        }
    }

    update_option('dgut_performance_datetime_migration', $migration_version, false);
}, 50);

add_action('init', function (): void {
    if (!function_exists('get_field') || !function_exists('update_field')) {
        return;
    }

    $front_page_id = (int) get_option('page_on_front');
    if ($front_page_id <= 0 || get_post_meta($front_page_id, '_dgut_home_hero_afisha_links_migrated_v2', true)) {
        return;
    }

    $rows = get_field('home_hero_slides', $front_page_id);
    if (!is_array($rows)) {
        return;
    }

    $link_map = [
        'afisha/sidur' => 'sidur',
        'afisha/vilni-stosunki' => 'vilni-stosunky',
        'afisha/povernennya-hlopchika-abo-ostannya-kazka-pro-gologo-korolya' => 'povernennya-khlopchyka',
    ];
    foreach ($rows as &$row) {
        if (!is_array($row) || !is_array($row['link'] ?? null)) {
            continue;
        }
        $path = trim((string) wp_parse_url((string) ($row['link']['url'] ?? ''), PHP_URL_PATH), '/');
        if (!isset($link_map[$path])) {
            continue;
        }
        $performance = get_page_by_path($link_map[$path], OBJECT, 'performance');
        if ($performance instanceof WP_Post) {
            $row['link']['url'] = get_permalink($performance);
        }
    }
    unset($row);

    update_field('field_dgut_home_hero_slides', $rows, $front_page_id);
    update_post_meta($front_page_id, '_dgut_home_hero_afisha_links_migrated_v2', '1');
}, 60);

add_action('init', function (): void {
    $rewrite_version = '3';
    if (get_option('dgut_afisha_rewrite_version') === $rewrite_version) {
        return;
    }

    flush_rewrite_rules(false);
    update_option('dgut_afisha_rewrite_version', $rewrite_version, false);
}, 99);
