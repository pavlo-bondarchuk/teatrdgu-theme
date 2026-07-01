<?php
get_header();
the_post();

function dgut_performance_field(string $key, mixed $default = ''): mixed
{
    if (!function_exists('get_field')) {
        return $default;
    }

    $value = get_field($key);
    return $value !== null && $value !== false && $value !== '' ? $value : $default;
}

function dgut_performance_text_field(string $key, string $default = ''): string
{
    $value = dgut_performance_field($key, $default);
    return is_string($value) ? trim($value) : $default;
}

function dgut_performance_people(string $key): array
{
    $rows = dgut_performance_field($key, []);
    if (!is_array($rows)) {
        return [];
    }

    $people = [];
    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $role = trim((string) ($row['role'] ?? ''));
        $name = trim((string) ($row['name'] ?? ''));

        if ($role === '' && $name === '') {
            continue;
        }

        $people[] = [
            'role' => $role,
            'name' => $name,
        ];
    }

    return $people;
}

function dgut_performance_services(): array
{
    $rows = dgut_performance_field('dgut_performance_ticket_services', []);
    if (!is_array($rows)) {
        return [];
    }

    $services = [];
    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $url = trim((string) ($row['url'] ?? ''));
        if ($url === '') {
            continue;
        }

        $name = trim((string) ($row['name'] ?? ''));
        $description = trim((string) ($row['description'] ?? ''));
        $icon = dgut_get_image_from_field($row['icon'] ?? '');

        if ($name === '' && $description === '' && $icon === '') {
            continue;
        }

        $services[] = [
            'name' => $name,
            'url' => $url,
            'icon' => $icon,
            'description' => $description,
        ];
    }

    return $services;
}

function dgut_performance_gallery(): array
{
    $gallery_raw = dgut_performance_field('dgut_performance_gallery_images', []);
    $gallery = [];

    if (!is_array($gallery_raw)) {
        return $gallery;
    }

    foreach ($gallery_raw as $image) {
        if (is_array($image)) {
            $image_id = (int) ($image['ID'] ?? $image['id'] ?? 0);
            $image_url = (string) ($image['url'] ?? '');

            if ($image_id || $image_url !== '') {
                $gallery[] = [
                    'id' => $image_id,
                    'url' => $image_url,
                    'alt' => (string) ($image['alt'] ?? get_the_title()),
                ];
            }
            continue;
        }

        if (is_numeric($image)) {
            $gallery[] = [
                'id' => (int) $image,
                'url' => '',
                'alt' => get_the_title(),
            ];
            continue;
        }

        if (is_string($image) && trim($image) !== '') {
            $gallery[] = [
                'id' => 0,
                'url' => trim($image),
                'alt' => get_the_title(),
            ];
        }
    }

    return $gallery;
}

function dgut_performance_video_embed(string $video_url): string
{
    if ($video_url === '') {
        return '';
    }

    if (str_contains($video_url, '<iframe')) {
        return wp_kses($video_url, [
            'iframe' => [
                'src' => true,
                'title' => true,
                'width' => true,
                'height' => true,
                'loading' => true,
                'allow' => true,
                'allowfullscreen' => true,
                'frameborder' => true,
            ],
        ]);
    }

    if (str_ends_with(strtolower(parse_url($video_url, PHP_URL_PATH) ?: ''), '.mp4')) {
        return sprintf('<video controls preload="metadata"><source src="%s" type="video/mp4"></video>', esc_url($video_url));
    }

    $embed = wp_oembed_get($video_url);
    if ($embed) {
        return $embed;
    }

    return sprintf(
        '<iframe src="%s" title="%s" loading="lazy" allowfullscreen allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>',
        esc_url($video_url),
        esc_attr(get_the_title())
    );
}

$date = dgut_performance_text_field('dgut_performance_date');
$duration = dgut_performance_text_field('dgut_performance_duration');
$ticket_url = dgut_performance_text_field('dgut_performance_ticket_url');
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
$ticket_services = $services;
if (empty($ticket_services) && $ticket_url !== '') {
    $ticket_services[] = [
        'name' => __('Купити квиток', 'dgutheater'),
        'url' => $ticket_url,
        'icon' => '',
        'description' => '',
    ];
}
$terms = wp_get_post_terms(get_the_ID(), 'performance_genre', ['fields' => 'names']);
$has_ticket_links = !empty($ticket_services);
$video_embed = dgut_performance_video_embed($video_url);
?>
<main id="primary" class="site-main dgut-event">
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
                                <span class="dgut-event-meta__icon"><?php echo dgut_ui_icon('calendar'); ?></span>
                                <span><?php esc_html_e('Дата', 'dgutheater'); ?></span>
                                <strong><?php echo esc_html($date); ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if ($duration !== '') : ?>
                            <div class="dgut-event-meta__item">
                                <span class="dgut-event-meta__icon"><?php echo dgut_ui_icon('clock'); ?></span>
                                <span><?php esc_html_e('Тривалість', 'dgutheater'); ?></span>
                                <strong><?php echo esc_html($duration); ?></strong>
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
                            <span><?php esc_html_e('Перейти до квитків', 'dgutheater'); ?> <?php echo dgut_ui_icon('external-link'); ?></span>
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
