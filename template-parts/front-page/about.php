<?php
$fields = isset($args['fields']) && is_array($args['fields']) ? $args['fields'] : [];
$about_eyebrow = isset($args['about_eyebrow']) ? (string) $args['about_eyebrow'] : '';
$about_title = isset($args['about_title']) ? (string) $args['about_title'] : '';
$about_text_1 = isset($args['about_text_1']) ? (string) $args['about_text_1'] : '';
$about_text_2 = isset($args['about_text_2']) ? (string) $args['about_text_2'] : '';
$about_stats = isset($args['about_stats']) && is_array($args['about_stats']) ? $args['about_stats'] : [];
$about_images = isset($args['about_images']) && is_array($args['about_images']) ? $args['about_images'] : [];
$has_about = !empty($args['has_about']);

if (!dgut_front_bool($fields, 'home_about_show', true) || !$has_about) {
    return;
}

$archive_link = dgut_front_field($fields, 'home_about_archive_link', []);
$archive_link_url = is_array($archive_link) ? (string) ($archive_link['url'] ?? '') : '';
$archive_link_title = is_array($archive_link) ? (string) ($archive_link['title'] ?? '') : '';
$archive_link_target = is_array($archive_link) ? (string) ($archive_link['target'] ?? '') : '';
?>
<section id="about" class="section dgut-about">
    <div class="container dgut-about__grid">
        <div class="dgut-about__content">
            <?php if ($about_eyebrow !== '') : ?>
                <p class="eyebrow dgut-about__eyebrow"><?php echo esc_html($about_eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($about_title !== '') : ?>
                <h2 class="section-title dgut-about__title">
                    <?php echo wp_kses_post($about_title); ?>
                </h2>
            <?php endif; ?>
            <?php if ($about_text_1 !== '' || $about_text_2 !== '') : ?>
                <div class="dgut-about__copy">
                    <?php if ($about_text_1 !== '') : ?>
                        <p><?php echo esc_html($about_text_1); ?></p>
                    <?php endif; ?>
                    <?php if ($about_text_2 !== '') : ?>
                        <p><?php echo esc_html($about_text_2); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($about_stats)) : ?>
                <div class="dgut-about__stats">
                    <?php foreach ($about_stats as $stat) : ?>
                        <div>
                            <?php if ($stat['value'] !== '') : ?>
                                <strong><?php echo esc_html($stat['value']); ?></strong>
                            <?php endif; ?>
                            <?php if ($stat['label'] !== '') : ?>
                                <span><?php echo esc_html($stat['label']); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if ($archive_link_url !== '') : ?>
                <a class="btn dgut-about__button" href="<?php echo esc_url($archive_link_url); ?>" <?php echo $archive_link_target !== '' ? ' target="' . esc_attr($archive_link_target) . '"' : ''; ?><?php echo $archive_link_target === '_blank' ? ' rel="noopener noreferrer"' : ''; ?>>
                    <?php echo esc_html($archive_link_title ?: __('Про театр', 'dgutheater')); ?> <span aria-hidden="true">→</span>
                </a>
            <?php endif; ?>
        </div>
        <?php if (!empty($about_images)) : ?>
            <div class="dgut-about__media">
                <?php foreach ($about_images as $index => $about_image) : ?>
                    <div class="dgut-about__image<?php echo $index === 0 ? ' dgut-about__image--wide' : ''; ?>">
                        <?php echo dgut_responsive_image_from_url(
                            (string) $about_image,
                            wp_strip_all_tags($about_title),
                            'medium_large',
                            '(max-width: 640px) calc(100vw - 40px), (max-width: 1024px) calc((100vw - 72px) / 2), 364px'
                        ); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
