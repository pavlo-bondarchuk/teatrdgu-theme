<?php
$fields = isset($args['fields']) && is_array($args['fields']) ? $args['fields'] : [];
$team = isset($args['team']) && is_array($args['team']) ? $args['team'] : [];

if (!dgut_front_bool($fields, 'home_team_show', true) || empty($team)) {
    return;
}
?>
<section class="section dgut-team" data-carousel data-carousel-desktop="4" data-carousel-tablet="2" data-carousel-mobile="1">
    <div class="container">
        <div class="dgut-team__top">
            <div class="slider-controls">
                <button class="slider-arrow" type="button" data-carousel-prev aria-label="<?php esc_attr_e('Попередня група команди', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-left'); ?></button>
                <button class="slider-arrow" type="button" data-carousel-next aria-label="<?php esc_attr_e('Наступна група команди', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-right'); ?></button>
            </div>
        </div>
        <div class="dgut-team__track" data-carousel-track>
            <?php foreach ($team as $person) : ?>
                <article class="card dgut-team-card">
                    <?php if (!empty($person['image'])) : ?>
                        <div class="media-frame dgut-team-card__image"><?php echo dgut_img($person['image'], $person['name'], '', ['style' => 'object-position:' . ($person['focus'] ?? 'center top')]); ?></div>
                    <?php endif; ?>
                    <div class="dgut-team-card__body">
                        <h3><?php echo esc_html($person['name']); ?></h3>
                        <?php if (!empty($person['role'])) : ?>
                            <p><?php echo esc_html($person['role']); ?></p>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
