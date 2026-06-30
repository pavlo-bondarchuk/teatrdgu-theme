<?php
$fields = function_exists('get_fields') ? (array) get_fields() : [];
$show_block = $fields['show_block'] ?? true;
if ($show_block !== true) {
    return;
}

$title = $fields['title'] ?? __('Репертуар', 'dgutheater');
$title_tag = dgut_block_heading_tag($fields['title_tag'] ?? 'h2');
$posts = get_posts([
    'post_type' => 'performance',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'menu_order date',
    'order' => 'ASC',
]);
$items = [];
if ($posts) {
    foreach ($posts as $post) {
        $items[] = dgut_get_performance_card_data($post);
    }
}

if (empty($items)) {
    return;
}
?>
<section id="repertoire" class="section dgut-repertoire" data-carousel data-carousel-step="page">
    <div class="container">
        <div class="dgut-section-head">
            <<?php echo tag_escape($title_tag); ?> class="section-title"><?php echo esc_html($title); ?></<?php echo tag_escape($title_tag); ?>>
            <div class="dgut-repertoire__actions">
                <a class="dgut-repertoire__all" href="<?php echo esc_url(home_url('/repertoire/')); ?>">
                    <?php esc_html_e('Весь репертуар', 'dgutheater'); ?>
                    <span aria-hidden="true">→</span>
                </a>
                <div class="slider-controls" aria-label="<?php esc_attr_e('Repertoire slider', 'dgutheater'); ?>">
                    <button class="slider-arrow" type="button" data-carousel-prev aria-label="<?php esc_attr_e('Попередня група репертуару', 'dgutheater'); ?>">
                        <?php echo dgut_ui_icon('chevron-left'); ?>
                    </button>
                    <button class="slider-arrow" type="button" data-carousel-next aria-label="<?php esc_attr_e('Наступна група репертуару', 'dgutheater'); ?>">
                        <?php echo dgut_ui_icon('chevron-right'); ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="dgut-repertoire__viewport">
            <div class="dgut-repertoire__grid" data-carousel-track>
                <?php foreach ($items as $item) : ?>
                    <article class="card dgut-performance-card">
                        <a href="<?php echo esc_url($item['permalink'] ?? '#tickets'); ?>">
                            <div class="media-frame dgut-performance-card__image">
                                <?php if (!empty($item['image'])) : ?>
                                    <?php echo dgut_img($item['image'], $item['title'], '', ['style' => 'object-position:' . ($item['focus'] ?? 'center')]); ?>
                                <?php endif; ?>
                                <span class="dgut-performance-card__badge"><?php echo esc_html($item['genre'] ?? ''); ?></span>
                            </div>
                            <div class="dgut-performance-card__body">
                                <h3><?php echo esc_html($item['title']); ?></h3>
                                <p><?php echo esc_html($item['excerpt']); ?></p>
                                <span class="dgut-performance-card__date"><?php echo dgut_ui_icon('clock'); ?><?php echo esc_html($item['date']); ?></span>
                                <span class="btn dgut-performance-card__button"><?php esc_html_e('Детальніше', 'dgutheater'); ?></span>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
