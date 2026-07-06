<?php
get_header();

$post_id = get_the_ID();
$categories = get_the_category($post_id);
$category = !empty($categories) ? $categories[0]->name : __('Новини', 'dgutheater');
$news_archive_url = function_exists('dgut_news_archive_url') ? dgut_news_archive_url() : home_url('/novyny/');

$related_query = new WP_Query([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'post__not_in' => [$post_id],
    'ignore_sticky_posts' => true,
]);
?>

<main id="primary" class="site-main dgut-single-news-page">
    <section class="dgut-single-news-hero">
        <div class="container">
            <nav class="dgut-breadcrumbs" aria-label="<?php esc_attr_e('Хлібні крихти', 'dgutheater'); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Головна', 'dgutheater'); ?></a>
                <span aria-hidden="true">›</span>
                <a href="<?php echo esc_url($news_archive_url); ?>"><?php esc_html_e('Новини', 'dgutheater'); ?></a>
                <span aria-hidden="true">›</span>
                <span><?php the_title(); ?></span>
            </nav>

            <div class="dgut-single-news-hero__grid">
                <div class="dgut-single-news-hero__content">
                    <div class="dgut-news-card__meta">
                        <span><?php echo esc_html($category); ?></span>
                        <span class="dgut-news-card__meta-separator" aria-hidden="true"></span>
                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                            <?php echo esc_html(get_the_date('d.m.Y')); ?>
                        </time>
                    </div>

                    <h1 class="dgut-single-news-hero__title"><?php the_title(); ?></h1>

                    <?php if (has_excerpt()) : ?>
                        <p class="dgut-single-news-hero__excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
                    <?php endif; ?>
                </div>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="media-frame dgut-single-news-hero__image">
                        <?php the_post_thumbnail('full', [
                            'loading' => 'eager',
                            'fetchpriority' => 'high',
                        ]); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="dgut-single-news-content">
        <div class="container">
            <div class="dgut-single-news-content__inner">
                <?php the_content(); ?>
            </div>
        </div>
    </section>

    <?php
    $gallery = function_exists('get_field') ? get_field('news_gallery', $post_id) : [];
    $gallery = is_array($gallery) ? array_filter($gallery) : [];
    ?>

    <?php if (!empty($gallery)) : ?>
        <section class="dgut-single-news-gallery">
            <div class="container">
                <div class="dgut-section-head">
                    <h2 class="section-title"><?php esc_html_e('Фотогалерея', 'dgutheater'); ?></h2>
                </div>

                <div class="dgut-single-news-gallery__grid">
                    <?php foreach ($gallery as $image) : ?>
                        <?php
                        if (is_array($image)) {
                            $image_id = (int) ($image['ID'] ?? $image['id'] ?? 0);
                        } else {
                            $image_id = (int) $image;
                        }

                        if (!$image_id) {
                            continue;
                        }

                        $full_url = wp_get_attachment_image_url($image_id, 'full');

                        if (!$full_url) {
                            continue;
                        }
                        ?>

                        <a
                            class="media-frame dgut-single-news-gallery__item"
                            href="<?php echo esc_url($full_url); ?>"
                            target="_blank"
                            rel="noopener">
                            <?php
                            echo wp_get_attachment_image($image_id, 'dgut-card', false, [
                                'loading' => 'lazy',
                                'decoding' => 'async',
                            ]);
                            ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($related_query->have_posts()) : ?>
        <section class="dgut-single-news-related">
            <div class="container">
                <div class="dgut-section-head">
                    <h2 class="section-title"><?php esc_html_e('Читайте також', 'dgutheater'); ?></h2>

                    <a class="dgut-section-link" href="<?php echo esc_url($news_archive_url); ?>">
                        <?php esc_html_e('Усі новини', 'dgutheater'); ?> <span aria-hidden="true">→</span>
                    </a>
                </div>

                <div class="dgut-news-archive__grid">
                    <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                        <?php
                        $related_id = get_the_ID();
                        $related_categories = get_the_category($related_id);
                        $related_category = !empty($related_categories) ? $related_categories[0]->name : __('Новини', 'dgutheater');
                        $related_image_id = get_post_thumbnail_id($related_id);
                        ?>
                        <article class="card dgut-news-card dgut-news-archive-card">
                            <a href="<?php the_permalink(); ?>">
                                <div class="media-frame dgut-news-card__image">
                                    <?php if ($related_image_id) : ?>
                                        <?php echo dgut_responsive_news_image(
                                            (int) $related_image_id,
                                            get_the_title(),
                                            'dgut-news-card',
                                            '(max-width: 640px) calc(100vw - 40px), (max-width: 1180px) calc((100vw - 72px) / 2), 373px'
                                        ); ?>
                                    <?php endif; ?>
                                </div>

                                <div class="dgut-news-card__body">
                                    <div class="dgut-news-card__meta">
                                        <span><?php echo esc_html($related_category); ?></span>
                                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                            <?php echo esc_html(get_the_date('d.m.Y')); ?>
                                        </time>
                                    </div>

                                    <h3><?php the_title(); ?></h3>

                                    <?php if (has_excerpt()) : ?>
                                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                                    <?php else : ?>
                                        <p><?php echo esc_html(wp_trim_words(get_the_content(), 24)); ?></p>
                                    <?php endif; ?>

                                    <span class="dgut-news-card__read">
                                        <?php esc_html_e('Читати', 'dgutheater'); ?><span aria-hidden="true">›</span>
                                    </span>
                                </div>
                            </a>
                        </article>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
</main>

<?php
get_footer();
