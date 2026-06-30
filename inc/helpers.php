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
        'chevron-left' => '<svg ' . $attrs . '><path d="m15 18-6-6 6-6"/></svg>',
        'chevron-right' => '<svg ' . $attrs . '><path d="m9 18 6-6-6-6"/></svg>',
        'external-link' => '<svg ' . $attrs . '><path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/></svg>',
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

function dgut_get_performance_card_data(WP_Post|int $post): array
{
    $post = get_post($post);

    if (!$post) {
        return [];
    }

    $post_id = $post->ID;
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
        'excerpt' => $excerpt,
        'image' => get_the_post_thumbnail_url($post, 'hero-slider') ?: '',
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
