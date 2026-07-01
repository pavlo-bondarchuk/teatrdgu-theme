<?php
get_header();
the_post();

$date = dgut_performance_text_field('dgut_performance_date');
$duration = dgut_performance_text_field('dgut_performance_duration');
$video_url = dgut_performance_text_field('dgut_performance_video_url');
$about_eyebrow = dgut_performance_text_field('dgut_performance_about_eyebrow');
$about_title = dgut_performance_text_field('dgut_performance_about_title');
$about_text = dgut_performance_text_field('dgut_performance_about_text');
$gallery_eyebrow = dgut_performance_text_field('dgut_performance_gallery_eyebrow');
$gallery_title = dgut_performance_text_field('dgut_performance_gallery_title');
$video_eyebrow = dgut_performance_text_field('dgut_performance_video_eyebrow');
$video_title = dgut_performance_text_field('dgut_performance_video_title');
$services_eyebrow = dgut_performance_text_field('dgut_performance_services_eyebrow');
$services_title = dgut_performance_text_field('dgut_performance_services_title');
$services_text = dgut_performance_text_field('dgut_performance_services_text');
$gallery = dgut_performance_gallery();
$cast = dgut_performance_people('dgut_performance_cast');
$backstage = dgut_performance_people('dgut_performance_backstage');
$services = dgut_performance_services();
$ticket_services = dgut_performance_ticket_services($services, '');
$terms = wp_get_post_terms(get_the_ID(), 'performance_genre', ['fields' => 'names']);
$has_ticket_links = !empty($ticket_services);
$video_embed = dgut_performance_video_embed($video_url);
$breadcrumbs = dgut_yoast_breadcrumbs();
?>
<main id="primary" class="site-main dgut-event">
    <?php if ($breadcrumbs !== '') : ?>
        <div class="container dgut-event-breadcrumbs">
            <?php echo $breadcrumbs; ?>
        </div>
    <?php endif; ?>

    <section class="section dgut-event-hero">
        <div class="container dgut-event-hero__grid">
            <?php if (has_post_thumbnail()) : ?>
                <div class="media-frame dgut-event-hero__image">
                    <?php the_post_thumbnail('dgut-card', ['loading' => 'eager', 'fetchpriority' => 'high']); ?>
                </div>
            <?php endif; ?>
            <div class="dgut-event-hero__content">
                <?php if (!empty($terms)) : ?>
                    <p class="eyebrow dgut-event-eyebrow dgut-event-hero__eyebrow"><?php echo esc_html($terms[0]); ?></p>
                <?php endif; ?>
                <h1 class="display dgut-event-hero__title"><?php the_title(); ?></h1>
                <?php if (has_excerpt()) : ?>
                    <p class="dgut-event-hero__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
                <?php if ($date !== '' || $duration !== '') : ?>
                    <div class="dgut-event-meta">
                        <?php if ($date !== '') : ?>
                            <div class="dgut-event-meta__item">
                                <div class="dgut-event-meta__icon">
                                    <?php echo dgut_ui_icon('calendar'); ?>
                                </div>
                                <div class="dgut-event-meta__text">
                                    <span>
                                        <?php esc_html_e('Дата', 'dgutheater'); ?>
                                    </span>
                                    <strong>
                                        <?php echo esc_html($date); ?>
                                    </strong>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if ($duration !== '') : ?>
                            <div class="dgut-event-meta__item">
                                <div class="dgut-event-meta__icon">
                                    <?php echo dgut_ui_icon('clock'); ?>
                                </div>
                                <div class="dgut-event-meta__text">
                                    <span>
                                        <?php esc_html_e('Тривалість', 'dgutheater'); ?>
                                    </span>
                                    <strong>
                                        <?php echo esc_html($duration); ?>
                                    </strong>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ($has_ticket_links) : ?>
                    <a class="btn dgut-event-hero__button" href="#tickets">
                        <?php echo dgut_ui_icon('ticket'); ?>
                        <?php esc_html_e('Купити квиток', 'dgutheater'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php if ($about_title !== '' || $about_text !== '') : ?>
        <section class="section dgut-event-about">
            <div class="container dgut-event-about__grid">
                <div>
                    <?php if ($about_eyebrow !== '') : ?>
                        <p class="eyebrow dgut-event-section-eyebrow"><?php echo esc_html($about_eyebrow); ?></p>
                    <?php endif; ?>
                    <?php if ($about_title !== '') : ?>
                        <h2 class="section-title dgut-event-section-title"><?php echo esc_html($about_title); ?></h2>
                    <?php endif; ?>
                </div>
                <?php if ($about_text !== '') : ?>
                    <div class="dgut-event-about__content flow">
                        <?php echo wp_kses_post(apply_filters('the_content', $about_text)); ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if (!empty($cast) || !empty($backstage)) : ?>
        <section class="section dgut-event-people">
            <div class="container dgut-event-people__grid">
                <?php if (!empty($cast)) : ?>
                    <div class="dgut-event-people__group">
                        <p class="eyebrow dgut-event-section-eyebrow"><?php esc_html_e('На сцені', 'dgutheater'); ?></p>
                        <h2 class="section-title dgut-event-section-title"><?php esc_html_e('Акторський склад', 'dgutheater'); ?></h2>
                        <div class="dgut-event-people__list">
                            <?php foreach ($cast as $person) : ?>
                                <div class="dgut-event-person ">
                                    <div class="dgut-event-person__icon">
                                        <?php echo dgut_ui_icon('user'); ?>
                                    </div>
                                    <div class="dgut-event-person__column">
                                        <?php if ($person['role'] !== '') : ?>
                                            <span><?php echo esc_html($person['role']); ?></span>
                                        <?php endif; ?>
                                        <?php if ($person['name'] !== '') : ?>
                                            <strong><?php echo esc_html($person['name']); ?></strong>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($backstage)) : ?>
                    <div class="dgut-event-people__group">
                        <p class="eyebrow dgut-event-section-eyebrow"><?php esc_html_e('За лаштунками', 'dgutheater'); ?></p>
                        <h2 class="section-title dgut-event-section-title"><?php esc_html_e('Постановча команда', 'dgutheater'); ?></h2>
                        <div class="dgut-event-people__list">
                            <?php foreach ($backstage as $person) : ?>
                                <div class="dgut-event-person">
                                    <?php if ($person['role'] !== '') : ?>
                                        <span><?php echo esc_html($person['role']); ?></span>
                                    <?php endif; ?>
                                    <?php if ($person['name'] !== '') : ?>
                                        <strong><?php echo esc_html($person['name']); ?></strong>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($gallery) : ?>
        <section class="section dgut-event-gallery" data-carousel data-carousel-desktop="3" data-carousel-tablet="2" data-carousel-mobile="1">
            <div class="container">
                <div class="dgut-event-section-head">
                    <div>
                        <?php if ($gallery_eyebrow !== '') : ?>
                            <p class="eyebrow dgut-event-section-eyebrow"><?php echo esc_html($gallery_eyebrow); ?></p>
                        <?php endif; ?>
                        <?php if ($gallery_title !== '') : ?>
                            <h2 class="section-title dgut-event-section-title"><?php echo esc_html($gallery_title); ?></h2>
                        <?php endif; ?>
                    </div>
                    <div class="slider-controls">
                        <button class="slider-arrow" type="button" data-carousel-prev aria-label="<?php esc_attr_e('Попередні фото', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-left'); ?></button>
                        <button class="slider-arrow" type="button" data-carousel-next aria-label="<?php esc_attr_e('Наступні фото', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-right'); ?></button>
                    </div>
                </div>
                <div class="dgut-event-gallery__track" data-carousel-track>
                    <?php foreach ($gallery as $image) : ?>
                        <div class="media-frame dgut-event-gallery__item">
                            <?php
                            if (!empty($image['id'])) {
                                echo wp_get_attachment_image((int) $image['id'], 'dgut-wide', false, ['loading' => 'lazy']);
                            } else {
                                echo dgut_img(esc_url($image['url']), $image['alt']);
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($video_embed !== '') : ?>
        <section class="section dgut-event-video">
            <div class="container">
                <?php if ($video_eyebrow !== '') : ?>
                    <p class="eyebrow dgut-event-section-eyebrow"><?php echo esc_html($video_eyebrow); ?></p>
                <?php endif; ?>
                <?php if ($video_title !== '') : ?>
                    <h2 class="section-title dgut-event-section-title"><?php echo esc_html($video_title); ?></h2>
                <?php endif; ?>
                <div class="dgut-video-frame">
                    <?php echo $video_embed; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($has_ticket_links) : ?>
        <section id="tickets" class="section dgut-ticket-services">
            <div class="container">
                <div class="dgut-ticket-services__intro">
                    <?php if ($services_eyebrow !== '') : ?>
                        <p class="eyebrow dgut-event-section-eyebrow"><?php echo esc_html($services_eyebrow); ?></p>
                    <?php endif; ?>
                    <?php if ($services_title !== '') : ?>
                        <h2 class="section-title dgut-event-section-title"><?php echo esc_html($services_title); ?></h2>
                    <?php endif; ?>
                    <?php if ($services_text !== '') : ?>
                        <p><?php echo esc_html($services_text); ?></p>
                    <?php endif; ?>
                </div>
                <div class="dgut-ticket-services__grid">
                    <?php foreach ($ticket_services as $service) : ?>
                        <a class="dgut-ticket-service-card" href="<?php echo esc_url($service['url']); ?>" target="_blank" rel="noopener noreferrer">
                            <?php if ($service['icon'] !== '') : ?>
                                <img src="<?php echo esc_url($service['icon']); ?>" alt="<?php echo esc_attr($service['name']); ?>">
                            <?php endif; ?>
                            <?php if ($service['name'] !== '') : ?>
                                <h3><?php echo esc_html($service['name']); ?></h3>
                            <?php endif; ?>
                            <?php if ($service['description'] !== '') : ?>
                                <p><?php echo esc_html($service['description']); ?></p>
                            <?php endif; ?>
                            <span class="dgut-ticket-service-card__link">
                                <?php echo dgut_ui_icon('external-link'); ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($has_ticket_links) : ?>
        <aside class="dgut-event-floating-ticket" data-floating-ticket aria-label="<?php esc_attr_e('Швидкий перехід до квитків', 'dgutheater'); ?>">
            <button class="dgut-event-floating-ticket__close" type="button" data-floating-ticket-close aria-label="<?php esc_attr_e('Закрити', 'dgutheater'); ?>">×</button>
            <p class="eyebrow dgut-event-floating-ticket__eyebrow"><?php esc_html_e('Квитки', 'dgutheater'); ?></p>
            <p class="dgut-event-floating-ticket__title"><?php esc_html_e('Готові побачити виставу?', 'dgutheater'); ?></p>
            <a class="btn dgut-event-floating-ticket__button" href="#tickets">
                <?php echo dgut_ui_icon('ticket'); ?>
                <?php esc_html_e('Перейти до квитків', 'dgutheater'); ?>
                <?php echo dgut_ui_icon('chevron-right'); ?>
            </a>
        </aside>
        <script>
            (() => {
                const floatingTicket = document.querySelector('[data-floating-ticket]');
                const closeButton = document.querySelector('[data-floating-ticket-close]');

                closeButton?.addEventListener('click', () => {
                    floatingTicket?.setAttribute('hidden', '');
                });
            })();
        </script>
    <?php endif; ?>
</main>
<?php
get_footer();
