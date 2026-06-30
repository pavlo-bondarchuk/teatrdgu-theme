<?php
$fields = function_exists('get_fields') ? (array) get_fields() : [];
$show_block = $fields['show_block'] ?? true;
if ($show_block !== true) {
    return;
}

$eyebrow = $fields['eyebrow'] ?? __('Контакти', 'dgutheater');
$title = $fields['title'] ?? __('Театр ДГУ', 'dgutheater');
$title_tag = dgut_block_heading_tag($fields['title_tag'] ?? 'h2');
?>
<section id="contacts" class="section dgut-contact">
    <div class="container dgut-contact__grid">
        <div class="dgut-contact__content">
            <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <<?php echo tag_escape($title_tag); ?> class="section-title"><?php echo esc_html($title); ?></<?php echo tag_escape($title_tag); ?>>
            <div class="dgut-contact__details">
                <p><?php echo nl2br(esc_html(dgut_option('dgut_footer_address', "Троїцька площа, 5А\nДніпро, 49100"))); ?></p>
                <p><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', dgut_option('dgut_footer_phone', '+38 (067) 560-63-20'))); ?>"><?php echo esc_html(dgut_option('dgut_footer_phone', '+38 (067) 560-63-20')); ?></a></p>
                <p><a href="mailto:<?php echo esc_attr(dgut_option('dgut_footer_email', 'kvn.dgu@dhp.dniprorada.gov.ua')); ?>"><?php echo esc_html(dgut_option('dgut_footer_email', 'kvn.dgu@dhp.dniprorada.gov.ua')); ?></a></p>
                <p><?php echo esc_html(dgut_option('dgut_footer_hours', 'Пн-Пт: 10:30-19:30')); ?></p>
            </div>
            <a class="btn" href="<?php echo esc_url(dgut_option('dgut_footer_map_url', 'https://www.google.com/maps/search/?api=1&query=Троїцька+площа+5А,+Дніпро')); ?>" target="_blank" rel="noopener noreferrer">Google Maps</a>
        </div>
        <div class="media-frame dgut-contact__map">
            <img src="<?php echo esc_url(DGUTHEME_URI . '/assets/img/contact-map.png'); ?>" alt="<?php esc_attr_e('Theatre location map', 'dgutheater'); ?>" loading="lazy" decoding="async">
        </div>
    </div>
</section>
