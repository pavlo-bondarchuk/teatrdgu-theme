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
?>
<section id="about" class="section dgut-about">
    <div class="container dgut-about__grid">
        <div class="dgut-about__content">
            <?php if ($about_eyebrow !== '') : ?>
                <p class="eyebrow dgut-about__eyebrow"><?php echo esc_html($about_eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($about_title !== '') : ?>
                <h2 class="section-title dgut-about__title"><?php echo esc_html($about_title); ?></h2>
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
            <a class="btn dgut-about__button" href="<?php echo esc_url(dgut_front_page_url('about', '/about/')); ?>"><?php esc_html_e('Про театр', 'dgutheater'); ?> <span aria-hidden="true">→</span></a>
        </div>
        <?php if (!empty($about_images)) : ?>
            <div class="dgut-about__media">
                <?php foreach ($about_images as $index => $about_image) : ?>
                    <div class="dgut-about__image<?php echo $index === 0 ? ' dgut-about__image--wide' : ''; ?>">
                        <?php echo dgut_img($about_image, $about_title); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
