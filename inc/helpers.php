<?php
if (!defined('ABSPATH')) {
    exit;
}

function dgut_option(string $key, mixed $default = ''): mixed
{
    if (function_exists('get_field')) {
        $value = get_field($key, 'option');
        if ($value !== null && $value !== false && $value !== '') {
            return $value;
        }
    }

    $value = get_theme_mod($key, $default);
    return $value !== '' ? $value : $default;
}

function dgut_asset(string $relative): string
{
    return DGUTHEME_URI . '/assets/' . ltrim($relative, '/');
}

function dgut_media(string $relative): string
{
    return dgut_asset('media/' . ltrim($relative, '/'));
}

function dgut_img(string $src, string $alt = '', string $class = '', array $attrs = []): string
{
    $attrs = array_merge([
        'src' => $src,
        'alt' => $alt,
        'class' => $class,
        'loading' => 'lazy',
        'decoding' => 'async',
    ], $attrs);

    $html = '<img';
    foreach ($attrs as $name => $value) {
        if ($value === '' || $value === null) {
            continue;
        }
        $html .= ' ' . esc_attr($name) . '="' . esc_attr((string) $value) . '"';
    }
    return $html . '>';
}

function dgut_responsive_news_image(int $attachment_id, string $alt = '', string $size = 'dgut-news-card', string $sizes = '', array $attrs = []): string
{
    if ($attachment_id <= 0) {
        return '';
    }

    $attrs = array_merge([
        'alt' => $alt,
        'loading' => 'lazy',
        'decoding' => 'async',
    ], $attrs);

    if ($sizes !== '') {
        $attrs['sizes'] = $sizes;
    }

    return wp_get_attachment_image($attachment_id, $size, false, $attrs);
}

function dgut_responsive_news_image_from_url(string $src, string $alt = '', string $size = 'dgut-news-grid-card', string $sizes = '', array $attrs = []): string
{
    if ($src === '') {
        return '';
    }

    $attachment_id = attachment_url_to_postid($src);
    if ($attachment_id <= 0) {
        $original_src = preg_replace('/-\d+x\d+(?=\.[^.]+$)/', '', $src);
        if (is_string($original_src) && $original_src !== $src) {
            $attachment_id = attachment_url_to_postid($original_src);
        }
    }
    if ($attachment_id > 0) {
        return dgut_responsive_news_image($attachment_id, $alt, $size, $sizes, $attrs);
    }

    if ($sizes !== '') {
        $attrs['sizes'] = $sizes;
    }

    return dgut_img($src, $alt, '', $attrs);
}

function dgut_hero_picture(int $attachment_id, string $alt = '', string $focus = 'center center', array $attrs = []): string
{
    if ($attachment_id <= 0) {
        return '';
    }

    $mobile_image = image_get_intermediate_size($attachment_id, 'dgut-hero-slide-mobile');
    if (!$mobile_image) {
        $mobile_image = image_get_intermediate_size($attachment_id, 'medium_large');
    }
    if (!$mobile_image) {
        $mobile_image = image_get_intermediate_size($attachment_id, 'medium');
    }
    $desktop_image = image_get_intermediate_size($attachment_id, 'dgut-hero-slide');
    if (!$desktop_image) {
        $desktop_src = wp_get_attachment_image_src($attachment_id, 'full');
        if ($desktop_src) {
            $desktop_image = [
                'url' => $desktop_src[0],
                'width' => $desktop_src[1],
            ];
        }
    }
    $attrs = array_merge([
        'class' => 'dgut-hero__image',
        'loading' => 'lazy',
        'decoding' => 'async',
        'style' => 'object-position:' . $focus,
        'sizes' => '(max-width: 780px) 100vw, 100vw',
    ], $attrs);

    $image = wp_get_attachment_image($attachment_id, 'dgut-hero-slide', false, array_merge($attrs, [
        'alt' => $alt,
    ]));
    if ($image === '') {
        return '';
    }

    $sources = '';
    if (is_array($mobile_image) && !empty($mobile_image['url']) && !empty($mobile_image['width'])) {
        $sources .= sprintf(
            '<source media="(max-width: 780px)" srcset="%s" sizes="100vw">',
            esc_attr($mobile_image['url'] . ' ' . (int) $mobile_image['width'] . 'w')
        );
    }
    if (is_array($desktop_image) && !empty($desktop_image['url']) && !empty($desktop_image['width'])) {
        $sources .= sprintf(
            '<source media="(min-width: 781px)" srcset="%s" sizes="100vw">',
            esc_attr($desktop_image['url'] . ' ' . (int) $desktop_image['width'] . 'w')
        );
    }

    return '<picture class="dgut-hero__picture">' . $sources . $image . '</picture>';
}

