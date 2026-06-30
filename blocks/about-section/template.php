<?php
$fields = function_exists('get_fields') ? (array) get_fields() : [];
$show_block = $fields['show_block'] ?? true;
if ($show_block !== true) {
    return;
}

$about_url = get_permalink(get_page_by_path('about')) ?: home_url('/about/');
$images = [
    [
        'src' => 'https://images.unsplash.com/photo-1762983809201-b42ca93fe1ea?w=800&h=450&fit=crop&auto=format',
        'alt' => __('Dnipro city and cultural life', 'dgutheater'),
    ],
    [
        'src' => 'https://images.unsplash.com/photo-1582192903020-8a5e59dcdcf2?w=400&h=300&fit=crop&auto=format',
        'alt' => __('Theatre audience', 'dgutheater'),
    ],
    [
        'src' => 'https://images.unsplash.com/photo-1515168833906-d2a3b82b302a?w=400&h=300&fit=crop&auto=format',
        'alt' => __('Theatre event', 'dgutheater'),
    ],
];
?>
<section id="about" class="section dgut-about">
    <div class="container dgut-about__grid">
        <div class="dgut-about__content">
            <p class="eyebrow dgut-about__eyebrow"><?php esc_html_e('Про театр', 'dgutheater'); ?></p>
            <h2 class="section-title dgut-about__title"><?php esc_html_e('Театр ДГУ - Дніпро Гордість України', 'dgutheater'); ?></h2>
            <div class="dgut-about__copy">
                <p><?php esc_html_e('Театр ДГУ - більше, ніж театральний майданчик: це культурний бренд міста, який об’єднує навколо себе проактивну спільноту.', 'dgutheater'); ?></p>
                <p><?php esc_html_e('Театр ДГУ зберігає свої традиції та водночас розвивається разом із містом і його мешканцями, адже Дніпро є центром нашої системи координат.', 'dgutheater'); ?></p>
            </div>
            <div class="dgut-about__stats" aria-label="<?php esc_attr_e('Theatre facts', 'dgutheater'); ?>">
                <div>
                    <strong><?php esc_html_e('ДГУ', 'dgutheater'); ?></strong>
                    <span><?php esc_html_e('Дніпро Гордість України', 'dgutheater'); ?></span>
                </div>
                <div>
                    <strong><?php esc_html_e('5А', 'dgutheater'); ?></strong>
                    <span><?php esc_html_e('Троїцька площа', 'dgutheater'); ?></span>
                </div>
                <div>
                    <strong><?php esc_html_e('Дніпро', 'dgutheater'); ?></strong>
                    <span><?php esc_html_e('центр координат', 'dgutheater'); ?></span>
                </div>
            </div>
            <a class="btn dgut-about__button" href="<?php echo esc_url($about_url); ?>">
                <?php esc_html_e('Про театр', 'dgutheater'); ?>
                <span aria-hidden="true">→</span>
            </a>
        </div>

        <div class="dgut-about__media" aria-label="<?php esc_attr_e('Theatre images', 'dgutheater'); ?>">
            <div class="dgut-about__image dgut-about__image--wide">
                <?php echo dgut_img($images[0]['src'], $images[0]['alt']); ?>
            </div>
            <div class="dgut-about__image">
                <?php echo dgut_img($images[1]['src'], $images[1]['alt']); ?>
            </div>
            <div class="dgut-about__image">
                <?php echo dgut_img($images[2]['src'], $images[2]['alt']); ?>
            </div>
        </div>
    </div>
</section>
