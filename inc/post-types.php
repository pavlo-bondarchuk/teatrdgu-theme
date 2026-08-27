<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function (): void {
    register_post_type('performance', [
        'labels' => [
            'name' => __('Performances', 'dgutheater'),
            'singular_name' => __('Performance', 'dgutheater'),
        ],
        'public' => true,
        'menu_icon' => 'dashicons-tickets-alt',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
        'show_in_rest' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'repertoire'],
    ]);

    register_taxonomy('performance_genre', 'performance', [
        'labels' => [
            'name' => __('Performance genres', 'dgutheater'),
            'singular_name' => __('Performance genre', 'dgutheater'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
    ]);

    register_post_type('dgut_event', [
        'labels' => [
            'name' => __('Афіша', 'dgutheater'),
            'singular_name' => __('Подія афіші', 'dgutheater'),
            'add_new' => __('Додати подію', 'dgutheater'),
            'add_new_item' => __('Додати подію афіші', 'dgutheater'),
            'edit_item' => __('Редагувати подію афіші', 'dgutheater'),
            'new_item' => __('Нова подія афіші', 'dgutheater'),
            'view_item' => __('Переглянути подію', 'dgutheater'),
            'search_items' => __('Шукати в афіші', 'dgutheater'),
            'not_found' => __('Подій не знайдено', 'dgutheater'),
            'all_items' => __('Уся афіша', 'dgutheater'),
            'menu_name' => __('Афіша', 'dgutheater'),
        ],
        'public' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
        'show_in_rest' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'afisha'],
    ]);

    register_taxonomy('dgut_event_type', 'dgut_event', [
        'labels' => [
            'name' => __('Типи подій', 'dgutheater'),
            'singular_name' => __('Тип події', 'dgutheater'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite' => ['slug' => 'afisha/type'],
    ]);

    register_post_type('team_member', [
        'labels' => [
            'name' => __('Team', 'dgutheater'),
            'singular_name' => __('Team member', 'dgutheater'),
            'add_new_item' => __('Add team member', 'dgutheater'),
            'edit_item' => __('Edit team member', 'dgutheater'),
        ],
        'public' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'editor', 'thumbnail', 'page-attributes', 'revisions'],
        'show_in_rest' => true,
        'has_archive' => false,
        'rewrite' => ['slug' => 'team'],
    ]);
});

add_action('after_switch_theme', function (): void {
    flush_rewrite_rules();
});

add_action('init', function (): void {
    $rewrite_version = '1';
    if (get_option('dgut_afisha_rewrite_version') === $rewrite_version) {
        return;
    }

    flush_rewrite_rules(false);
    update_option('dgut_afisha_rewrite_version', $rewrite_version, false);
}, 99);