function dgut_social_links(): array
{
    return [
        'facebook' => [
            'label' => 'Facebook',
            'url' => dgut_option('dgut_facebook_url', 'https://www.facebook.com/teatrdgu/'),
        ],
        'instagram' => [
            'label' => 'Instagram',
            'url' => dgut_option('dgut_instagram_url', 'https://www.instagram.com/teatrdgu/'),
        ],
        'telegram' => [
            'label' => 'Telegram',
            'url' => dgut_option('dgut_telegram_url', 'https://t.me/teatrdgu'),
        ],
    ];
}

function dgut_social_icon(string $name): string
{
    $icons = [
        'facebook' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 22v-8h2.7l.4-3.1h-3.1v-2c0-.9.3-1.5 1.6-1.5h1.7V4.6c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3v2.1H7.3V14h2.8v8h3.4z"/></svg>',
        'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.8 2h8.4A5.8 5.8 0 0 1 22 7.8v8.4a5.8 5.8 0 0 1-5.8 5.8H7.8A5.8 5.8 0 0 1 2 16.2V7.8A5.8 5.8 0 0 1 7.8 2zm0 2A3.8 3.8 0 0 0 4 7.8v8.4A3.8 3.8 0 0 0 7.8 20h8.4a3.8 3.8 0 0 0 3.8-3.8V7.8A3.8 3.8 0 0 0 16.2 4H7.8zm8.7 2.2a1.3 1.3 0 1 1 0 2.6 1.3 1.3 0 0 1 0-2.6zM12 7.1a4.9 4.9 0 1 1 0 9.8 4.9 4.9 0 0 1 0-9.8zm0 2a2.9 2.9 0 1 0 0 5.8 2.9 2.9 0 0 0 0-5.8z"/></svg>',
        'telegram' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm4.1 6.3c.2 0 .5.1.6.2.1.1.2.3.2.5v.6c-.2 1.6-.8 5-1.1 6.7-.1.7-.4.9-.7.9-.6.1-1-.4-1.6-.8-.9-.6-1.4-1-2.2-1.5-1-.7-.3-1 .2-1.6.1-.1 2.7-2.5 2.8-2.7 0 0 0-.1-.1-.2h-.2c-.1 0-1.5 1-4.2 2.8-.4.3-.8.4-1.1.4-.4 0-1.1-.2-1.6-.4-.6-.2-1.1-.3-1.1-.7 0-.2.3-.4.8-.6 2.9-1.3 4.9-2.1 5.8-2.5 2.8-1.1 3.3-1.3 3.5-1.1z"/></svg>',
    ];

    return $icons[$name] ?? '';
}

