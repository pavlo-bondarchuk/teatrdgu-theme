<?php
get_header();

$months = dgut_afisha_month_options();
$selected_month = dgut_afisha_selected_month($months);
$event_posts = dgut_afisha_posts_for_month($selected_month);
$events = array_values(array_filter(array_map('dgut_afisha_event_data', $event_posts)));
$breadcrumbs = dgut_yoast_breadcrumbs('dgut-breadcrumbs dgut-afisha-breadcrumbs');
?>
<main id="primary" class="site-main dgut-afisha-page">
    <?php if ($breadcrumbs !== '') : ?>
        <div class="container dgut-breadcrumbs-wrap dgut-afisha-breadcrumbs-wrap"><?php echo $breadcrumbs; ?></div>
    <?php endif; ?>

    <section class="dgut-afisha-archive">
        <div class="container">
            <header class="dgut-afisha-header">
                <p class="eyebrow"><?php esc_html_e('Найближчі вистави та культурні події', 'dgutheater'); ?></p>
                <h1 class="section-title"><?php esc_html_e('Афіша', 'dgutheater'); ?></h1>
            </header>

            <?php if ($months) : ?>
                <nav class="dgut-afisha-months" aria-label="<?php esc_attr_e('Оберіть місяць', 'dgutheater'); ?>">
                    <?php foreach ($months as $month => $label) : ?>
                        <a class="dgut-afisha-month<?php echo $month === $selected_month ? ' is-active' : ''; ?>" href="<?php echo esc_url(add_query_arg('month', $month, get_post_type_archive_link('dgut_event'))); ?>" <?php echo $month === $selected_month ? 'aria-current="page"' : ''; ?>>
                            <?php echo esc_html($label); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>

            <?php if ($events) : ?>
                <div class="dgut-afisha-grid">
                    <?php foreach ($events as $event) : ?>
                        <?php $sold = in_array($event['status_key'], ['sold_out', 'cancelled'], true); ?>
                        <article class="dgut-afisha-card">
                            <a class="dgut-afisha-card__media" href="<?php echo esc_url($event['permalink']); ?>">
                                <?php if ($event['image_id']) : ?>
                                    <?php echo dgut_responsive_image((int) $event['image_id'], 'dgut-afisha-card', (string) $event['title'], '(min-width: 1024px) 280px, (min-width: 640px) 45vw, 100vw'); ?>
                                <?php endif; ?>
                                <span class="dgut-afisha-card__date"><strong><?php echo esc_html($event['day']); ?></strong><span><?php echo esc_html($event['weekday']); ?></span></span>
                            </a>
                            <div class="dgut-afisha-card__body">
                                <p class="eyebrow"><?php echo esc_html($event['type']); ?></p>
                                <h2><a href="<?php echo esc_url($event['permalink']); ?>"><?php echo esc_html($event['title']); ?></a></h2>
                                <p class="dgut-afisha-card__time"><?php echo dgut_ui_icon('clock'); ?><?php echo esc_html($event['time']); ?></p>
                                <?php if ($event['venue']) : ?><p class="dgut-afisha-card__venue"><?php echo esc_html($event['venue']); ?></p><?php endif; ?>
                                <div class="dgut-afisha-card__footer">
                                    <span class="dgut-afisha-status dgut-afisha-status--<?php echo esc_attr($event['status_key']); ?>"><?php echo esc_html($event['status']); ?></span>
                                    <a href="<?php echo esc_url($event['ticket_url'] && !$sold ? $event['ticket_url'] : $event['permalink']); ?>" <?php echo $event['ticket_url'] && !$sold ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>><?php echo $event['ticket_url'] && !$sold ? esc_html__('Квитки', 'dgutheater') : esc_html__('Детальніше', 'dgutheater'); ?></a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="dgut-afisha-empty">
                    <h2><?php esc_html_e('У цьому місяці подій поки немає', 'dgutheater'); ?></h2>
                    <p><?php esc_html_e('Оберіть інший місяць або перегляньте репертуар театру.', 'dgutheater'); ?></p>
                    <a class="btn" href="<?php echo esc_url(get_post_type_archive_link('performance')); ?>"><?php esc_html_e('Переглянути репертуар', 'dgutheater'); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
