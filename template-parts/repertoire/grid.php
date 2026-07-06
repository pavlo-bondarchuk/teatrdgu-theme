<?php
$performances = isset($args['performances']) && is_array($args['performances']) ? $args['performances'] : [];
$filters = isset($args['filters']) && is_array($args['filters']) ? $args['filters'] : dgut_repertoire_filters();

if (empty($performances)) {
    return;
}
$title = isset($args['title']) && $args['title'] !== ''
    ? (string) $args['title']
    : __('Репертуар', 'dgutheater');
?>
<section class="dgut-repertoire-archive" data-repertoire>
    <div class="container">
        <div class="dgut-repertoire-archive__head">
            <h1 class="section-title dgut-repertoire-archive__title">
                <?php echo esc_html($title); ?>
            </h1>

            <?php if (!empty($filters)) : ?>
                <div class="dgut-repertoire-filters" role="group" aria-label="<?php echo esc_attr(dgut_repertoire_label('filter_aria')); ?>">
                    <button
                        class="dgut-repertoire-filter is-active"
                        type="button"
                        data-repertoire-filter="all"
                        aria-pressed="true">
                        <?php echo __('Усі вистави', 'dgutheater'); ?>
                    </button>
                    <?php
                    foreach ($filters as $index => $filter) :
                        $key = (string) ($filter['id'] ?? '');
                        $label = (string) ($filter['name'] ?? '');
                        if ($key === '' || $label === '') {
                            continue;
                        }
                    ?>
                        <button
                            class="dgut-repertoire-filter"
                            type="button"
                            data-repertoire-filter="<?php echo esc_attr($key); ?>"
                            aria-pressed="false">
                            <?php echo esc_html($label); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="dgut-repertoire-grid">
            <?php foreach ($performances as $performance) : ?>
                <?php
                $title = (string) ($performance['title'] ?? '');
                $permalink = (string) ($performance['permalink'] ?? '');

                if ($title === '' || $permalink === '') {
                    continue;
                }

                $genre = (string) ($performance['genre'] ?? '');
                $date = (string) ($performance['date'] ?? '');
                $excerpt = (string) ($performance['excerpt'] ?? '');
                $image = (string) ($performance['image'] ?? '');
                $thumbnail_id = (int) ($performance['thumbnail_id'] ?? 0);
                $focus = (string) ($performance['focus'] ?? 'center top');
                $filter_text = (string) ($performance['filter_text'] ?? '');
                $genre_ids = isset($performance['genre_ids']) && is_array($performance['genre_ids'])
                    ? array_filter(array_map('strval', $performance['genre_ids']))
                    : [];
                ?>
                <article
                    class="dgut-repertoire-card"
                    data-repertoire-card
                    data-repertoire-genres="<?php echo esc_attr(implode(' ', $genre_ids)); ?>"
                    data-filter-text="<?php echo esc_attr($filter_text); ?>">
                    <a class="dgut-repertoire-card__media" href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($title); ?>">
                        <?php if ($thumbnail_id > 0) : ?>
                            <?php echo dgut_responsive_image($thumbnail_id, 'dgut-repertoire-archive-card', $title, '(min-width: 1240px) 216px, (min-width: 1024px) calc((100vw - 160px) / 5), (min-width: 640px) calc((100vw - 88px) / 3), calc((100vw - 64px) / 2)', ['style' => 'object-position: ' . $focus . ';']); ?>
                        <?php elseif ($image !== '') : ?>
                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" decoding="async" style="object-position: <?php echo esc_attr($focus); ?>;">
                        <?php endif; ?>

                        <?php if ($genre !== '') : ?>
                            <span class="dgut-repertoire-card__badge"><?php echo esc_html($genre); ?></span>
                        <?php endif; ?>


                    </a>

                    <div class="dgut-repertoire-card__body">
                        <h2>
                            <a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a>
                        </h2>

                        <?php if ($excerpt !== '') : ?>
                            <p class="dgut-repertoire-card__excerpt"><?php echo esc_html($excerpt); ?></p>
                        <?php endif; ?>

                        <?php if ($date !== '') : ?>
                            <p class="dgut-repertoire-card__date">
                                <?php echo dgut_ui_icon('clock'); ?>
                                <span><?php echo esc_html($date); ?></span>
                            </p>
                        <?php endif; ?>

                        <a class="dgut-repertoire-card__button" href="<?php echo esc_url($permalink); ?>">
                            <?php echo esc_html(dgut_repertoire_label('details')); ?>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <p class="dgut-repertoire-empty" data-repertoire-empty hidden><?php echo esc_html(dgut_repertoire_label('empty')); ?></p>
    </div>
</section>