function dgut_ui_icon(string $name): string
{
    $attrs = 'viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"';
    $icons = [
        'map-pin' => '<svg ' . $attrs . '><path d="M20 10c0 4.99-5.52 10.35-7.38 12.02a.94.94 0 0 1-1.24 0C9.52 20.35 4 14.99 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>',
        'phone' => '<svg ' . $attrs . '><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.68 2.8a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.32 1.84.55 2.8.68A2 2 0 0 1 22 16.92Z"/></svg>',
        'mail' => '<svg ' . $attrs . '><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>',
        'clock' => '<svg ' . $attrs . '><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>',
        'calendar' => '<svg ' . $attrs . '><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"></path><path d="M12 14h.01"></path><path d="M16 14h.01"></path><path d="M8 18h.01"></path><path d="M12 18h.01"></path><path d="M16 18h.01"></path></svg>',
        'ticket' => '<svg ' . $attrs . '><path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2Z"/><path d="M13 5v2"/><path d="M13 17v2"/><path d="M13 11v2"/></svg>',
        'users' => '<svg ' . $attrs . '><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'chevron-left' => '<svg ' . $attrs . '><path d="m15 18-6-6 6-6"/></svg>',
        'chevron-right' => '<svg ' . $attrs . '><path d="m9 18 6-6-6-6"/></svg>',
        'external-link' => '<svg ' . $attrs . '><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>',
        'user' => '<svg  ' . $attrs . '><circle cx="12" cy="8" r="5"></circle><path d="M20 21a8 8 0 0 0-16 0"></path></svg>',
        'sparkles' => '<svg ' . $attrs . '><path d="m12 3-1.9 5.8L4 11l6.1 2.2L12 19l1.9-5.8L20 11l-6.1-2.2Z"/><path d="M5 3v4"/><path d="M3 5h4"/><path d="M19 17v4"/><path d="M17 19h4"/></svg>',
    ];

    return $icons[$name] ?? '';
}

function dgut_get_image_from_field(mixed $field, string $fallback = ''): string
{
    if (is_array($field) && isset($field['url'])) {
        return (string) $field['url'];
    }
    if (is_numeric($field)) {
        $url = wp_get_attachment_image_url((int) $field, 'full');
        return $url ?: $fallback;
    }
    if (is_string($field) && $field !== '') {
        return $field;
    }
    return $fallback;
}

function dgut_front_field(array $fields, string $key, mixed $default = ''): mixed
{
    if (array_key_exists($key, $fields) && $fields[$key] !== null && $fields[$key] !== '') {
        return $fields[$key];
    }

    return $default;
}

function dgut_front_bool(array $fields, string $key, bool $default = true): bool
{
    if (array_key_exists($key, $fields) && $fields[$key] !== null && $fields[$key] !== '') {
        return (bool) $fields[$key];
    }

    return $default;
}

function dgut_front_image(mixed $field, string $fallback = ''): string
{
    return dgut_get_image_from_field($field, $fallback);
}

function dgut_front_posts(string $post_type, int $limit, array $args = []): array
{
    return get_posts(array_merge([
        'post_type' => $post_type,
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'orderby' => 'menu_order date',
        'order' => 'ASC',
    ], $args));
}

function dgut_front_page_url(string $slug, string $fallback): string
{
    $page = get_page_by_path($slug);
    return $page ? get_permalink($page) : home_url($fallback);
}

function dgut_page_fields(): array
{
    $fields = function_exists('get_fields') ? get_fields() : [];
    return is_array($fields) ? $fields : [];
}

