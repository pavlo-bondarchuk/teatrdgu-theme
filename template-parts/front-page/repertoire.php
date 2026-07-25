<?php
$fields = isset($args['fields']) && is_array($args['fields']) ? $args['fields'] : [];
$performances = isset($args['performances']) && is_array($args['performances']) ? $args['performances'] : [];

if (!dgut_front_bool($fields, 'home_repertoire_show', true) || empty($performances)) {
    return;
}

$repertoire_title = (string) dgut_front_field($fields, 'home_repertoire_title');
$archive_link = dgut_front_field($fields, 'home_repertoire_archive_link', []);
$archive_link_url = is_array($archive_link) ? (string) ($archive_link['url'] ?? '') : '';
$archive_link_title = is_array($archive_link) ? (string) ($archive_link['title'] ?? '') : '';
$archive_link_target = is_array($archive_link) ? (string) ($archive_link['target'] ?? '') : '';
?>
<section id="repertoire" class="section dgut-repertoire" data-carousel data-carousel-desktop="3" data-carousel-tablet="2" data-carousel-mobile="1">
    <div class="container">
        <div class="dgut-section-head">
            <?php if ($repertoire_title !== '') : ?>
                <h2 class="section-title">
                    <?php echo esc_html($repertoire_title); ?>
                </h2>
            <?php endif; ?>
            <div class="dgut-section-actions">
                <?php if ($archive_link_url !== '') : ?>
                    <a class="dgut-section-link" href="<?php echo esc_url($archive_link_url); ?>" <?php echo $archive_link_target !== '' ? ' target="' . esc_attr($archive_link_target) . '"' : ''; ?><?php echo $archive_link_target === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>>
                        <?php echo esc_html($archive_link_title ?: __('Весь репертуар', 'dgutheater')); ?> <span aria-hidden="true">→</span>
                    </a>
                <?php endif; ?>
                <div class="slider-controls">
                    <button class="slider-arrow" type="button" data-carousel-prev aria-label="<?php esc_attr_e('Попередня група репертуару', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-left'); ?></button>
                    <button class="slider-arrow" type="button" data-carousel-next aria-label="<?php esc_attr_e('Наступна група репертуару', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-right'); ?></button>
                </div>
            </div>
        </div>
        <div class="dgut-repertoire__viewport">
            <div class="dgut-repertoire__grid" data-carousel-track>
                <?php foreach ($performances as $item) : ?>
                    <article class="card dgut-performance-card">
                        <a href="<?php echo esc_url($item['permalink'] ?? '#tickets'); ?>">
                            <div class="media-frame dgut-performance-card__image">
                                <?php if (!empty($item['thumbnail_id'])) : ?>
                                    <?php echo dgut_responsive_image((int) $item['thumbnail_id'], 'dgut-repertoire-home-card', (string) $item['title'], '(min-width: 1240px) 373px, (min-width: 1024px) calc((100vw - 120px) / 3), (min-width: 640px) calc((100vw - 68px) / 2), calc(100vw - 48px)', ['style' => 'object-position:' . ($item['focus'] ?? 'center')]); ?>
                                <?php elseif (!empty($item['image'])) : ?>
                                    <?php echo dgut_responsive_image_from_url(
                                        (string) $item['image'],
                                        (string) $item['title'],
                                        'dgut-repertoire-home-card',
                                        '(min-width: 1240px) 373px, (min-width: 1024px) calc((100vw - 120px) / 3), (min-width: 640px) calc((100vw - 68px) / 2), calc(100vw - 48px)',
                                        ['style' => 'object-position:' . ($item['focus'] ?? 'center')]
                                    ); ?>
                                <?php endif; ?>
                                <span class="dgut-performance-card__badge"><?php echo esc_html($item['genre'] ?? ''); ?></span>
                                <?php if (!empty($item['age_rating'])) : ?>
                                    <span class="dgut-performance-card__age"><?php echo esc_html($item['age_rating']); ?></span>
                                <?php endif; ?>
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