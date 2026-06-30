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