function dgut_about_defaults(): array
{
    return [
        'about_hero_eyebrow' => __('Про театр', 'dgutheater'),
        'about_hero_title' => 'Театр «ДГУ»',
        'about_hero_subtitle' => __('Український театр і культурна платформа Дніпра', 'dgutheater'),
        'about_hero_tagline' => 'ДНІПРО_ГОРДІСТЬ_УКРАЇНИ',
        'about_history_cards' => [
            [
                'text' => 'Наша історія почалася наприкінці 1980-х років із творчої спільноти, сформованої навколо команди «КВН» Дніпропетровського державного університету. У 1994 році з\'явився Театр «КВН ДГУ», який поєднав авторську драматургію, інтелектуальний гумор, експеримент та живий діалог із глядачем.',
            ],
            [
                'text' => 'У 2023 році театр здійснив «деКВНізацію» - ми відмовилися від абревіатури «КВН» у назві як від радянського рудименту. Це стало не розривом із власним минулим, а переосмисленням нашої ідентичності в сучасній Україні.',
            ],
            [
                'text' => 'Сьогодні Театр «ДГУ» працює на перетині різних жанрів і театральних практик. Герої вистав приходять з біблійних часів, минулого століття чи сьогоднішніх новин, але всі ці історії так чи інакше ведуть до Дніпра.',
            ],
            [
                'text' => 'Театр «ДГУ» - вже більше, ніж сцена. Це культурний бренд Дніпра, що поєднує традицію, сучасне мистецтво та проактивну творчу спільноту.',
            ],
        ],
        'about_work_show' => true,
        'about_work_eyebrow' => __('Що ми робимо', 'dgutheater'),
        'about_work_title' => __('Театр, проєкти та місто', 'dgutheater'),
        'about_work_cards' => [
            [
                'icon' => 'ticket',
                'title' => 'Створюємо вистави',
                'text' => 'Працюємо з авторською драматургією, сучасними темами та різними театральними формами.',
            ],
            [
                'icon' => 'sparkles',
                'title' => 'Реалізуємо культурні проєкти',
                'text' => 'Поєднуємо театр, сучасне мистецтво, документальні та просвітницькі ініціативи.',
            ],
            [
                'icon' => 'map-pin',
                'title' => 'Популяризуємо Дніпро',
                'text' => 'Розповідаємо про місто через його постаті, пам\'ять, культуру та живу спільноту.',
            ],
        ],
        'about_initiatives_show' => true,
        'about_initiatives_eyebrow' => __('Серед наших ініціатив', 'dgutheater'),
        'about_initiatives_title' => __('Культурна робота поза сценою', 'dgutheater'),
        'about_initiatives' => [
            [
                'text' => 'Літературна премія імені Валер\'яна Підмогильного',
            ],
            [
                'text' => 'Дніпровська літературна резиденція Українського ПЕН',
            ],
            [
                'text' => 'Документальні та просвітницькі проєкти про місто',
            ],
        ],
    ];
}

function dgut_about_field(array $fields, string $key, mixed $default = ''): mixed
{
    return array_key_exists($key, $fields) ? $fields[$key] : $default;
}

function dgut_about_bool(array $fields, string $key, bool $default = true): bool
{
    $value = dgut_about_field($fields, $key, $default);
    if ($value === null || $value === '') {
        return false;
    }

    return (bool) $value;
}

function dgut_about_rows(array $fields, string $key, array $default = []): array
{
    $rows = dgut_about_field($fields, $key, $default);
    if (!is_array($rows)) {
        return [];
    }

    $clean_rows = [];
    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $clean_row = array_map(static fn(mixed $value): mixed => is_string($value) ? trim($value) : $value, $row);
        $has_content = false;
        foreach ($clean_row as $value) {
            if (is_array($value)) {
                $has_content = !empty(array_filter($value));
            } elseif ($value !== '' && $value !== null && $value !== false) {
                $has_content = true;
            }
            if ($has_content) {
                break;
            }
        }

        if ($has_content) {
            $clean_rows[] = $clean_row;
        }
    }

    return $clean_rows;
}

function dgut_about_initiative_posts(mixed $category): array
{
    $category_id = dgut_about_initiative_category_id($category);
    if ($category_id <= 0) {
        return [];
    }

    $posts = get_posts([
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'cat' => $category_id,
        'orderby' => 'date',
        'order' => 'DESC',
        'ignore_sticky_posts' => true,
        'no_found_rows' => true,
    ]);

    if (empty($posts)) {
        return [];
    }

    return array_map(static function (WP_Post $post): array {
        return [
            'text' => get_the_title($post),
            'url' => get_permalink($post),
        ];
    }, $posts);
}

function dgut_about_initiative_category_id(mixed $category): int
{
    if ($category instanceof WP_Term) {
        return (int) $category->term_id;
    }

    if (is_array($category)) {
        $first = reset($category);
        return dgut_about_initiative_category_id($first);
    }

    return is_numeric($category) ? (int) $category : 0;
}

function dgut_yoast_breadcrumbs(string $class = 'dgut-breadcrumbs'): string
{
    if (!function_exists('yoast_breadcrumb')) {
        return '';
    }

    $use_icon_separator = dgut_yoast_breadcrumb_separator_is_empty();
    $separator_filter = static fn(): string => dgut_breadcrumb_separator_icon();
    if ($use_icon_separator) {
        add_filter('wpseo_breadcrumb_separator', $separator_filter);
    }

    $breadcrumbs = (string) yoast_breadcrumb(
        '<nav class="' . esc_attr($class) . '" aria-label="' . esc_attr__('Хлібні крихти', 'dgutheater') . '">',
        '</nav>',
        false
    );

    if ($use_icon_separator) {
        remove_filter('wpseo_breadcrumb_separator', $separator_filter);
    }

    return $breadcrumbs;
}

