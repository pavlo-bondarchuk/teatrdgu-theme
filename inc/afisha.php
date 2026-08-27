<?php
if (!defined('ABSPATH')) {
    exit;
}

function dgut_afisha_raw_field(string $key, int $post_id = 0, mixed $default = ''): mixed
{
    $post_id = $post_id ?: get_the_ID();
    $value = get_post_meta($post_id, $key, true);

    return $value !== '' && $value !== null ? $value : $default;
}

function dgut_afisha_datetime(int $post_id, string $key = 'dgut_event_start'): ?DateTimeImmutable
{
    $raw = trim((string) dgut_afisha_raw_field($key, $post_id));
    if ($raw === '') {
        return null;
    }

    $timezone = wp_timezone();
    $formats = ['Y-m-d H:i:s', 'Y-m-d H:i', 'd.m.Y H:i', DateTimeInterface::ATOM];
    foreach ($formats as $format) {
        $date = DateTimeImmutable::createFromFormat('!' . $format, $raw, $timezone);
        if ($date instanceof DateTimeImmutable) {
            return $date;
        }
    }

    try {
        return new DateTimeImmutable($raw, $timezone);
    } catch (Exception) {
        return null;
    }
}

function dgut_afisha_performance_id(int $event_id): int
{
    $value = dgut_afisha_raw_field('dgut_event_performance', $event_id, 0);
    if ($value instanceof WP_Post) {
        return (int) $value->ID;
    }
    if (is_array($value)) {
        $value = reset($value);
    }

    return absint($value);
}

function dgut_afisha_image_id(int $event_id): int
{
    $image_id = get_post_thumbnail_id($event_id);
    if ($image_id > 0) {
        return $image_id;
    }

    $performance_id = dgut_afisha_performance_id($event_id);
    return $performance_id > 0 ? (int) get_post_thumbnail_id($performance_id) : 0;
}

function dgut_afisha_excerpt(int $event_id): string
{
    $excerpt = trim((string) get_post_field('post_excerpt', $event_id));
    if ($excerpt !== '') {
        return $excerpt;
    }

    $performance_id = dgut_afisha_performance_id($event_id);
    if ($performance_id > 0) {
        $excerpt = trim((string) get_post_field('post_excerpt', $performance_id));
    }

    return $excerpt;
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

function dgut_afisha_statuses(): array
{
    return [
        'available' => __('Є квитки', 'dgutheater'),
        'few' => __('Мало квитків', 'dgutheater'),
        'sold_out' => __('Продано', 'dgutheater'),
        'postponed' => __('Перенесено', 'dgutheater'),
        'cancelled' => __('Скасовано', 'dgutheater'),
    ];
}

function dgut_afisha_event_data(WP_Post|int $event): array
{
    $event = is_int($event) ? get_post($event) : $event;
    if (!$event instanceof WP_Post) {
        return [];
    }

    $start = dgut_afisha_datetime($event->ID);
    $performance_id = dgut_afisha_performance_id($event->ID);
    $status_key = sanitize_key((string) dgut_afisha_raw_field('dgut_event_ticket_status', $event->ID, 'available'));
    $statuses = dgut_afisha_statuses();
    $terms = wp_get_post_terms($event->ID, 'dgut_event_type', ['fields' => 'names']);

    return [
        'id' => $event->ID,
        'title' => get_the_title($event),
        'permalink' => get_permalink($event),
        'excerpt' => dgut_afisha_excerpt($event->ID),
        'image_id' => dgut_afisha_image_id($event->ID),
        'start' => $start,
        'month' => $start ? $start->format('Y-m') : '',
        'day' => $start ? wp_date('d', $start->getTimestamp(), wp_timezone()) : '',
        'weekday' => $start ? dgut_afisha_weekday_label($start) : '',
        'date' => $start ? dgut_afisha_date_label($start) : '',
        'time' => $start ? wp_date('H:i', $start->getTimestamp(), wp_timezone()) : '',
        'venue' => trim((string) dgut_afisha_raw_field('dgut_event_venue', $event->ID)),
        'price' => trim((string) dgut_afisha_raw_field('dgut_event_price', $event->ID)),
        'age' => trim((string) dgut_afisha_raw_field('dgut_event_age', $event->ID)),
        'duration' => trim((string) dgut_afisha_raw_field('dgut_event_duration', $event->ID)),
        'ticket_url' => esc_url_raw((string) dgut_afisha_raw_field('dgut_event_ticket_url', $event->ID)),
        'status_key' => $status_key,
        'status' => $statuses[$status_key] ?? $statuses['available'],
        'featured' => (bool) dgut_afisha_raw_field('dgut_event_featured', $event->ID, false),
        'performance_id' => $performance_id,
        'performance_url' => $performance_id > 0 ? get_permalink($performance_id) : '',
        'type' => !is_wp_error($terms) && !empty($terms) ? (string) $terms[0] : __('Подія', 'dgutheater'),
    ];
}

function dgut_afisha_month_options(): array
{
    $posts = get_posts([
        'post_type' => 'dgut_event',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_key' => 'dgut_event_start',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'no_found_rows' => true,
    ]);

    $months = [];
    foreach ($posts as $post_id) {
        $date = dgut_afisha_datetime((int) $post_id);
        if (!$date) {
            continue;
        }
        $key = $date->format('Y-m');
        $months[$key] = dgut_afisha_date_label($date, false);
    }

    ksort($months);
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
    foreach (array_keys($months) as $month) {
        if ($month > $current) {
            return $month;
        }
    }

    return $months ? (string) array_key_last($months) : $current;
}

function dgut_afisha_posts_for_month(string $month): array
{
    $from = $month . '-01 00:00:00';
    $to_date = DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $from, wp_timezone());
    if (!$to_date) {
        return [];
    }
    $to = $to_date->modify('last day of this month')->setTime(23, 59, 59)->format('Y-m-d H:i:s');

    return get_posts([
        'post_type' => 'dgut_event',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_key' => 'dgut_event_start',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => [[
            'key' => 'dgut_event_start',
            'value' => [$from, $to],
            'compare' => 'BETWEEN',
            'type' => 'DATETIME',
        ]],
        'no_found_rows' => true,
    ]);
}

