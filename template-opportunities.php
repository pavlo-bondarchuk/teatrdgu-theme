<?php
/**
 * Template Name: Можливості ДГУ
 */

get_header();

$fields = dgut_page_fields();
$page_id = get_queried_object_id();

$hero_title = trim((string) ($fields['opportunities_hero_title'] ?? ''));
$hero_text = trim((string) ($fields['opportunities_hero_text'] ?? ''));
$hero_button_link = $fields['opportunities_hero_button_link'] ?? [];
$hero_image_id = (int) ($fields['opportunities_hero_image'] ?? 0);
$hero_badge = trim((string) ($fields['opportunities_hero_badge'] ?? ''));
$hero_secondary_link = $fields['opportunities_hero_secondary_link'] ?? [];

$opportunities_title = trim((string) ($fields['opportunities_title'] ?? ''));
$opportunities_subtitle = trim((string) ($fields['opportunities_subtitle'] ?? ''));
$opportunities_tabs = $fields['opportunities_tabs'] ?? [];
$opportunities_tabs = is_array($opportunities_tabs) ? array_values(array_filter(
    $opportunities_tabs,
    static fn (mixed $tab): bool => is_array($tab) && trim((string) ($tab['tab_title'] ?? '')) !== ''
)) : [];

$normalize_link = static function (mixed $link): array {
    if (!is_array($link)) {
        return [];
    }

    $url = trim((string) ($link['url'] ?? ''));
    $label = trim((string) ($link['title'] ?? ''));
    if ($url === '' || $label === '') {
        return [];
    }

    return [
        'url' => $url,
        'label' => $label,
        'target' => (string) ($link['target'] ?? ''),
    ];
};

$hero_button = $normalize_link($hero_button_link);
$secondary_link = $normalize_link($hero_secondary_link);
$has_hero_content = $hero_title !== '' || $hero_text !== '' || $hero_button || $secondary_link;
$has_hero = $has_hero_content || $hero_image_id > 0 || $hero_badge !== '';
$has_opportunities_section = $opportunities_title !== '' || $opportunities_subtitle !== '' || $opportunities_tabs;
$tabs_label = $opportunities_title !== '' ? $opportunities_title : __('Можливості', 'dgutheater');
$hero_alt = $hero_image_id > 0 ? trim((string) get_post_meta($hero_image_id, '_wp_attachment_image_alt', true)) : '';
if ($hero_alt === '') {
    $hero_alt_source = $hero_title !== '' ? $hero_title : get_the_title($page_id);
    $hero_alt = wp_strip_all_tags(str_replace(["\r", "\n"], ' ', $hero_alt_source));
}