function dgut_yoast_breadcrumb_separator_is_empty(): bool
{
    $yoast_titles = get_option('wpseo_titles', []);
    if (!is_array($yoast_titles) || !array_key_exists('breadcrumbs-sep', $yoast_titles)) {
        return false;
    }

    $separator = html_entity_decode(wp_strip_all_tags((string) $yoast_titles['breadcrumbs-sep']), ENT_QUOTES | ENT_HTML5, get_bloginfo('charset') ?: 'UTF-8');
    $separator = preg_replace('/\x{00A0}/u', ' ', $separator) ?? $separator;

    return trim($separator) === '';
}

function dgut_breadcrumb_separator_icon(): string
{
    return '<svg class="dgut-breadcrumb-separator" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>';
}

function dgut_performance_field(string $key, mixed $default = ''): mixed
{
    if (!function_exists('get_field')) {
        return $default;
    }

    $value = get_field($key);
    return $value !== null && $value !== false && $value !== '' ? $value : $default;
}

function dgut_performance_text_field(string $key, string $default = ''): string
{
    $value = dgut_performance_field($key, $default);
    return is_string($value) ? trim($value) : $default;
}

function dgut_performance_people(string $key): array
{
    $rows = dgut_performance_field($key, []);
    if (!is_array($rows)) {
        return [];
    }

    $people = [];
    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $role = trim((string) ($row['role'] ?? ''));
        $name = trim((string) ($row['name'] ?? ''));

        if ($role === '' && $name === '') {
            continue;
        }

        $people[] = [
            'role' => $role,
            'name' => $name,
        ];
    }

    return $people;
}

function dgut_performance_services(): array
{
    $rows = dgut_performance_field('dgut_performance_ticket_services', []);
    if (!is_array($rows)) {
        return [];
    }

    $services = [];
    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $url = trim((string) ($row['url'] ?? ''));
        if ($url === '') {
            continue;
        }

        $name = trim((string) ($row['name'] ?? ''));
        $description = trim((string) ($row['description'] ?? ''));
        $icon = dgut_get_image_from_field($row['icon'] ?? '');

        if ($name === '' && $description === '' && $icon === '') {
            continue;
        }

        $services[] = [
            'name' => $name,
            'url' => $url,
            'icon' => $icon,
            'description' => $description,
        ];
    }

    return $services;
}

function dgut_performance_credit_line(int $post_id): string
{
    if (!function_exists('get_field')) {
        return '';
    }

    return trim((string) get_field('dgut_performance_hero_credits', $post_id));
}

function dgut_performance_ticket_services(array $services, string $ticket_url): array
{
    if (!empty($services) || $ticket_url === '') {
        return $services;
    }

    return [
        [
            'name' => __('Купити квиток', 'dgutheater'),
            'url' => $ticket_url,
            'icon' => '',
            'description' => '',
        ],
    ];
}

function dgut_performance_gallery(): array
{
    $gallery_raw = dgut_performance_field('dgut_performance_gallery_images', []);
    $gallery = [];

    if (!is_array($gallery_raw)) {
        return $gallery;
    }

    foreach ($gallery_raw as $image) {
        if (is_array($image)) {
            $image_id = (int) ($image['ID'] ?? $image['id'] ?? 0);
            $image_url = (string) ($image['url'] ?? '');

            if ($image_id || $image_url !== '') {
                $gallery[] = [
                    'id' => $image_id,
                    'url' => $image_url,
                    'alt' => (string) ($image['alt'] ?? get_the_title()),
                ];
            }
            continue;
        }

        if (is_numeric($image)) {
            $gallery[] = [
                'id' => (int) $image,
                'url' => '',
                'alt' => get_the_title(),
            ];
            continue;
        }

        if (is_string($image) && trim($image) !== '') {
            $gallery[] = [
                'id' => 0,
                'url' => trim($image),
                'alt' => get_the_title(),
            ];
        }
    }

    return $gallery;
}

