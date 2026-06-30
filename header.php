<?php
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <a class="skip-link" href="#primary"><?php esc_html_e('Skip to content', 'dgutheater'); ?></a>
    <header class="site-header" data-site-header>
        <div class="container site-header__inner">
            <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php bloginfo('name'); ?>">
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

            <?php
            $primary_location = 'primary';
            $has_primary_menu = has_nav_menu($primary_location);
            ?>
            <?php if ($has_primary_menu) : ?>
                <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-controls="primary-menu">
                    <span></span><span></span><span></span>
                    <span class="screen-reader-text"><?php esc_html_e('Menu', 'dgutheater'); ?></span>
                </button>

                <nav id="primary-menu" class="primary-nav" data-primary-nav aria-label="<?php esc_attr_e('Primary navigation', 'dgutheater'); ?>">
                    <?php
                    wp_nav_menu([
                        'theme_location' => $primary_location,
                        'container' => false,
                        'menu_class' => 'primary-nav__list',
                        'fallback_cb' => false,
                        'depth' => 1,
                    ]);
                    ?>
                </nav>
            <?php endif; ?>

            <div class="site-header__tools" aria-label="<?php esc_attr_e('Header tools', 'dgutheater'); ?>">
                <div class="site-header__socials" aria-label="<?php esc_attr_e('Social links', 'dgutheater'); ?>">
                    <?php foreach (get_field('socials', 'options') as $social) : ?>
                        <a href="<?php echo esc_url($social['social_link']); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($social['social_type']); ?>">
                            <?php echo dgut_social_icon($social['social_type']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <?php if (function_exists('pll_the_languages')) : ?>
                    <?php $languages = pll_the_languages(['raw' => 1, 'hide_if_empty' => 0]); ?>
                    <?php if (!empty($languages)) : ?>
                        <span class="site-header__divider" aria-hidden="true"></span>
                        <div class="language-switcher" aria-label="<?php esc_attr_e('Language switcher', 'dgutheater'); ?>">
                            <?php foreach ($languages as $language) : ?>
                                <?php
                                $language_url = (string) ($language['url'] ?? '');
                                if ($language_url === '') {
                                    continue;
                                }
                                $language_label = strtoupper((string) ($language['slug'] ?? $language['name'] ?? ''));
                                $is_current_language = !empty($language['current_lang']);
                                ?>
                                <a class="<?php echo $is_current_language ? 'is-active' : ''; ?>" href="<?php echo esc_url($language_url); ?>" <?php echo $is_current_language ? 'aria-current="true"' : ''; ?>>
                                    <?php echo esc_html($language_label); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </header>
