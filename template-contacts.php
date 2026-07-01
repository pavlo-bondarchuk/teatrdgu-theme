<?php
/**
 * Template Name: Контакти
 */

get_header();

$fields = dgut_page_fields();

$contacts_title = (string) ($fields['contacts_title'] ?? __('Контакти', 'dgutheater'));
$contacts_intro = (string) ($fields['contacts_intro'] ?? __('Ми завжди відкриті до спілкування.' . "\n" . 'Зв\'яжіться з нами зручним для вас способом.', 'dgutheater'));
$contacts_form_title = (string) ($fields['contacts_form_title'] ?? __('Напишіть нам', 'dgutheater'));
$contacts_map_link = $fields['contacts_map_link'] ?? [
    'url' => dgut_option('dgut_footer_map_url', 'https://www.google.com/maps/search/?api=1&query=Троїцька+площа+5А,+Дніпро'),
    'title' => __('Переглянути на Google Maps', 'dgutheater'),
    'target' => '_blank',
];
$contacts_items = $fields['contacts_items'] ?? [];
$contacts_form = $fields['contacts_form'] ?? null;
$background_image = dgut_get_image_from_field($fields['contacts_background_image'] ?? '', dgut_asset('img/contact-map.png'));

if (!is_array($contacts_items) || empty($contacts_items)) {
    $contacts_items = [
        [
            'icon' => 'map-pin',
            'label' => __('Адреса', 'dgutheater'),
            'text' => "Троїцька площа, 5А\nДніпро, 49100",
            'link' => '',
        ],
        [
            'icon' => 'phone',
            'label' => __('Телефон', 'dgutheater'),
            'text' => dgut_option('dgut_footer_phone', '+38 (067) 560-63-20'),
            'link' => 'tel:' . preg_replace('/[^0-9+]/', '', (string) dgut_option('dgut_footer_phone', '+38 (067) 560-63-20')),
        ],
        [
            'icon' => 'mail',
            'label' => 'Email',
            'text' => dgut_option('dgut_footer_email', 'kvn.dgu@dhp.dniprorada.gov.ua'),
            'link' => 'mailto:' . dgut_option('dgut_footer_email', 'kvn.dgu@dhp.dniprorada.gov.ua'),
        ],
        [
            'icon' => 'clock',
            'label' => __('Графік роботи', 'dgutheater'),
            'text' => dgut_option('dgut_footer_hours', 'Пн-Пт: 10:30-19:30'),
            'link' => '',
        ],
    ];
}

$map_url = is_array($contacts_map_link) ? (string) ($contacts_map_link['url'] ?? '') : (string) $contacts_map_link;
$map_label = is_array($contacts_map_link) ? (string) ($contacts_map_link['title'] ?? '') : __('Переглянути на Google Maps', 'dgutheater');
$map_target = is_array($contacts_map_link) ? (string) ($contacts_map_link['target'] ?? '') : '_blank';

$form_id = 0;
if ($contacts_form instanceof WP_Post) {
    $form_id = (int) $contacts_form->ID;
} elseif (is_numeric($contacts_form)) {
    $form_id = (int) $contacts_form;
}
?>
<main id="primary" class="site-main dgut-contacts-page">
    <section class="dgut-contacts-page__section" style="--dgut-contact-bg: url('<?php echo esc_url($background_image); ?>');">
        <div class="container dgut-contacts-page__container">
            <div class="dgut-contacts-panel">
                <div class="dgut-contacts-panel__intro">
                    <?php if ($contacts_title !== '') : ?>
                        <h1><?php echo esc_html($contacts_title); ?></h1>
                    <?php endif; ?>
                    <?php if ($contacts_intro !== '') : ?>
                        <div class="dgut-contacts-panel__text">
                            <?php echo wp_kses_post(wpautop($contacts_intro)); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="dgut-contacts-panel__grid">
                    <div class="dgut-contacts-details">
                        <div class="dgut-contacts-details__list">
                            <?php foreach ($contacts_items as $item) : ?>
                                <?php
                                if (!is_array($item)) {
                                    continue;
                                }

                                $item_icon = (string) ($item['icon'] ?? 'map-pin');
                                $item_label = (string) ($item['label'] ?? '');
                                $item_text = (string) ($item['text'] ?? '');
                                $item_link = (string) ($item['link'] ?? '');

                                if ($item_label === '' && $item_text === '') {
                                    continue;
                                }

                                $tag = $item_link !== '' ? 'a' : 'div';
                                ?>
                                <<?php echo esc_html($tag); ?> class="dgut-contact-card" <?php echo $item_link !== '' ? 'href="' . esc_url($item_link) . '"' : ''; ?>>
                                    <span class="dgut-contact-card__icon"><?php echo dgut_ui_icon($item_icon); ?></span>
                                    <span class="dgut-contact-card__body">
                                        <?php if ($item_label !== '') : ?>
                                            <span class="dgut-contact-card__label"><?php echo esc_html($item_label); ?></span>
                                        <?php endif; ?>
                                        <?php if ($item_text !== '') : ?>
                                            <span class="dgut-contact-card__value"><?php echo esc_html($item_text); ?></span>
                                        <?php endif; ?>
                                    </span>
                                </<?php echo esc_html($tag); ?>>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($map_url !== '' && $map_label !== '') : ?>
                            <a class="dgut-contacts-map-link" href="<?php echo esc_url($map_url); ?>" <?php echo $map_target !== '' ? 'target="' . esc_attr($map_target) . '" rel="noopener noreferrer"' : ''; ?>>
                                <?php echo dgut_ui_icon('map-pin'); ?>
                                <span><?php echo esc_html($map_label); ?></span>
                                <?php echo dgut_ui_icon('external-link'); ?>
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="dgut-contacts-form">
                        <?php if ($contacts_form_title !== '') : ?>
                            <h2><?php echo esc_html($contacts_form_title); ?></h2>
                        <?php endif; ?>

                        <?php if ($form_id > 0 && shortcode_exists('contact-form-7')) : ?>
                            <?php echo do_shortcode('[contact-form-7 id="' . absint($form_id) . '"]'); ?>
                        <?php else : ?>
                            <p class="dgut-contacts-form__empty">
                                <?php esc_html_e('Оберіть контактну форму в налаштуваннях сторінки.', 'dgutheater'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php
get_footer();
