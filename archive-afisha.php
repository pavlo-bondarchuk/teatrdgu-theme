<?php
get_header();

$months = dgut_afisha_month_options();
$selected_month = dgut_afisha_selected_month($months);
$performance_posts = dgut_afisha_posts_for_month($selected_month);
$events = array_values(array_filter(array_map('dgut_afisha_performance_data', $performance_posts)));
?>
<main id="primary" class="site-main dgut-afisha-page">
    <div class="container dgut-breadcrumbs-wrap dgut-afisha-breadcrumbs-wrap">
        <nav class="dgut-breadcrumbs dgut-afisha-breadcrumbs" aria-label="<?php esc_attr_e('Хлібні крихти', 'dgutheater'); ?>">
            <span>
                <span><a href="<?php echo esc_url(function_exists('pll_home_url') ? pll_home_url() : home_url('/')); ?>"><?php esc_html_e('Головна', 'dgutheater'); ?></a></span>
                <?php echo dgut_breadcrumb_separator_icon(); ?>
                <span class="breadcrumb_last" aria-current="page"><?php esc_html_e('Афіша', 'dgutheater'); ?></span>
            </span>
        </nav>
    </div>

    <section class="dgut-afisha-archive">
        <div class="container">
            <header class="dgut-afisha-header">
                <p class="eyebrow"><?php esc_html_e('Найближчі вистави та культурні події', 'dgutheater'); ?></p>
                <h1 class="section-title"><?php esc_html_e('Афіша', 'dgutheater'); ?></h1>
            </header>

            <?php if ($months) : ?>
                <nav class="dgut-afisha-months" aria-label="<?php esc_attr_e('Оберіть місяць', 'dgutheater'); ?>">
                    <?php foreach ($months as $month => $label) : ?>
                        <a class="dgut-afisha-month<?php echo $month === $selected_month ? ' is-active' : ''; ?>" href="<?php echo esc_url(add_query_arg('month', $month, dgut_afisha_archive_url())); ?>" <?php echo $month === $selected_month ? 'aria-current="page"' : ''; ?>>
                            <?php echo esc_html($label); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>

            <?php if ($events) : ?>
                <div class="dgut-afisha-grid">
                    <?php foreach ($events as $event) : ?>
                        <article class="dgut-afisha-card">
                            <a class="dgut-afisha-card__media" href="<?php echo esc_url($event['permalink']); ?>">
                                <?php if ($event['image_id']) : ?>
                                    <?php echo dgut_responsive_image((int) $event['image_id'], (string) $event['image_size'], (string) $event['title'], '(min-width: 1024px) 380px, (min-width: 640px) 45vw, 100vw'); ?>
                                <?php endif; ?>
                                <span class="dgut-afisha-card__date"><strong><?php echo esc_html($event['day']); ?></strong><span><?php echo esc_html($event['weekday']); ?></span></span>
                            </a>
                            <div class="dgut-afisha-card__body">
                                <p class="eyebrow"><?php echo esc_html($event['type']); ?></p>
                                <h2><a href="<?php echo esc_url($event['permalink']); ?>"><?php echo esc_html($event['title']); ?></a></h2>
                                <?php if ($event['time']) : ?>
                                    <p class="dgut-afisha-card__time"><?php echo dgut_ui_icon('clock'); ?><?php echo esc_html($event['time']); ?></p>
                                <?php endif; ?>
                                <div class="dgut-afisha-card__footer">
                                    <a href="<?php echo esc_url($event['permalink']); ?>"><?php esc_html_e('Детальніше', 'dgutheater'); ?></a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="dgut-afisha-empty">
                    <h2><?php esc_html_e('У цьому місяці вистав поки немає', 'dgutheater'); ?></h2>
                    <p><?php esc_html_e('Оберіть інший місяць або перегляньте весь репертуар театру.', 'dgutheater'); ?></p>
                    <a class="btn" href="<?php echo esc_url(get_post_type_archive_link('performance')); ?>"><?php esc_html_e('Переглянути репертуар', 'dgutheater'); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php get_footer(); ?>