function dgut_performance_video_embed(string $video_url): string
{
    if ($video_url === '') {
        return '';
    }

    if (str_contains($video_url, '<iframe')) {
        return wp_kses($video_url, [
            'iframe' => [
                'src' => true,
                'title' => true,
                'width' => true,
                'height' => true,
                'loading' => true,
                'allow' => true,
                'allowfullscreen' => true,
                'frameborder' => true,
            ],
        ]);
    }

    if (str_ends_with(strtolower(parse_url($video_url, PHP_URL_PATH) ?: ''), '.mp4')) {
        return sprintf('<video controls preload="metadata"><source src="%s" type="video/mp4"></video>', esc_url($video_url));
    }

    $embed = wp_oembed_get($video_url);
    if ($embed) {
        return $embed;
    }

    return sprintf(
        '<iframe src="%s" title="%s" loading="lazy" allowfullscreen allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>',
        esc_url($video_url),
        esc_attr(get_the_title())
    );
}

function dgut_get_performance_card_data(WP_Post|int $post): array
{
    $post = get_post($post);

    if (!$post) {
        return [];
    }

    $post_id = $post->ID;
    $thumbnail_id = get_post_thumbnail_id($post);
    $title = get_the_title($post);
    $excerpt = trim((string) get_the_excerpt($post));
    if ($excerpt === '') {
        $excerpt = wp_trim_words(wp_strip_all_tags((string) get_post_field('post_content', $post_id)), 18);
    }

    return [
        'title' => $title,
        'genre' => wp_get_post_terms($post_id, 'performance_genre', ['fields' => 'names'])[0] ?? '',
        'date' => function_exists('get_field') ? (string) get_field('dgut_performance_date', $post_id) : '',
        'duration' => function_exists('get_field') ? (string) get_field('dgut_performance_duration', $post_id) : '',
        'credits' => dgut_performance_credit_line($post_id),
        'excerpt' => $excerpt,
        'image' => get_the_post_thumbnail_url($post, 'dgut-event-grid-card') ?: '',
        'hero_image' => get_the_post_thumbnail_url($post, 'dgut-hero-slide') ?: '',
        'thumbnail_id' => $thumbnail_id ?: 0,
        'permalink' => get_permalink($post),
        'focus' => function_exists('get_field') ? ((string) get_field('dgut_performance_image_focus', $post_id) ?: 'center top') : 'center top',
    ];
}

function dgut_get_team_member_card_data(WP_Post|int $post): array
{
    $post = get_post($post);

    if (!$post) {
        return [];
    }

    $post_id = $post->ID;

    return [
        'name' => get_the_title($post),
        'role' => function_exists('get_field') ? (string) get_field('dgut_team_member_role', $post_id) : '',
        'image' => get_the_post_thumbnail_url($post, 'dgut-card') ?: '',
        'focus' => function_exists('get_field') ? ((string) get_field('dgut_team_member_image_focus', $post_id) ?: 'center top') : 'center top',
        'url' => get_permalink($post),
    ];
}

function dgut_repertoire_posts(): array
{
    $args = [
        'post_type' => 'performance',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'menu_order date',
        'order' => 'ASC',
    ];

    if (function_exists('pll_current_language')) {
        $language = pll_current_language('slug');
        if (is_string($language) && $language !== '') {
            $args['lang'] = $language;
        }
    }

    return get_posts($args);
}

if (!function_exists('dgut_repertoire_filters')) {
    function dgut_repertoire_filters(): array
    {
        $is_english = function_exists('dgut_repertoire_is_english') && dgut_repertoire_is_english();

        $genres = get_terms([
            'taxonomy' => 'performance_genre',
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC',
        ]);

        if (is_wp_error($genres) || empty($genres)) {
            return [];
        }

        return array_map(static function ($genre) use ($is_english) {
            $name_ua = get_term_meta($genre->term_id, 'dgut_performance_genre_ua', true);

            return [
                'id' => (int) $genre->term_id,
                'slug' => $genre->slug,
                'name' => $is_english ? $genre->name : ($name_ua ?: $genre->name),
            ];
        }, $genres);
    }
}

