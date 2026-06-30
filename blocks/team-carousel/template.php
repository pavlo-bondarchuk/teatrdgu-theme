<?php
$fields = function_exists('get_fields') ? (array) get_fields() : [];
$show_block = $fields['show_block'] ?? true;
if ($show_block !== true) {
    return;
}

$eyebrow = $fields['eyebrow'] ?? __('Команда', 'dgutheater');
$title = $fields['title'] ?? __('Люди театру', 'dgutheater');
$title_tag = dgut_block_heading_tag($fields['title_tag'] ?? 'h2');
$posts = get_posts([
    'post_type' => 'team_member',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'orderby' => 'menu_order date',
    'order' => 'ASC',
]);
$team = [];
foreach ($posts as $post) {
    $team[] = dgut_get_team_member_card_data($post);
}

if (empty($team)) {
    return;
}
?>
<section class="section dgut-team" data-carousel>
    <div class="container">
        <div class="dgut-team__top">
            <div>
                <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <<?php echo tag_escape($title_tag); ?> class="section-title"><?php echo esc_html($title); ?></<?php echo tag_escape($title_tag); ?>>
            </div>
            <div class="slider-controls">
                <button class="slider-arrow" type="button" data-carousel-prev aria-label="<?php esc_attr_e('Попередня група команди', 'dgutheater'); ?>">‹</button>
                <button class="slider-arrow" type="button" data-carousel-next aria-label="<?php esc_attr_e('Наступна група команди', 'dgutheater'); ?>">›</button>
            </div>
        </div>
        <div class="dgut-team__track" data-carousel-track>
            <?php foreach ($team as $person) : ?>
                <article class="card dgut-team-card">
                    <?php if (!empty($person['image'])) : ?>
                        <div class="media-frame dgut-team-card__image">
                            <?php echo dgut_img($person['image'], $person['name'], '', ['style' => 'object-position:' . ($person['focus'] ?? 'center top')]); ?>
                        </div>
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