function dgut_afisha_other_dates(int $event_id, int $performance_id, int $limit = 3): array
{
    if ($performance_id <= 0) {
        return [];
    }

    return get_posts([
        'post_type' => 'dgut_event',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'post__not_in' => [$event_id],
        'meta_key' => 'dgut_event_start',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => [
            [
                'key' => 'dgut_event_performance',
                'value' => $performance_id,
                'compare' => '=',
            ],
            [
                'key' => 'dgut_event_start',
                'value' => current_time('mysql'),
                'compare' => '>=',
                'type' => 'DATETIME',
            ],
        ],
        'no_found_rows' => true,
    ]);
}

add_filter('enter_title_here', function (string $title, WP_Post $post): string {
    return $post->post_type === 'dgut_event' ? __('Назва події або вистави', 'dgutheater') : $title;
}, 10, 2);

add_filter('manage_dgut_event_posts_columns', function (array $columns): array {
    $columns['dgut_event_start'] = __('Дата і час', 'dgutheater');
    $columns['dgut_event_performance'] = __('Вистава', 'dgutheater');
    $columns['dgut_event_status'] = __('Квитки', 'dgutheater');
    return $columns;
});

add_filter('manage_edit-dgut_event_sortable_columns', function (array $columns): array {
    $columns['dgut_event_start'] = 'dgut_event_start';
    return $columns;
});

add_action('manage_dgut_event_posts_custom_column', function (string $column, int $post_id): void {
    if ($column === 'dgut_event_start') {
        $date = dgut_afisha_datetime($post_id);
        echo $date ? esc_html(wp_date('d.m.Y H:i', $date->getTimestamp(), wp_timezone())) : '—';
    } elseif ($column === 'dgut_event_performance') {
        $performance_id = dgut_afisha_performance_id($post_id);
        echo $performance_id > 0 ? esc_html(get_the_title($performance_id)) : '—';
    } elseif ($column === 'dgut_event_status') {
        $data = dgut_afisha_event_data($post_id);
        echo esc_html((string) ($data['status'] ?? '—'));
    }
}, 10, 2);

add_action('pre_get_posts', function (WP_Query $query): void {
    if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'dgut_event') {
        return;
    }
    if ($query->get('orderby') === 'dgut_event_start' || !$query->get('orderby')) {
        $query->set('meta_key', 'dgut_event_start');
        $query->set('orderby', 'meta_value');
        if (!$query->get('order')) {
            $query->set('order', 'DESC');
        }
    }
});
