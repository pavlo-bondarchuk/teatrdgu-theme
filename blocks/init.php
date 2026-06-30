<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('init', function (): void {
    $blocks_directory = DGUTHEME_DIR . '/blocks';

    if (!is_dir($blocks_directory)) {
        return;
    }

    $block_dirs = array_diff(scandir($blocks_directory) ?: [], ['..', '.', 'init.php']);

    foreach ($block_dirs as $block_dir) {
        $block_path = $blocks_directory . '/' . $block_dir;

        if (!is_dir($block_path) || !is_readable($block_path . '/block.json')) {
            continue;
        }

        register_block_type($block_path);
    }
});

add_filter('should_load_separate_core_block_assets', '__return_true');
