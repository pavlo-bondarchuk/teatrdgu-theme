<?php
$performances = isset($args['performances']) && is_array($args['performances']) ? $args['performances'] : [];
$filters = isset($args['filters']) && is_array($args['filters']) ? $args['filters'] : dgut_repertoire_filters();

if (empty($performances)) {
    return;
}
?>
<section class="dgut-repertoire-archive" data-repertoire>
    <div class="container">
        <div class="dgut-repertoire-archive__head">
            <h1 class="section-title dgut-repertoire-archive__title"><?php echo esc_html(dgut_repertoire_label('title')); ?></h1>

            <?php if (!empty($filters)) : ?>
                <div class="dgut-repertoire-filters" role="list" aria-label="<?php echo esc_attr(dgut_repertoire_label('filter_aria')); ?>">
                    <?php foreach ($filters as $index => $filter) : ?>
                        <?php
                        $key = (string) ($filter['key'] ?? '');
                        $label = (string) ($filter['label'] ?? '');
                        if ($key === '' || $label === '') {
                            continue;
                        }
                        ?>
                        <button
                            class="dgut-repertoire-filter<?php echo $index === 0 ? ' is-active' : ''; ?>"
                            type="button"
                            data-repertoire-filter="<?php echo esc_attr($key); ?>"
                            aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                        >
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
                $focus = (string) ($performance['focus'] ?? 'center top');
                $filter_text = (string) ($performance['filter_text'] ?? '');
                ?>
                <article class="dgut-repertoire-card" data-repertoire-card data-filter-text="<?php echo esc_attr($filter_text); ?>">
                    <a class="dgut-repertoire-card__media" href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($title); ?>">
                        <?php if ($image !== '') : ?>
                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" decoding="async" style="object-position: <?php echo esc_attr($focus); ?>;">
                        <?php endif; ?>

                        <?php if ($genre !== '') : ?>
                            <span class="dgut-repertoire-card__badge"><?php echo esc_html($genre); ?></span>
                        <?php endif; ?>

                        <span class="dgut-repertoire-card__overlay" aria-hidden="true"><?php echo esc_html(dgut_repertoire_label('details')); ?></span>
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
