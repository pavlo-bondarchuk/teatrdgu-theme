<?php
$fields = isset($args['fields']) && is_array($args['fields']) ? $args['fields'] : [];
$defaults = isset($args['defaults']) && is_array($args['defaults']) ? $args['defaults'] : dgut_about_defaults();

if (!dgut_about_bool($fields, 'about_work_show', (bool) $defaults['about_work_show'])) {
    return;
}

$eyebrow = trim((string) dgut_about_field($fields, 'about_work_eyebrow', $defaults['about_work_eyebrow']));
$title = trim((string) dgut_about_field($fields, 'about_work_title', $defaults['about_work_title']));
$cards = dgut_about_rows($fields, 'about_work_cards', $defaults['about_work_cards']);

if ($eyebrow === '' && $title === '' && empty($cards)) {
    return;
}
?>
<section class="dgut-about-work">
    <div class="container">
        <?php if ($eyebrow !== '' || $title !== '') : ?>
            <div class="dgut-about-section-head">
                <?php if ($eyebrow !== '') : ?>
                    <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>
                <?php if ($title !== '') : ?>
                    <h2 class="section-title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($cards)) : ?>
            <div class="dgut-about-work__grid">
                <?php foreach ($cards as $card) : ?>
                    <?php
                    $card_title = trim((string) ($card['title'] ?? ''));
                    $card_text = trim((string) ($card['text'] ?? ''));
                    $icon = trim((string) ($card['icon'] ?? ''));
                    if ($card_title === '' && $card_text === '' && $icon === '') {
                        continue;
                    }
                    ?>
                    <article class="dgut-about-work-card">
                        <?php if ($icon !== '') : ?>
                            <span class="dgut-about-work-card__icon"><?php echo dgut_ui_icon($icon); ?></span>
                        <?php endif; ?>
                        <?php if ($card_title !== '') : ?>
                            <h3><?php echo esc_html($card_title); ?></h3>
                        <?php endif; ?>
                        <?php if ($card_text !== '') : ?>
                            <p><?php echo esc_html($card_text); ?></p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
