<?php
get_header();
the_post();

$date = function_exists('get_field') ? (string) get_field('dgut_performance_date') : '';
$duration = function_exists('get_field') ? (string) get_field('dgut_performance_duration') : '';
$ticket_url = function_exists('get_field') ? (string) get_field('dgut_performance_ticket_url') : '';
$video_url = function_exists('get_field') ? (string) get_field('dgut_performance_video_url') : '';
$gallery_raw = function_exists('get_field') ? (string) get_field('dgut_performance_gallery') : '';
$gallery = array_filter(array_map('trim', preg_split('/\R+/', $gallery_raw) ?: []));
$terms = wp_get_post_terms(get_the_ID(), 'performance_genre', ['fields' => 'names']);
?>
<main id="primary" class="site-main dgut-event">
    <section class="section dgut-event-hero">
        <div class="container dgut-event-hero__grid">
            <div class="media-frame dgut-event-hero__image">
                <?php
                if (has_post_thumbnail()) {
                    the_post_thumbnail('dgut-card', ['loading' => 'eager', 'fetchpriority' => 'high']);
                }
                ?>
            </div>
            <div class="dgut-event-hero__content">
                <?php if (!empty($terms)) : ?>
                    <p class="eyebrow"><?php echo esc_html($terms[0]); ?></p>
                <?php endif; ?>
                <h1 class="display dgut-event-hero__title"><?php the_title(); ?></h1>
                <?php if (has_excerpt()) : ?>
                    <p class="dgut-event-hero__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
                <div class="dgut-event-meta">
                    <div><span><?php esc_html_e('Дата', 'dgutheater'); ?></span><strong><?php echo esc_html($date ?: __('Дати уточнюються', 'dgutheater')); ?></strong></div>
                    <div><span><?php esc_html_e('Тривалість', 'dgutheater'); ?></span><strong><?php echo esc_html($duration ?: __('Уточнюється', 'dgutheater')); ?></strong></div>
                </div>
                <button class="btn" type="button" data-scroll-target="#tickets"><?php esc_html_e('Купити квиток', 'dgutheater'); ?></button>
            </div>
        </div>
    </section>

    <section class="section dgut-event-body">
        <div class="container dgut-event-body__content">
            <?php the_content(); ?>
        </div>
    </section>

    <?php if ($video_url) : ?>
        <section class="section dgut-event-video">
            <div class="container">
                <p class="eyebrow"><?php esc_html_e('Відео', 'dgutheater'); ?></p>
                <div class="dgut-video-frame">
                    <?php
                    if (str_ends_with($video_url, '.mp4')) {
                        printf('<video controls preload="metadata"><source src="%s" type="video/mp4"></video>', esc_url($video_url));
                    } else {
                        printf('<iframe src="%s" title="%s" loading="lazy" allowfullscreen allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>', esc_url($video_url), esc_attr(get_the_title()));
                    }
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($gallery) : ?>
        <section class="section dgut-event-gallery" data-carousel>
            <div class="container">
                <div class="dgut-team__top">
                    <div>
                        <p class="eyebrow"><?php esc_html_e('Фотогалерея', 'dgutheater'); ?></p>
                        <h2 class="section-title"><?php esc_html_e('Вистава у кадрі', 'dgutheater'); ?></h2>
                    </div>
                    <div class="slider-controls">
                        <button class="slider-arrow" type="button" data-carousel-prev aria-label="<?php esc_attr_e('Попередні фото', 'dgutheater'); ?>">‹</button>
                        <button class="slider-arrow" type="button" data-carousel-next aria-label="<?php esc_attr_e('Наступні фото', 'dgutheater'); ?>">›</button>
                    </div>
                </div>
                <div class="dgut-event-gallery__track" data-carousel-track>
                    <?php foreach ($gallery as $image_ref) : ?>
                        <div class="media-frame dgut-event-gallery__item">
                            <?php
                            if (is_numeric($image_ref)) {
                                echo wp_get_attachment_image((int) $image_ref, 'dgut-wide', false, ['loading' => 'lazy']);
                            } else {
                                echo dgut_img(esc_url($image_ref), get_the_title());
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($ticket_url) : ?>
        <section id="tickets" class="section dgut-tickets">
            <div class="container">
                <div class="dgut-tickets__intro">
                    <p class="eyebrow"><?php esc_html_e('Квитки', 'dgutheater'); ?></p>
                    <h2 class="section-title"><?php esc_html_e('Купити квиток', 'dgutheater'); ?></h2>
                    <p><?php esc_html_e('Продаж і повернення квитків відбуваються на стороні офіційного сервісу.', 'dgutheater'); ?></p>
                </div>
                <a class="btn" href="<?php echo esc_url($ticket_url); ?>" target="_blank" rel="noopener noreferrer">
                    <?php esc_html_e('Перейти до квитків', 'dgutheater'); ?>
                    <span aria-hidden="true">↗</span>
                </a>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php
get_footer();
