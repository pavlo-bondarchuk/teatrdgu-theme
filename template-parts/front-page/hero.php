<?php
$fields = isset($args['fields']) && is_array($args['fields']) ? $args['fields'] : [];
$hero_slides = isset($args['hero_slides']) && is_array($args['hero_slides']) ? $args['hero_slides'] : [];

if (!dgut_front_bool($fields, 'home_hero_show', true) || empty($hero_slides)) {
    return;
}
?>
<section id="top" class="dgut-hero" data-hero-slider>
    <div class="dgut-hero__slides">
        <?php foreach ($hero_slides as $index => $slide) : ?>
            <article class="dgut-hero__slide<?php echo $index === 0 ? ' is-active' : ''; ?>" data-hero-slide>
                <?php $hero_image = $slide['hero_image'] ?? ($slide['image'] ?? ''); ?>
                <?php $thumbnail_id = (int) ($slide['thumbnail_id'] ?? 0); ?>
                <?php if ($thumbnail_id > 0) : ?>
                    <?php echo dgut_hero_picture($thumbnail_id, (string) $slide['title'], (string) ($slide['focus'] ?? 'center center'), [
                        'loading' => $index === 0 ? 'eager' : 'lazy',
                        'fetchpriority' => $index === 0 ? 'high' : 'auto',
                    ]); ?>
                <?php elseif ($hero_image !== '') : ?>
                    <?php echo dgut_img($hero_image, $slide['title'], 'dgut-hero__image', [
                        'loading' => $index === 0 ? 'eager' : 'lazy',
                        'fetchpriority' => $index === 0 ? 'high' : 'auto',
                        'style' => 'object-position:' . ($slide['focus'] ?? 'center center'),
                    ]); ?>
                <?php endif; ?>
                <div class="dgut-hero__arrows">
                    <button class="dgut-hero__arrow" type="button" data-hero-prev aria-label="<?php esc_attr_e('Попередній слайд', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-left'); ?></button>
                    <button class="dgut-hero__arrow" type="button" data-hero-next aria-label="<?php esc_attr_e('Наступний слайд', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-right'); ?></button>
                </div>
                <div class="dgut-hero__shade" aria-hidden="true"></div>
                <div class="dgut-hero__fade" aria-hidden="true"></div>
                <div class="container dgut-hero__content">
                    <p class="eyebrow dgut-hero__eyebrow"><?php echo esc_html($slide['genre'] ?? ''); ?></p>
                    <div class="display dgut-hero__title"><?php echo esc_html($slide['title']); ?></div>
                    <p class="dgut-hero__desc"><?php echo esc_html($slide['excerpt'] ?? ''); ?></p>
                    <p class="dgut-hero__date"><?php echo dgut_ui_icon('clock'); ?><?php echo esc_html($slide['date'] ?? ''); ?></p>
                    <a class="btn dgut-hero__button" href="<?php echo esc_url($slide['permalink'] ?? '#repertoire'); ?>"><?php esc_html_e('Детальніше', 'dgutheater'); ?></a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
    <div class="container dgut-hero__controls" aria-label="<?php esc_attr_e('Hero slides', 'dgutheater'); ?>">
        <button class="dgut-hero__arrow" type="button" data-hero-prev aria-label="<?php esc_attr_e('Попередній слайд', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-left'); ?></button>
        <div class="dgut-hero__dots">
            <?php foreach ($hero_slides as $index => $slide) : ?>
                <button type="button" data-hero-dot="<?php echo esc_attr((string) $index); ?>" class="<?php echo $index === 0 ? 'is-active' : ''; ?>">
                    <span class="screen-reader-text"><?php echo esc_html($slide['title']); ?></span>
                </button>
            <?php endforeach; ?>
        </div>
        <button class="dgut-hero__arrow" type="button" data-hero-next aria-label="<?php esc_attr_e('Наступний слайд', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-right'); ?></button>
    </div>
</section>
