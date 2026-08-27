<?php
get_header();
the_post();

$event = dgut_afisha_event_data(get_the_ID());
$content = trim((string) get_the_content());
$performance_id = (int) ($event['performance_id'] ?? 0);
$other_dates = dgut_afisha_other_dates(get_the_ID(), $performance_id);
$breadcrumbs = dgut_yoast_breadcrumbs('dgut-breadcrumbs dgut-afisha-breadcrumbs');
$ticket_disabled = in_array($event['status_key'] ?? '', ['sold_out', 'cancelled'], true);
?>
<main id="primary" class="site-main dgut-afisha-page dgut-afisha-single">
    <?php if ($breadcrumbs !== '') : ?>
        <div class="container dgut-breadcrumbs-wrap dgut-afisha-breadcrumbs-wrap"><?php echo $breadcrumbs; ?></div>
    <?php endif; ?>

    <section class="section dgut-afisha-single__hero">
        <div class="container dgut-afisha-single__grid">
            <?php if (!empty($event['image_id'])) : ?>
                <div class="dgut-afisha-single__image"><?php echo dgut_responsive_image((int) $event['image_id'], 'dgut-event-single', (string) $event['title'], '(min-width: 1024px) 446px, 100vw', ['loading' => 'eager', 'fetchpriority' => 'high']); ?></div>
            <?php endif; ?>
            <div class="dgut-afisha-single__content">
                <div class="dgut-afisha-single__labels">
                    <span class="eyebrow"><?php echo esc_html($event['type']); ?></span>
                    <?php if ($event['age']) : ?><span class="dgut-afisha-age"><?php echo esc_html($event['age']); ?></span><?php endif; ?>
                </div>
                <h1 class="display"><?php the_title(); ?></h1>
                <?php if ($event['excerpt']) : ?><p class="dgut-afisha-single__excerpt"><?php echo esc_html($event['excerpt']); ?></p><?php endif; ?>
                <div class="dgut-afisha-single__meta">
                    <div><span><?php esc_html_e('Дата', 'dgutheater'); ?></span><strong><?php echo esc_html($event['date']); ?></strong></div>
                    <div><span><?php esc_html_e('Час', 'dgutheater'); ?></span><strong><?php echo esc_html($event['time']); ?></strong></div>
                    <?php if ($event['venue']) : ?><div><span><?php esc_html_e('Місце', 'dgutheater'); ?></span><strong><?php echo esc_html($event['venue']); ?></strong></div><?php endif; ?>
                    <?php if ($event['duration']) : ?><div><span><?php esc_html_e('Тривалість', 'dgutheater'); ?></span><strong><?php echo esc_html($event['duration']); ?></strong></div><?php endif; ?>
                    <?php if ($event['price']) : ?><div><span><?php esc_html_e('Вартість', 'dgutheater'); ?></span><strong><?php echo esc_html($event['price']); ?></strong></div><?php endif; ?>
                </div>
                <div class="dgut-afisha-actions">
                    <?php if ($event['ticket_url'] && !$ticket_disabled) : ?><a class="btn" href="<?php echo esc_url($event['ticket_url']); ?>" target="_blank" rel="noopener noreferrer"><?php echo dgut_ui_icon('ticket'); ?><?php esc_html_e('Купити квиток', 'dgutheater'); ?></a><?php endif; ?>
                    <span class="dgut-afisha-status dgut-afisha-status--<?php echo esc_attr($event['status_key']); ?>"><?php echo esc_html($event['status']); ?></span>
                </div>
            </div>
        </div>
    </section>

    <?php if ($content !== '' || $event['excerpt']) : ?>
        <section class="section dgut-afisha-single__about">
            <div class="container dgut-afisha-single__about-grid">
                <div><p class="eyebrow"><?php esc_html_e('Про подію', 'dgutheater'); ?></p><h2 class="section-title"><?php esc_html_e('Подробиці', 'dgutheater'); ?></h2></div>
                <div class="flow"><?php echo $content !== '' ? wp_kses_post(apply_filters('the_content', $content)) : wpautop(esc_html($event['excerpt'])); ?></div>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($performance_id > 0 && $event['performance_url']) : ?>
        <section class="dgut-afisha-performance-link">
            <div class="container dgut-afisha-performance-link__inner">
                <div><p class="eyebrow"><?php esc_html_e('У репертуарі', 'dgutheater'); ?></p><h2><?php echo esc_html(get_the_title($performance_id)); ?></h2></div>
                <a class="btn btn--light" href="<?php echo esc_url($event['performance_url']); ?>"><?php esc_html_e('Про виставу', 'dgutheater'); ?></a>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($other_dates) : ?>
        <section class="section dgut-afisha-other">
            <div class="container">
                <p class="eyebrow"><?php esc_html_e('Ще можна побачити', 'dgutheater'); ?></p>
                <h2 class="section-title"><?php esc_html_e('Інші дати', 'dgutheater'); ?></h2>
                <div class="dgut-afisha-other__grid">
                    <?php foreach ($other_dates as $other_post) : $other = dgut_afisha_event_data($other_post); ?>
                        <a href="<?php echo esc_url($other['permalink']); ?>"><strong><?php echo esc_html($other['day']); ?></strong><span><?php echo esc_html($other['date'] . ' · ' . $other['time']); ?></span><?php echo dgut_ui_icon('chevron-right'); ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php get_footer(); ?>
