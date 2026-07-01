<?php
$fields = isset($args['fields']) && is_array($args['fields']) ? $args['fields'] : [];
$news_items = isset($args['news_items']) && is_array($args['news_items']) ? $args['news_items'] : [];

if (!dgut_front_bool($fields, 'home_news_show', true) || empty($news_items)) {
    return;
}
?>
<section id="news" class="section dgut-news" data-carousel data-carousel-desktop="4" data-carousel-tablet="2" data-carousel-mobile="1">
    <div class="container">
        <div class="dgut-section-head">
            <h2 class="section-title"><?php echo esc_html(dgut_front_field($fields, 'home_news_title', __('Новини | Культурна платформа', 'dgutheater'))); ?></h2>
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
