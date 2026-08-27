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
    if (!function_exists('get_field') || !function_exists('update_field')) {
        return;
    }

    $front_page_id = (int) get_option('page_on_front');
    if ($front_page_id <= 0) {
        return;
    }

    $front_page_ids = [$front_page_id];
    if (function_exists('pll_get_post_translations')) {
        $front_page_ids = array_merge($front_page_ids, array_values((array) pll_get_post_translations($front_page_id)));
    }

    foreach (array_unique(array_map('intval', $front_page_ids)) as $page_id) {
        if ($page_id <= 0 || get_post_meta($page_id, '_dgut_home_hero_repeater_migrated_v2', true)) {
            continue;
        }

        $performance_ids = get_post_meta($page_id, 'home_hero_performances', true);
        $performance_ids = array_values(array_filter(array_map('intval', (array) $performance_ids)));
        $legacy_count = max(1, (int) get_post_meta($page_id, 'home_hero_count', true));
        $performance_ids = array_slice($performance_ids, 0, $legacy_count);
        if (!$performance_ids) {
            $existing_rows = get_field('home_hero_slides', $page_id);
            if (is_array($existing_rows) && $existing_rows) {
                update_post_meta($page_id, '_dgut_home_hero_repeater_migrated_v2', '1');
            }
            continue;
        }

        $did_switch_locale = false;
        if (function_exists('pll_get_post_language')) {
            $page_locale = (string) pll_get_post_language($page_id, 'locale');
            if ($page_locale !== '') {
                $did_switch_locale = switch_to_locale($page_locale);
            }
        }
        $link_title = __('Детальніше', 'dgutheater');

        $rows = [];
        foreach ($performance_ids as $performance_id) {
            $performance = dgut_get_performance_card_data($performance_id);
            if (!$performance) {
                continue;
            }

            $rows[] = [
                'image' => (int) ($performance['thumbnail_id'] ?? 0),
                'focus' => (string) ($performance['focus'] ?? 'center top'),
                'eyebrow' => (string) ($performance['genre'] ?? ''),
                'title' => (string) ($performance['title'] ?? ''),
                'credits' => (string) ($performance['credits'] ?? ''),
                'date' => (string) ($performance['date'] ?? ''),
                'link' => [
                    'title' => $link_title,
                    'url' => (string) ($performance['permalink'] ?? ''),
                    'target' => '',
                ],
            ];
        }

        if ($did_switch_locale) {
            restore_previous_locale();
        }

        if ($rows) {
            update_field('field_dgut_home_hero_slides', $rows, $page_id);
            $saved_rows = get_field('home_hero_slides', $page_id);
            if (is_array($saved_rows) && count($saved_rows) === count($rows)) {
                update_post_meta($page_id, '_dgut_home_hero_repeater_migrated_v2', '1');
            }
        }
    }
}, 30);

add_action('init', function (): void {
    if (!function_exists('get_field') || !function_exists('update_field')) {
        return;
    }

    $front_page_id = (int) get_option('page_on_front');
    if ($front_page_id <= 0 || get_post_meta($front_page_id, '_dgut_home_hero_afisha_slides_migrated_v1', true)) {
        return;
    }

    $rows = get_field('home_hero_slides', $front_page_id);
    if (!is_array($rows)) {
        $rows = [];
    }

    $existing_urls = array_filter(array_map(static function ($row): string {
        return is_array($row) && is_array($row['link'] ?? null)
            ? untrailingslashit((string) ($row['link']['url'] ?? ''))
            : '';
    }, $rows));

    $event_slugs = [
        'sidur',
        'vilni-stosunki',
        'povernennya-hlopchika-abo-ostannya-kazka-pro-gologo-korolya',
    ];

    foreach ($event_slugs as $event_slug) {
        $event_post = get_page_by_path($event_slug, OBJECT, 'dgut_event');
        if (!$event_post instanceof WP_Post || $event_post->post_status !== 'publish') {
            continue;
        }

        $event = dgut_afisha_event_data($event_post);
        $event_url = untrailingslashit((string) ($event['permalink'] ?? ''));
        if ($event_url === '' || in_array($event_url, $existing_urls, true)) {
            continue;
        }

        $performance_id = (int) ($event['performance_id'] ?? 0);
        $rows[] = [
            'image' => (int) ($event['image_id'] ?? 0),
            'focus' => 'center top',
            'eyebrow' => (string) ($event['type'] ?? ''),
            'title' => (string) ($event['title'] ?? ''),
            'credits' => $performance_id > 0 ? dgut_performance_credit_line($performance_id) : '',
            'date' => trim((string) ($event['date'] ?? '') . ' ' . (string) ($event['time'] ?? '')),
            'link' => [
                'title' => __('Детальніше', 'dgutheater'),
                'url' => (string) ($event['permalink'] ?? ''),
                'target' => '',
            ],
        ];
        $existing_urls[] = $event_url;
    }

    update_field('field_dgut_home_hero_slides', $rows, $front_page_id);
    $saved_rows = get_field('home_hero_slides', $front_page_id);
    if (is_array($saved_rows) && count($saved_rows) === count($rows)) {
        update_post_meta($front_page_id, '_dgut_home_hero_afisha_slides_migrated_v1', '1');
    }
}, 40);

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
