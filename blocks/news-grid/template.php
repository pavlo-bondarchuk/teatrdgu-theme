<?php
$fields = function_exists('get_fields') ? (array) get_fields() : [];
$show_block = $fields['show_block'] ?? true;
if ($show_block !== true) {
    return;
}

$eyebrow = $fields['eyebrow'] ?? __('Новини', 'dgutheater');
$title = $fields['title'] ?? __('Культурна платформа', 'dgutheater');
$title_tag = dgut_block_heading_tag($fields['title_tag'] ?? 'h2');
$posts = get_posts(['post_type' => 'post', 'posts_per_page' => -1, 'post_status' => 'publish']);
$items = [];
if ($posts) {
    foreach ($posts as $post) {
        $items[] = [
            'title' => get_the_title($post),
            'category' => get_the_category($post->ID)[0]->name ?? '',
            'date' => get_the_date('d.m.Y', $post),
            'excerpt' => get_the_excerpt($post),
            'image' => get_the_post_thumbnail_url($post, 'dgut-wide') ?: '',
            'focus' => 'center',
            'url' => get_permalink($post),
        ];
    }
}

$section_title = trim((string) $eyebrow);
if ($title !== '') {
    $section_title = $section_title !== '' ? $section_title . ' | ' . (string) $title : (string) $title;
}
?>
<section id="news" class="section dgut-news" data-carousel data-carousel-step="page">
    <div class="container">
        <div class="dgut-section-head">
            <<?php echo tag_escape($title_tag); ?> class="section-title"><?php echo esc_html($section_title); ?></<?php echo tag_escape($title_tag); ?>>
            <div class="dgut-news__actions">
                <a class="dgut-news__all" href="<?php echo esc_url(home_url('/news/')); ?>">
                    <?php esc_html_e('Усі новини', 'dgutheater'); ?>
                    <span aria-hidden="true">→</span>
                </a>
                <div class="slider-controls" aria-label="<?php esc_attr_e('News slider', 'dgutheater'); ?>">
                    <button class="slider-arrow" type="button" data-carousel-prev aria-label="<?php esc_attr_e('Попередня група новин', 'dgutheater'); ?>">
                        <?php echo dgut_ui_icon('chevron-left'); ?>
                    </button>
                    <button class="slider-arrow" type="button" data-carousel-next aria-label="<?php esc_attr_e('Наступна група новин', 'dgutheater'); ?>">
                        <?php echo dgut_ui_icon('chevron-right'); ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="dgut-news__viewport">
            <div class="dgut-news__grid" data-carousel-track>
                <?php foreach ($items as $item) : ?>
                    <article class="card dgut-news-card">
                        <a href="<?php echo esc_url($item['url'] ?? '#news'); ?>">
                            <div class="media-frame dgut-news-card__image">
                                <?php if (!empty($item['image'])) : ?>
                                    <?php echo dgut_img($item['image'], $item['title'], '', ['style' => 'object-position:' . ($item['focus'] ?? 'center')]); ?>
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