function dgut_repertoire_is_english(): bool
{
    return function_exists('pll_current_language') && pll_current_language('slug') === 'en';
}

function dgut_repertoire_label(string $key): string
{
    $labels = [
        'title' => [
            'ua' => 'Репертуар',
            'en' => 'Repertoire',
        ],
        'filter_aria' => [
            'ua' => 'Фільтр репертуару',
            'en' => 'Repertoire filter',
        ],
        'details' => [
            'ua' => 'Детальніше',
            'en' => 'Details',
        ],
        'empty' => [
            'ua' => 'За цим фільтром вистав поки немає.',
            'en' => 'There are no performances for this filter yet.',
        ],
    ];

    $language = dgut_repertoire_is_english() ? 'en' : 'ua';

    return $labels[$key][$language] ?? '';
}

function dgut_repertoire_card_data($post): array
{
    $post_id = is_object($post) ? (int) $post->ID : (int) $post;

    $terms = wp_get_post_terms($post_id, 'performance_genre');

    $genre_ids = [];
    $genre_names = [];

    if (!is_wp_error($terms) && !empty($terms)) {
        foreach ($terms as $term) {
            $genre_ids[] = (string) $term->term_id;
            $genre_names[] = $term->name;
        }
    }

    $fields = function_exists('get_fields') ? get_fields($post_id) : [];

    return [
        'title' => get_the_title($post_id),
        'permalink' => get_permalink($post_id),
        'genre' => $genre_names ? $genre_names[0] : '',
        'genre_ids' => $genre_ids,
        'date' => isset($fields['dgut_performance_date']) ? (string) $fields['dgut_performance_date'] : '',
        'excerpt' => get_the_excerpt($post_id),
        'image' => get_the_post_thumbnail_url($post_id, 'dgut-card') ?: '',
        'focus' => isset($fields['dgut_performance_image_focus']) ? (string) $fields['dgut_performance_image_focus'] : 'center top',
        'filter_text' => mb_strtolower(get_the_title($post_id) . ' ' . get_the_excerpt($post_id) . ' ' . implode(' ', $genre_names)),
    ];
}

add_filter('nav_menu_css_class', function ($classes, $item) {
    $item_url = isset($item->url) ? untrailingslashit($item->url) : '';

    $active_urls = [];

    if (is_singular('performance')) {
        $active_urls[] = untrailingslashit(home_url('/repertuar/'));
        $archive_url = get_post_type_archive_link('performance');

        if ($archive_url) {
            $active_urls[] = untrailingslashit($archive_url);
        }
    }

    if (is_singular('post')) {
        $active_urls[] = untrailingslashit(home_url('/novyny/'));

        $posts_page_id = (int) get_option('page_for_posts');

        if ($posts_page_id) {
            $active_urls[] = untrailingslashit(get_permalink($posts_page_id));
        }
    }

    if (empty($active_urls) || !in_array($item_url, array_unique($active_urls), true)) {
        return $classes;
    }

    $classes[] = 'current-menu-item';
    $classes[] = 'current_page_item';
    $classes[] = 'current-menu-ancestor';

    return array_unique($classes);
}, 10, 2);
add_filter('nav_menu_link_attributes', function ($atts, $item) {
    $item_url = isset($item->url) ? untrailingslashit($item->url) : '';

    $active_urls = [];

    if (is_singular('performance')) {
        $active_urls[] = untrailingslashit(home_url('/repertuar/'));
        $archive_url = get_post_type_archive_link('performance');

        if ($archive_url) {
            $active_urls[] = untrailingslashit($archive_url);
        }
    }

    if (is_singular('post')) {
        $active_urls[] = untrailingslashit(home_url('/novyny/'));

        $posts_page_id = (int) get_option('page_for_posts');

        if ($posts_page_id) {
            $active_urls[] = untrailingslashit(get_permalink($posts_page_id));
        }
    }

    if (!empty($active_urls) && in_array($item_url, array_unique($active_urls), true)) {
        $atts['aria-current'] = 'page';
    }

    return $atts;
}, 10, 2);
