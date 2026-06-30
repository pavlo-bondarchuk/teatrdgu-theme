<?php
get_header();

function dgut_front_field(string $key, mixed $default = ''): mixed
{
    if (function_exists('get_field')) {
        $value = get_field($key);
        if ($value !== null && $value !== '') {
            return $value;
        }
    }

    return $default;
}

function dgut_front_bool(string $key, bool $default = true): bool
{
    if (function_exists('get_field')) {
        $value = get_field($key);
        if ($value !== null && $value !== '') {
            return (bool) $value;
        }
    }

    return $default;
}

function dgut_front_image(mixed $field, string $fallback = ''): string
{
    return dgut_get_image_from_field($field, $fallback);
}

function dgut_front_posts(string $post_type, int $limit): array
{
    return get_posts([
        'post_type' => $post_type,
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'orderby' => 'menu_order date',
        'order' => 'ASC',
    ]);
}

function dgut_front_page_url(string $slug, string $fallback): string
{
    $page = get_page_by_path($slug);
    return $page ? get_permalink($page) : home_url($fallback);
}

$performance_posts = dgut_front_posts('performance', -1);
$performances = [];
foreach ($performance_posts as $performance_post) {
    $performances[] = dgut_get_performance_card_data($performance_post);
}

$hero_slides = array_slice($performances, 0, max(1, (int) dgut_front_field('home_hero_count', 3)));

$news_posts = dgut_front_posts('post', max(1, (int) dgut_front_field('home_news_count', 4)));
$news_items = [];
foreach ($news_posts as $news_post) {
    $news_items[] = [
        'title' => get_the_title($news_post),
        'category' => get_the_category($news_post->ID)[0]->name ?? '',
        'date' => get_the_date('d.m.Y', $news_post),
        'excerpt' => get_the_excerpt($news_post),
        'image' => get_the_post_thumbnail_url($news_post, 'dgut-wide') ?: '',
        'url' => get_permalink($news_post),
    ];
}

$about_eyebrow = (string) dgut_front_field('home_about_eyebrow');
$about_title = (string) dgut_front_field('home_about_title');
$about_text_1 = (string) dgut_front_field('home_about_text_1');
$about_text_2 = (string) dgut_front_field('home_about_text_2');
$about_stats = [
    [
        'value' => (string) dgut_front_field('home_about_stat_1_value'),
        'label' => (string) dgut_front_field('home_about_stat_1_label'),
    ],
    [
        'value' => (string) dgut_front_field('home_about_stat_2_value'),
        'label' => (string) dgut_front_field('home_about_stat_2_label'),
    ],
    [
        'value' => (string) dgut_front_field('home_about_stat_3_value'),
        'label' => (string) dgut_front_field('home_about_stat_3_label'),
    ],
];
$about_stats = array_values(array_filter($about_stats, fn (array $stat): bool => $stat['value'] !== '' || $stat['label'] !== ''));
$about_images = array_values(array_filter([
    dgut_front_image(dgut_front_field('home_about_image_1')),
    dgut_front_image(dgut_front_field('home_about_image_2')),
    dgut_front_image(dgut_front_field('home_about_image_3')),
]));
$has_about = $about_eyebrow !== '' || $about_title !== '' || $about_text_1 !== '' || $about_text_2 !== '' || !empty($about_stats) || !empty($about_images);

