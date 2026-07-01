<?php
$fields = isset($args['fields']) && is_array($args['fields']) ? $args['fields'] : [];
$defaults = isset($args['defaults']) && is_array($args['defaults']) ? $args['defaults'] : dgut_about_defaults();

$eyebrow = trim((string) dgut_about_field($fields, 'about_hero_eyebrow', $defaults['about_hero_eyebrow']));
$title = trim((string) dgut_about_field($fields, 'about_hero_title', $defaults['about_hero_title']));
$subtitle = trim((string) dgut_about_field($fields, 'about_hero_subtitle', $defaults['about_hero_subtitle']));
$tagline = trim((string) dgut_about_field($fields, 'about_hero_tagline', $defaults['about_hero_tagline']));
$history_cards = dgut_about_rows($fields, 'about_history_cards', $defaults['about_history_cards']);

if ($eyebrow === '' && $title === '' && $subtitle === '' && $tagline === '' && empty($history_cards)) {
    return;
}
?>
<section class="dgut-about-hero">
    <div class="container dgut-about-hero__grid">
        <div class="dgut-about-hero__intro">
            <?php if ($eyebrow !== '') : ?>
                <p class="eyebrow dgut-about-hero__eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($title !== '') : ?>
                <h1 class="display dgut-about-hero__title"><?php echo esc_html($title); ?></h1>
            <?php endif; ?>
            <?php if ($subtitle !== '') : ?>
                <p class="dgut-about-hero__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
            <?php if ($tagline !== '') : ?>
                <p class="dgut-about-hero__tagline"><?php echo esc_html($tagline); ?></p>
            <?php endif; ?>
        </div>
        <?php if (!empty($history_cards)) : ?>
            <div class="dgut-about-history">
                <?php foreach ($history_cards as $index => $card) : ?>
                    <?php $text = trim((string) ($card['text'] ?? '')); ?>
                    <?php if ($text === '') : ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <article class="dgut-about-history__card<?php echo $index % 2 === 0 ? ' is-cream' : ''; ?>">
                        <p><?php echo esc_html($text); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
