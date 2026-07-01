<?php
/**
 * Template Name: Квитки
 */

get_header();

$fields = dgut_page_fields();

$tickets_title = (string) ($fields['tickets_title'] ?? __('Купити квиток', 'dgutheater'));
$tickets_intro = (string) ($fields['tickets_intro'] ?? __('Квитки продаються через офіційні онлайн-сервіси. Оберіть зручний сервіс та придбайте квиток онлайн.', 'dgutheater'));
$tickets_phone_text = (string) ($fields['tickets_phone_text'] ?? __('Або телефонуйте: +380974136285 (з 10 до 18).', 'dgutheater'));
$tickets_phone_url = (string) ($fields['tickets_phone_url'] ?? 'tel:+380974136285');
$tickets_repertoire_link = $fields['tickets_repertoire_link'] ?? [
    'url' => dgut_front_page_url('repertoire', '/repertoire/'),
    'title' => __('Переглянути репертуар', 'dgutheater'),
    'target' => '',
];
$ticket_services = $fields['tickets_services'] ?? [];

if (!is_array($ticket_services) || empty($ticket_services)) {
    $ticket_services = [
        [
            'logo' => dgut_media('ticket-providers/wayforpay.png'),
            'title' => 'WayForPay',
            'description' => __('Прямий онлайн-продаж квитків на актуальні події театру.', 'dgutheater'),
            'link' => [
                'url' => 'https://secure.wayforpay.com/payment/matinkakurazhtasniperlyuty180626',
                'title' => __('Перейти', 'dgutheater'),
                'target' => '_blank',
            ],
        ],
        [
            'logo' => dgut_media('ticket-providers/karabas-dark.png'),
            'title' => 'Karabas',
            'description' => __('Афіша та продаж квитків на події простору «Театральний поверх».', 'dgutheater'),
            'link' => [
                'url' => 'https://dnipro.karabas.com/hall/tvorchij-prostir-teatralnij-poverh/',
                'title' => __('Перейти', 'dgutheater'),
                'target' => '_blank',
            ],
        ],
        [
            'logo' => dgut_media('ticket-providers/internet-bilet-ua.png'),
            'title' => 'Інтернет Білет',
            'description' => __('Онлайн-купівля квитків через сервіс Internet Bilet.', 'dgutheater'),
            'link' => [
                'url' => 'https://internet-bilet.ua/uk/hall/teatralnij-poverh',
                'title' => __('Перейти', 'dgutheater'),
                'target' => '_blank',
            ],
        ],
        [
            'logo' => dgut_media('ticket-providers/kontramarka-dark.png'),
            'title' => 'Контрамарка',
            'description' => __('Продаж квитків на майданчик через сервіс Kontramarka.', 'dgutheater'),
            'link' => [
                'url' => 'https://dnipro.kontramarka.ua/uk/theatre/budinok-na-troickij-j-poverh-2757.html',
                'title' => __('Перейти', 'dgutheater'),
                'target' => '_blank',
            ],
        ],
    ];
}

$repertoire_url = is_array($tickets_repertoire_link) ? (string) ($tickets_repertoire_link['url'] ?? '') : (string) $tickets_repertoire_link;
$repertoire_label = is_array($tickets_repertoire_link) ? (string) ($tickets_repertoire_link['title'] ?? '') : __('Переглянути репертуар', 'dgutheater');
$repertoire_target = is_array($tickets_repertoire_link) ? (string) ($tickets_repertoire_link['target'] ?? '') : '';
?>
<main id="primary" class="site-main dgut-tickets-page">
    <section class="section dgut-tickets-page__section">
        <div class="container dgut-tickets-page__container">
            <?php if ($tickets_title !== '') : ?>
                <h1 class="dgut-tickets-page__title"><?php echo esc_html($tickets_title); ?></h1>
            <?php endif; ?>

            <?php if ($tickets_intro !== '') : ?>
                <div class="dgut-tickets-page__intro">
                    <?php echo wp_kses_post(wpautop($tickets_intro)); ?>
                </div>
            <?php endif; ?>

            <?php if ($tickets_phone_text !== '') : ?>
                <p class="dgut-tickets-page__phone">
                    <?php if ($tickets_phone_url !== '') : ?>
                        <a href="<?php echo esc_url($tickets_phone_url); ?>"><?php echo esc_html($tickets_phone_text); ?></a>
                    <?php else : ?>
                        <?php echo esc_html($tickets_phone_text); ?>
                    <?php endif; ?>
                </p>
            <?php endif; ?>

            <div class="dgut-tickets-page__grid">
                <?php foreach ($ticket_services as $service) : ?>
                    <?php
                    if (!is_array($service)) {
                        continue;
                    }

                    $service_logo = dgut_get_image_from_field($service['logo'] ?? '');
                    $service_title = (string) ($service['title'] ?? '');
                    $service_description = (string) ($service['description'] ?? '');
                    $service_link = $service['link'] ?? [];
                    $service_url = is_array($service_link) ? (string) ($service_link['url'] ?? '') : (string) $service_link;
                    $service_label = is_array($service_link) ? (string) ($service_link['title'] ?? '') : '';
                    $service_target = is_array($service_link) ? (string) ($service_link['target'] ?? '') : '';

                    if ($service_url === '' && $service_title === '' && $service_description === '' && $service_logo === '') {
                        continue;
                    }
                    ?>
                    <article class="dgut-tickets-card">
                        <?php if ($service_logo !== '') : ?>
                            <img class="dgut-tickets-card__logo" src="<?php echo esc_url($service_logo); ?>" alt="<?php echo esc_attr($service_title); ?>" loading="lazy" decoding="async">
                        <?php endif; ?>
                        <?php if ($service_title !== '') : ?>
                            <h2><?php echo esc_html($service_title); ?></h2>
                        <?php endif; ?>
                        <?php if ($service_description !== '') : ?>
                            <p><?php echo esc_html($service_description); ?></p>
                        <?php endif; ?>
                        <?php if ($service_url !== '') : ?>
                            <a class="dgut-tickets-card__link" href="<?php echo esc_url($service_url); ?>" <?php echo $service_target !== '' ? 'target="' . esc_attr($service_target) . '" rel="noopener noreferrer"' : ''; ?>>
                                <span><?php echo esc_html($service_label !== '' ? $service_label : __('Перейти', 'dgutheater')); ?></span>
                                <?php echo dgut_ui_icon('external-link'); ?>
                            </a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ($repertoire_url !== '' && $repertoire_label !== '') : ?>
                <div class="dgut-tickets-page__footer">
                    <a class="dgut-tickets-page__repertoire" href="<?php echo esc_url($repertoire_url); ?>" <?php echo $repertoire_target !== '' ? 'target="' . esc_attr($repertoire_target) . '" rel="noopener noreferrer"' : ''; ?>>
                        <span><?php echo esc_html($repertoire_label); ?></span>
                        <?php echo dgut_ui_icon('chevron-right'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php
get_footer();