$team_posts = dgut_front_posts('team_member', max(1, (int) dgut_front_field('home_team_count', 14)));
$team = [];
foreach ($team_posts as $team_post) {
    $team[] = dgut_get_team_member_card_data($team_post);
}
?>
<main id="primary" class="site-main">
    <?php if (dgut_front_bool('home_hero_show', true) && !empty($hero_slides)) : ?>
        <section id="top" class="dgut-hero" data-hero-slider>
            <div class="dgut-hero__slides">
                <?php foreach ($hero_slides as $index => $slide) : ?>
                    <article class="dgut-hero__slide<?php echo $index === 0 ? ' is-active' : ''; ?>" data-hero-slide>
                        <?php if (!empty($slide['image'])) : ?>
                            <?php echo dgut_img($slide['image'], $slide['title'], 'dgut-hero__image', [
                                'loading' => $index === 0 ? 'eager' : 'lazy',
                                'fetchpriority' => $index === 0 ? 'high' : 'auto',
                                'style' => 'object-position:' . ($slide['focus'] ?? 'center center'),
                            ]); ?>
                        <?php endif; ?>
                        <div class="dgut-hero__shade" aria-hidden="true"></div>
                        <div class="dgut-hero__fade" aria-hidden="true"></div>
                        <div class="container dgut-hero__content">
                            <p class="eyebrow dgut-hero__eyebrow"><?php echo esc_html($slide['genre'] ?? ''); ?></p>
                            <h1 class="display dgut-hero__title"><?php echo esc_html($slide['title']); ?></h1>
                            <p class="dgut-hero__desc"><?php echo esc_html($slide['excerpt'] ?? ''); ?></p>
                            <p class="dgut-hero__date"><?php echo dgut_ui_icon('clock'); ?><?php echo esc_html($slide['date'] ?? ''); ?></p>
                            <a class="btn dgut-hero__button" href="<?php echo esc_url($slide['permalink'] ?? '#repertoire'); ?>"><?php esc_html_e('Детальніше', 'dgutheater'); ?></a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="container dgut-hero__controls" aria-label="<?php esc_attr_e('Hero slides', 'dgutheater'); ?>">
                <button class="dgut-hero__arrow" type="button" data-hero-prev aria-label="<?php esc_attr_e('Попередній слайд', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-left'); ?></button>
                <div class="dgut-hero__dots">
                    <?php foreach ($hero_slides as $index => $slide) : ?>
                        <button type="button" data-hero-dot="<?php echo esc_attr((string) $index); ?>" class="<?php echo $index === 0 ? 'is-active' : ''; ?>">
                            <span class="screen-reader-text"><?php echo esc_html($slide['title']); ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
                <button class="dgut-hero__arrow" type="button" data-hero-next aria-label="<?php esc_attr_e('Наступний слайд', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-right'); ?></button>
            </div>
        </section>
    <?php endif; ?>

    <?php if (dgut_front_bool('home_repertoire_show', true) && !empty($performances)) : ?>
        <section id="repertoire" class="section dgut-repertoire" data-carousel data-carousel-step="page">
            <div class="container">
                <div class="dgut-section-head">
                    <h2 class="section-title"><?php echo esc_html(dgut_front_field('home_repertoire_title', __('Репертуар', 'dgutheater'))); ?></h2>
                    <div class="dgut-section-actions">
                        <a class="dgut-section-link" href="<?php echo esc_url(dgut_front_page_url('repertoire', '/repertoire/')); ?>"><?php esc_html_e('Весь репертуар', 'dgutheater'); ?> <span aria-hidden="true">→</span></a>
                        <div class="slider-controls">
                            <button class="slider-arrow" type="button" data-carousel-prev aria-label="<?php esc_attr_e('Попередня група репертуару', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-left'); ?></button>
                            <button class="slider-arrow" type="button" data-carousel-next aria-label="<?php esc_attr_e('Наступна група репертуару', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-right'); ?></button>
                        </div>
                    </div>
                </div>
                <div class="dgut-repertoire__viewport">
                    <div class="dgut-repertoire__grid" data-carousel-track>
                        <?php foreach ($performances as $item) : ?>
                            <article class="card dgut-performance-card">
                                <a href="<?php echo esc_url($item['permalink'] ?? '#tickets'); ?>">
                                    <div class="media-frame dgut-performance-card__image">
                                        <?php if (!empty($item['image'])) : ?>
                                            <?php echo dgut_img($item['image'], $item['title'], '', ['style' => 'object-position:' . ($item['focus'] ?? 'center')]); ?>
                                        <?php endif; ?>
                                        <span class="dgut-performance-card__badge"><?php echo esc_html($item['genre'] ?? ''); ?></span>
                                    </div>
                                    <div class="dgut-performance-card__body">
                                        <h3><?php echo esc_html($item['title']); ?></h3>
                                        <p><?php echo esc_html($item['excerpt']); ?></p>
                                        <span class="dgut-performance-card__date"><?php echo dgut_ui_icon('clock'); ?><?php echo esc_html($item['date']); ?></span>
                                        <span class="btn dgut-performance-card__button"><?php esc_html_e('Детальніше', 'dgutheater'); ?></span>
                                    </div>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if (dgut_front_bool('home_news_show', true) && !empty($news_items)) : ?>
        <section id="news" class="section dgut-news" data-carousel data-carousel-step="page">
            <div class="container">
                <div class="dgut-section-head">
                    <h2 class="section-title"><?php echo esc_html(dgut_front_field('home_news_title', __('Новини | Культурна платформа', 'dgutheater'))); ?></h2>
                    <div class="dgut-section-actions">
                        <a class="dgut-section-link" href="<?php echo esc_url(dgut_front_page_url('news', '/news/')); ?>"><?php esc_html_e('Усі новини', 'dgutheater'); ?> <span aria-hidden="true">→</span></a>
                        <div class="slider-controls">
                            <button class="slider-arrow" type="button" data-carousel-prev aria-label="<?php esc_attr_e('Попередня група новин', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-left'); ?></button>
                            <button class="slider-arrow" type="button" data-carousel-next aria-label="<?php esc_attr_e('Наступна група новин', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-right'); ?></button>
                        </div>
                    </div>
                </div>
                <div class="dgut-news__viewport">
                    <div class="dgut-news__grid" data-carousel-track>
                        <?php foreach ($news_items as $item) : ?>
                            <article class="card dgut-news-card">
                                <a href="<?php echo esc_url($item['url']); ?>">
                                    <div class="media-frame dgut-news-card__image">
                                        <?php if (!empty($item['image'])) : ?>
                                            <?php echo dgut_img($item['image'], $item['title']); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="dgut-news-card__body">
                                        <div class="dgut-news-card__meta">
                                            <span><?php echo esc_html($item['category']); ?></span>
                                            <time><?php echo esc_html($item['date']); ?></time>
                                        </div>
                                        <h3><?php echo esc_html($item['title']); ?></h3>
                                        <p><?php echo esc_html($item['excerpt']); ?></p>
                                        <span class="dgut-news-card__read"><?php esc_html_e('Читати', 'dgutheater'); ?><span aria-hidden="true">›</span></span>
                                    </div>
                                </a>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if (dgut_front_bool('home_about_show', true) && $has_about) : ?>
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
    <?php endif; ?>

    <?php if (dgut_front_bool('home_team_show', true) && !empty($team)) : ?>
        <section class="section dgut-team" data-carousel>
            <div class="container">
                <div class="dgut-team__top">
                    <div>
                        <p class="eyebrow"><?php echo esc_html(dgut_front_field('home_team_eyebrow', __('Команда', 'dgutheater'))); ?></p>
                        <h2 class="section-title"><?php echo esc_html(dgut_front_field('home_team_title', __('Люди театру', 'dgutheater'))); ?></h2>
                    </div>
                    <div class="slider-controls">
                        <button class="slider-arrow" type="button" data-carousel-prev aria-label="<?php esc_attr_e('Попередня група команди', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-left'); ?></button>
                        <button class="slider-arrow" type="button" data-carousel-next aria-label="<?php esc_attr_e('Наступна група команди', 'dgutheater'); ?>"><?php echo dgut_ui_icon('chevron-right'); ?></button>
                    </div>
                </div>
                <div class="dgut-team__track" data-carousel-track>
                    <?php foreach ($team as $person) : ?>
                        <article class="card dgut-team-card">
                            <?php if (!empty($person['image'])) : ?>
                                <div class="media-frame dgut-team-card__image"><?php echo dgut_img($person['image'], $person['name'], '', ['style' => 'object-position:' . ($person['focus'] ?? 'center top')]); ?></div>
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
    <?php endif; ?>
</main>
<?php
get_footer();
