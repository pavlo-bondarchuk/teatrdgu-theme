<footer class="site-footer">
    <div class="container site-footer__grid">
        <div class="site-footer__brand">
            <a class="site-footer__logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php bloginfo('name'); ?>">
                <?php
                $logo_id = dgut_option('dgut_header_logo');
                if ($logo_id) {
                    echo wp_get_attachment_image((int) $logo_id, 'medium', false, [
                        'class' => 'site-logo__img',
                        'loading' => 'eager',
                        'decoding' => 'async',
                    ]);
                } else {
                ?>
                    <img class="site-logo__img" src="<?php echo esc_url(DGUTHEME_URI . '/assets/img/logo-dark.svg'); ?>" width="106" height="62" alt="<?php bloginfo('name'); ?>" decoding="async">
                <?php
                }
                ?>
            </a>
            <p>
                <?php echo dgut_option('dgut_footer_tagline', __('Дніпро Гордість України - культурний бренд міста', 'dgutheater')); ?>
            </p>
            <div class="site-footer__socials" aria-label="<?php esc_attr_e('Social links', 'dgutheater'); ?>">
                <?php foreach (get_field('socials', 'options') as $social) : ?>
                    <a href="<?php echo esc_url($social['social_link']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($social['social_type']); ?>">
                        <?php echo dgut_social_icon($social['social_type']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php $primary_location = 'primary'; ?>
        <?php if (has_nav_menu($primary_location)) : ?>
            <div class="site-footer__nav">
                <p class="footer-label"><?php esc_html_e('Навігація', 'dgutheater'); ?></p>
                <?php
                wp_nav_menu([
                    'theme_location' => $primary_location,
                    'container' => 'nav',
                    'container_aria_label' => __('Footer navigation', 'dgutheater'),
                    'menu_class' => 'site-footer__menu',
                    'fallback_cb' => false,
                    'depth' => 1,
                ]);
                ?>
            </div>
        <?php endif; ?>
        <div class="site-footer__contacts">
            <p class="footer-label"><?php esc_html_e('Контакти', 'dgutheater'); ?></p>
            <address class="footer-address">
                <span class="footer-address__row">
                    <span class="footer-address__icon"><?php echo dgut_ui_icon('map-pin'); ?></span>
                    <span><?php echo esc_html(str_replace("\n", ', ', (string) dgut_option('dgut_footer_address', "Троїцька площа, 5А\nДніпро"))); ?></span>
                </span>
                <a class="footer-address__row" href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', dgut_option('dgut_footer_phone', '+38 (067) 560-63-20'))); ?>">
                    <span class="footer-address__icon"><?php echo dgut_ui_icon('phone'); ?></span>
                    <span><?php echo esc_html(dgut_option('dgut_footer_phone', '+38 (067) 560-63-20')); ?></span>
                </a>
                <a class="footer-address__row" href="mailto:<?php echo esc_attr(dgut_option('dgut_footer_email', 'kvn.dgu@dhp.dniprorada.gov.ua')); ?>">
                    <span class="footer-address__icon"><?php echo dgut_ui_icon('mail'); ?></span>
                    <span><?php echo esc_html(dgut_option('dgut_footer_email', 'kvn.dgu@dhp.dniprorada.gov.ua')); ?></span>
                </a>
            </address>
        </div>
        <div class="site-footer__hours">
            <p class="footer-label"><?php esc_html_e('Графік роботи', 'dgutheater'); ?></p>
            <p><?php echo esc_html(dgut_option('dgut_footer_hours', 'Пн-Пт: 10:30-19:30')); ?></p>
        </div>
    </div>
    <div class="container site-footer__bottom">
        <span>© <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('Усі права захищені.', 'dgutheater'); ?></span>
        <a class="site-footer__map" href="<?php echo esc_url(dgut_option('dgut_footer_map_url', 'https://www.google.com/maps/search/?api=1&query=Троїцька+площа+5А,+Дніпро')); ?>" target="_blank" rel="noopener noreferrer">
            <?php echo dgut_ui_icon('map-pin'); ?>
            <span>Google Maps</span>
            <?php echo dgut_ui_icon('external-link'); ?>
        </a>
    </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>