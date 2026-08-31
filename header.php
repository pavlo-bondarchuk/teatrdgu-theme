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
            $header_cta = function_exists('get_field') ? get_field('header_cta_link', 'option') : [];
            $header_cta_url = is_array($header_cta) ? trim((string) ($header_cta['url'] ?? '')) : '';
            $header_cta_label = is_array($header_cta) ? trim((string) ($header_cta['title'] ?? '')) : '';
            $header_cta_target = is_array($header_cta) ? (string) ($header_cta['target'] ?? '') : '';
            $header_cta_page_id = 0;

            if ($header_cta_label !== '' && function_exists('pll__')) {
                $header_cta_label = (string) pll__($header_cta_label);
            }

            if ($header_cta_url !== '' && !str_starts_with($header_cta_url, '#')) {
                $header_cta_url_without_fragment = strtok($header_cta_url, '#');
                $header_cta_page_id = $header_cta_url_without_fragment !== false
                    ? (int) url_to_postid($header_cta_url_without_fragment)
                    : 0;

                if ($header_cta_page_id > 0 && function_exists('pll_get_post')) {
                    $translated_cta_page_id = (int) pll_get_post($header_cta_page_id);
                    if ($translated_cta_page_id > 0) {
                        $header_cta_page_id = $translated_cta_page_id;
                        $translated_cta_url = get_permalink($translated_cta_page_id);
                        $header_cta_fragment = wp_parse_url($header_cta_url, PHP_URL_FRAGMENT);
                        if (is_string($translated_cta_url) && $translated_cta_url !== '') {
                            $header_cta_url = $translated_cta_url;
                            if (is_string($header_cta_fragment) && $header_cta_fragment !== '') {
                                $header_cta_url .= '#' . $header_cta_fragment;
                            }
                        }
                    }
                }
            }

            $show_header_cta = $header_cta_url !== '' && $header_cta_label !== '';
            $header_cta_is_active = $header_cta_page_id > 0
                && get_queried_object_id() === $header_cta_page_id;
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

            <?php if ($show_header_cta) : ?>
                <div class="site-header__cta">
                    <a
                        class="site-header__cta-link<?php echo $header_cta_is_active ? ' is-active' : ''; ?>"
                        href="<?php echo esc_url($header_cta_url); ?>"
                        target="<?php echo esc_attr($header_cta_target !== '' ? $header_cta_target : '_self'); ?>"
                        <?php echo $header_cta_target === '_blank' ? 'rel="noopener noreferrer"' : ''; ?>
                        <?php echo $header_cta_is_active ? 'aria-current="page"' : ''; ?>
                    >
                        <span class="site-header__cta-icon" aria-hidden="true"><?php echo dgut_ui_icon('sparkles'); ?></span>
                        <span class="site-header__cta-label"><?php echo esc_html($header_cta_label); ?></span>
                    </a>
                </div>
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
                                $language_slug = (string) ($language['slug'] ?? '');
                                if (function_exists('dgut_is_afisha_archive') && dgut_is_afisha_archive() && function_exists('dgut_afisha_language_switch_url')) {
                                    $language_url = dgut_afisha_language_switch_url($language_slug);
                                }
                                if ($language_url === '') {
                                    continue;
                                }
                                $language_label = (string) ($language['name'] ?? '');
                                if ($language_label === '') {
                                    $language_label = strtoupper((string) ($language['slug'] ?? ''));
                                }
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
