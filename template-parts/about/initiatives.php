<?php
$fields = isset($args['fields']) && is_array($args['fields']) ? $args['fields'] : [];
$defaults = isset($args['defaults']) && is_array($args['defaults']) ? $args['defaults'] : dgut_about_defaults();

if (!dgut_about_bool($fields, 'about_initiatives_show', (bool) $defaults['about_initiatives_show'])) {
    return;
}

$eyebrow = trim((string) dgut_about_field($fields, 'about_initiatives_eyebrow', $defaults['about_initiatives_eyebrow']));
$title = trim((string) dgut_about_field($fields, 'about_initiatives_title', $defaults['about_initiatives_title']));
$source = trim((string) dgut_about_field($fields, 'about_initiatives_source', 'manual'));
$category = dgut_about_field($fields, 'about_initiatives_category', 0);
$initiatives = dgut_about_rows($fields, 'about_initiatives', $defaults['about_initiatives']);

if ($source === 'category_posts') {
    $post_initiatives = dgut_about_initiative_posts($category);
    if (!empty($post_initiatives)) {
        $initiatives = $post_initiatives;
    }
}

if ($eyebrow === '' && $title === '' && empty($initiatives)) {
    return;
}
?>
<section class="dgut-about-initiatives">
    <div class="container dgut-about-initiatives__grid">
        <?php if ($eyebrow !== '' || $title !== '') : ?>
            <div class="dgut-about-initiatives__intro">
                <?php if ($eyebrow !== '') : ?>
                    <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
                <?php endif; ?>
                <?php if ($title !== '') : ?>
                    <h2 class="section-title"><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($initiatives)) : ?>
            <div class="dgut-about-initiatives__list">
                <?php foreach ($initiatives as $index => $initiative) : ?>
                    <?php $text = trim((string) ($initiative['text'] ?? '')); ?>
                    <?php if ($text === '') : ?>
                        <?php continue; ?>
                    <?php endif; ?>
                    <?php
                    $link = $initiative['link'] ?? ($initiative['url'] ?? '');
                    $link_url = is_array($link) ? trim((string) ($link['url'] ?? '')) : trim((string) $link);
                    $link_target = is_array($link) ? trim((string) ($link['target'] ?? '')) : '';
                    ?>
                    <article class="dgut-about-initiative">
                        <span><?php echo esc_html(sprintf('%02d', $index + 1)); ?></span>
                        <p>
                            <?php if ($link_url !== '') : ?>
                                <a href="<?php echo esc_url($link_url); ?>" <?php echo $link_target !== '' ? 'target="' . esc_attr($link_target) . '" rel="noopener noreferrer"' : ''; ?>>
                                    <?php echo esc_html($text); ?>
                                </a>
                            <?php else : ?>
                                <?php echo esc_html($text); ?>
                            <?php endif; ?>
                        </p>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
