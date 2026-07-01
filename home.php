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
                        $image = get_the_post_thumbnail_url($post_id, 'dgut-news-grid-card');
                        ?>

                        <article class="card dgut-news-card dgut-news-archive-card">
                            <a href="<?php the_permalink(); ?>">
                                <div class="media-frame dgut-news-card__image">
                                    <?php if ($image) : ?>
                                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" decoding="async">
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
                the_posts_pagination([
                    'mid_size' => 1,
                    'prev_text' => __('← Назад', 'dgutheater'),
                    'next_text' => __('Далі →', 'dgutheater'),
                ]);
                ?>
            <?php else : ?>
                <p class="dgut-news-archive__empty"><?php esc_html_e('Новин поки немає.', 'dgutheater'); ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