$component_id = 'opportunities-' . max(1, $page_id);
?>
<main id="primary" class="site-main opportunities-page">
    <?php if ($has_hero) : ?>
        <section class="opportunities-hero<?php echo $has_hero_content ? '' : ' opportunities-hero--media-only'; ?>">
            <?php if ($has_hero_content) : ?>
                <div class="opportunities-hero__content">
                    <?php if ($hero_title !== '') : ?>
                        <h1 class="opportunities-hero__title"><?php echo nl2br(esc_html($hero_title)); ?></h1>
                    <?php endif; ?>

                    <?php if ($hero_text !== '') : ?>
                        <div class="opportunities-hero__text">
                            <?php echo wp_kses_post(wpautop($hero_text)); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($hero_button || $secondary_link) : ?>
                        <div class="opportunities-hero__actions">
                            <?php if ($hero_button) : ?>
                                <a
                                    class="btn opportunities-hero__button"
                                    href="<?php echo esc_url($hero_button['url']); ?>"
                                    target="<?php echo esc_attr($hero_button['target'] !== '' ? $hero_button['target'] : '_self'); ?>"
                                    <?php echo $hero_button['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
                                >
                                    <?php echo esc_html($hero_button['label']); ?>
                                </a>
                            <?php endif; ?>

                            <?php if ($secondary_link) : ?>
                                <a
                                    class="opportunities-hero__secondary-link"
                                    href="<?php echo esc_url($secondary_link['url']); ?>"
                                    target="<?php echo esc_attr($secondary_link['target'] !== '' ? $secondary_link['target'] : '_self'); ?>"
                                    <?php echo $secondary_link['target'] === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
                                >
                                    <?php echo esc_html($secondary_link['label']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="opportunities-hero__media">
                <?php if ($hero_image_id > 0) : ?>
                    <?php
                    echo wp_get_attachment_image($hero_image_id, 'dgut-hero-slide', false, [
                        'class' => 'opportunities-hero__image',
                        'alt' => $hero_alt,
                        'loading' => 'eager',
                        'fetchpriority' => 'high',
                        'decoding' => 'async',
                        'sizes' => '(max-width: 900px) 100vw, 62vw',
                    ]);
                    ?>
                <?php endif; ?>

                <?php if ($hero_badge !== '') : ?>
                    <div class="opportunities-hero__badge">
                        <?php echo nl2br(esc_html($hero_badge)); ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($has_opportunities_section) : ?>
        <section
            class="opportunities-tabs-section"
            <?php echo $opportunities_title !== '' ? 'aria-labelledby="' . esc_attr($component_id) . '-title"' : 'aria-label="' . esc_attr($tabs_label) . '"'; ?>
        >
            <div class="container">
                <?php if ($opportunities_title !== '' || $opportunities_subtitle !== '') : ?>
                    <header class="opportunities-tabs-section__header">
                        <?php if ($opportunities_title !== '') : ?>
                            <h2 id="<?php echo esc_attr($component_id); ?>-title" class="opportunities-tabs-section__title">
                                <?php echo esc_html($opportunities_title); ?>
                            </h2>
                        <?php endif; ?>

                        <?php if ($opportunities_subtitle !== '') : ?>
                            <div class="opportunities-tabs-section__subtitle">
                                <?php echo wp_kses_post(wpautop($opportunities_subtitle)); ?>
                            </div>
                        <?php endif; ?>
                    </header>
                <?php endif; ?>

                <?php if ($opportunities_tabs) : ?>
                    <div class="opportunities-tabs" data-opportunities-tabs>
                        <div
                            class="opportunities-tabs__list"
                            role="tablist"
                            aria-label="<?php echo esc_attr($tabs_label); ?>"
                        >
                        <?php foreach ($opportunities_tabs as $index => $tab) : ?>
                            <?php
                            $tab_number = $index + 1;
                            $tab_id = $component_id . '-tab-' . $tab_number;
                            $panel_id = $component_id . '-panel-' . $tab_number;
                            $is_active = $index === 0;
                            ?>
                            <button
                                id="<?php echo esc_attr($tab_id); ?>"
                                class="opportunities-tabs__tab<?php echo $is_active ? ' is-active' : ''; ?>"
                                type="button"
                                role="tab"
                                aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                                aria-controls="<?php echo esc_attr($panel_id); ?>"
                                tabindex="<?php echo $is_active ? '0' : '-1'; ?>"
                            >
                                <?php echo esc_html((string) $tab['tab_title']); ?>
                            </button>
                        <?php endforeach; ?>
                        </div>

                        <div class="opportunities-tabs__panels">
                        <?php foreach ($opportunities_tabs as $index => $tab) : ?>
                            <?php
                            $tab_number = $index + 1;
                            $tab_id = $component_id . '-tab-' . $tab_number;
                            $panel_id = $component_id . '-panel-' . $tab_number;
                            $tab_title = trim((string) ($tab['tab_title'] ?? ''));
                            $tab_content = (string) ($tab['tab_content'] ?? '');
                            $tab_icon_id = (int) ($tab['tab_icon'] ?? 0);
                            $is_active = $index === 0;
                            ?>
                            <div
                                id="<?php echo esc_attr($panel_id); ?>"
                                class="opportunities-tabs__panel<?php echo $tab_icon_id > 0 ? ' opportunities-tabs__panel--with-icon' : ''; ?>"
                                role="tabpanel"
                                aria-labelledby="<?php echo esc_attr($tab_id); ?>"
                                tabindex="0"
                                <?php echo $is_active ? '' : 'hidden'; ?>
                            >
                                <?php if ($tab_icon_id > 0) : ?>
                                    <div class="opportunities-tabs__panel-icon" aria-hidden="true">
                                        <?php
                                        echo wp_get_attachment_image($tab_icon_id, 'thumbnail', false, [
                                            'alt' => '',
                                            'loading' => 'lazy',
                                            'decoding' => 'async',
                                            'sizes' => '72px',
                                        ]);
                                        ?>
                                    </div>
                                <?php endif; ?>

                                <div class="opportunities-tabs__panel-content">
                                    <h3><?php echo esc_html($tab_title); ?></h3>
                                    <?php if ($tab_content !== '') : ?>
                                        <?php echo wp_kses_post($tab_content); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php
get_footer();
