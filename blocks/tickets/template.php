<?php
$fields = function_exists('get_fields') ? (array) get_fields() : [];
$show_block = $fields['show_block'] ?? true;
if ($show_block !== true) {
    return;
}

$eyebrow = $fields['eyebrow'] ?? __('Онлайн-сервіси', 'dgutheater');
$title = $fields['title'] ?? __('Оберіть сервіс для квитків', 'dgutheater');
$title_tag = dgut_block_heading_tag($fields['title_tag'] ?? 'h2');
$description = $fields['description'] ?? __('Продаж і повернення квитків відбуваються на стороні обраного офіційного сервісу.', 'dgutheater');
$providers = [];
?>
<section id="tickets" class="section dgut-tickets">
    <div class="container">
        <div class="dgut-tickets__intro">
            <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <<?php echo tag_escape($title_tag); ?> class="section-title"><?php echo esc_html($title); ?></<?php echo tag_escape($title_tag); ?>>
            <p><?php echo esc_html($description); ?></p>
        </div>
        <?php if (!empty($providers)) : ?>
            <div class="dgut-tickets__grid">
                <?php foreach ($providers as $provider) : ?>
                    <a class="dgut-ticket-card" href="<?php echo esc_url($provider['href']); ?>" target="_blank" rel="noopener noreferrer">
                        <img src="<?php echo esc_url($provider['logo']); ?>" alt="<?php echo esc_attr($provider['name']); ?>" loading="lazy" decoding="async">
                        <div>
                            <h3><?php echo esc_html($provider['name']); ?></h3>
                            <p><?php echo esc_html($provider['description']); ?></p>
                        </div>
                        <span aria-hidden="true">↗</span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
