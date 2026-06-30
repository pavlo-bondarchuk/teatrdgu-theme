<?php
$fields = function_exists('get_fields') ? (array) get_fields() : [];
$show_block = $fields['show_block'] ?? true;
if ($show_block !== true) {
    return;
}

$source_mode = (string) ($fields['source_mode'] ?? 'prototype');
$posts_to_show = max(1, (int) ($fields['posts_to_show'] ?? 3));
$manual_ids = array_values(array_filter(array_map('intval', (array) ($fields['manual_performances'] ?? []))));
$slides = [];

if ($source_mode === 'manual' && !empty($manual_ids)) {
    $posts = get_posts([
        'post_type' => 'performance',
        'post_status' => 'publish',
        'post__in' => $manual_ids,
        'orderby' => 'post__in',
        'posts_per_page' => count($manual_ids),
    ]);

    if (!empty($posts)) {
        foreach ($posts as $post) {
            $slides[] = dgut_get_performance_card_data($post);
        }
    }
}

if (empty($slides)) {
    $posts = get_posts([
        'post_type' => 'performance',
        'post_status' => 'publish',
        'orderby' => 'menu_order date',
        'order' => 'ASC',
        'posts_per_page' => $posts_to_show,
    ]);

    foreach ($posts as $post) {
        $slides[] = dgut_get_performance_card_data($post);
    }
}

if (empty($slides)) {
    return;
}

$title_tag = dgut_block_heading_tag($fields['title_tag'] ?? 'h1', 'h1');
?>
<section id="top" class="dgut-hero" data-hero-slider>
    <div class="dgut-hero__slides">
        <?php foreach ($slides as $index => $slide) : ?>
            <article class="dgut-hero__slide<?php echo $index === 0 ? ' is-active' : ''; ?>" data-hero-slide>
                <?php if (!empty($slide['image'])) : ?>
                    <?php echo dgut_img($slide['image'], $slide['title'], 'dgut-hero__image', [
                        'loading' => $index === 0 ? 'eager' : 'lazy',
                        'fetchpriority' => $index === 0 ? 'high' : 'auto',
                        'style' => 'object-position:' . $slide['focus'],
                    ]); ?>
                <?php endif; ?>
                <div class="dgut-hero__shade" aria-hidden="true"></div>
                <div class="dgut-hero__fade" aria-hidden="true"></div>
                <div class="container dgut-hero__content">
                    <p class="eyebrow dgut-hero__eyebrow"><?php echo esc_html($slide['genre'] ?? ''); ?></p>
                    <<?php echo tag_escape($title_tag); ?> class="display dgut-hero__title"><?php echo esc_html($slide['title']); ?></<?php echo tag_escape($title_tag); ?>>
                    <p class="dgut-hero__desc"><?php echo esc_html($slide['excerpt'] ?? ''); ?></p>
                    <p class="dgut-hero__date"><?php echo dgut_ui_icon('clock'); ?><?php echo esc_html($slide['date']); ?></p>
                    <a class="btn dgut-hero__button" href="<?php echo esc_url($slide['permalink'] ?? '#repertoire'); ?>"><?php esc_html_e('Детальніше', 'dgutheater'); ?></a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <div class="container dgut-hero__controls" aria-label="<?php esc_attr_e('Hero slides', 'dgutheater'); ?>">
        <button class="dgut-hero__arrow" type="button" data-hero-prev aria-label="<?php esc_attr_e('Попередній слайд', 'dgutheater'); ?>">
            <?php echo dgut_ui_icon('chevron-left'); ?>
        </button>
        <div class="dgut-hero__dots">
        <?php foreach ($slides as $index => $slide) : ?>
            <button type="button" data-hero-dot="<?php echo esc_attr((string) $index); ?>" class="<?php echo $index === 0 ? 'is-active' : ''; ?>">
                <span class="screen-reader-text"><?php echo esc_html($slide['title']); ?></span>
            </button>
        <?php endforeach; ?>
        </div>
        <button class="dgut-hero__arrow" type="button" data-hero-next aria-label="<?php esc_attr_e('Наступний слайд', 'dgutheater'); ?>">
            <?php echo dgut_ui_icon('chevron-right'); ?>
        </button>
    </div>
</section>
