<?php
get_header();

$paged = max(1, (int) get_query_var('paged'));
?>

<main id="primary" class="site-main dgut-news-archive-page">
    <section class="section dgut-news-archive">
        <div class="container">
            <div class="dgut-section-head"> 
                <h1 class="section-title"><?php esc_html_e('Новини', 'dgutheater'); ?></h1>
            </div>

            <?php if (have_posts()) : ?>
                <div class="dgut-news-archive__grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php
                        $post_id = get_the_ID();
                        $categories = get_the_category($post_id);
                        $category = !empty($categories) ? $categories[0]->name : __('Новини', 'dgutheater');
                        $image_id = get_post_thumbnail_id($post_id);
                        ?>

                        <article class="card dgut-news-card dgut-news-archive-card">
                            <a href="<?php the_permalink(); ?>">
                                <div class="media-frame dgut-news-card__image">
                                    <?php if ($image_id) : ?>
                                        <?php echo dgut_responsive_news_image(
                                            (int) $image_id,
                                            get_the_title(),
                                            'dgut-news-card',
                                            '(max-width: 640px) calc(100vw - 40px), (max-width: 1180px) calc((100vw - 72px) / 2), 373px'
                                        ); ?>
                                    <?php endif; ?>
                                </div>

                                <div class="dgut-news-card__body">
                                    <div class="dgut-news-card__meta">
                                        <span><?php echo esc_html($category); ?></span>
                                        <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                            <?php echo esc_html(get_the_date('d.m.Y')); ?>
                                        </time>
                                    </div>

                                    <h2><?php the_title(); ?></h2>

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

                <?php
                $pagination = paginate_links([
                    'mid_size' => 1,
                    'prev_text' => __('← Назад', 'dgutheater'),
                    'next_text' => __('Далі →', 'dgutheater'),
                ]);
                ?>

                <?php if ($pagination) : ?>
                    <nav class="navigation pagination" aria-label="<?php esc_attr_e('Пагінація новин', 'dgutheater'); ?>">
                        <div class="nav-links">
                            <?php echo wp_kses_post($pagination); ?>
                        </div>
                    </nav>
                <?php endif; ?>
            <?php else : ?>
                <p class="dgut-news-archive__empty"><?php esc_html_e('Новин поки немає.', 'dgutheater'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
