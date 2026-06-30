<?php
/**
 * Template Name: Development placeholder
 * Template Post Type: page
 */

if (!defined('ABSPATH')) {
    exit;
}

$title = get_the_title() ?: __('Офіційний сайт Театру ДГУ незабаром відкриється', 'dgutheater');
$content = trim((string) get_post_field('post_content', get_the_ID()));
$email = dgut_option('dgut_footer_email', 'kvn.dgu@dhp.dniprorada.gov.ua');
$facebook = dgut_option('dgut_coming_soon_facebook_url', 'https://www.facebook.com/teatrdgu/');
$poster = DGUTHEME_URI . '/assets/coming-soon/background.png';
$video = DGUTHEME_URI . '/assets/coming-soon/background.mp4';
$logo = DGUTHEME_URI . '/assets/coming-soon/logo.png';

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <?php wp_head(); ?>
</head>
<body <?php body_class('coming-soon-page'); ?> style="--coming-soon-poster:url('<?php echo esc_url($poster); ?>')">
<?php wp_body_open(); ?>
<main class="coming-soon">
    <video class="coming-soon__video" aria-hidden="true" autoplay muted loop playsinline preload="metadata" poster="<?php echo esc_url($poster); ?>" tabindex="-1">
        <source src="<?php echo esc_url($video); ?>" type="video/mp4">
    </video>
    <div class="coming-soon__overlay" aria-hidden="true"></div>
    <div class="coming-soon__container">
        <section class="coming-soon__content" aria-labelledby="coming-soon-title">
            <div class="coming-soon__logo-wrap">
                <img class="coming-soon__logo" src="<?php echo esc_url($logo); ?>" width="250" height="70" alt="<?php bloginfo('name'); ?>" decoding="async" fetchpriority="high">
            </div>
            <p class="coming-soon__eyebrow"><?php echo esc_html(dgut_option('dgut_coming_soon_eyebrow', __('Культурна платформа', 'dgutheater'))); ?></p>
            <h1 class="coming-soon__title" id="coming-soon-title">
                <span class="coming-soon__title-line coming-soon__title-line--primary"><?php echo esc_html(dgut_option('dgut_coming_soon_title_primary', __('Офіційний сайт Театру ДГУ', 'dgutheater'))); ?></span>
                <span class="coming-soon__title-line coming-soon__title-line--secondary"><?php echo esc_html(dgut_option('dgut_coming_soon_title_secondary', __('незабаром відкриється', 'dgutheater'))); ?></span>
            </h1>
            <div class="coming-soon__description">
                <?php
                if ($content !== '') {
                    the_content();
                } else {
                    echo wp_kses_post(wpautop(dgut_option('dgut_coming_soon_description', __('Ми працюємо над новим сайтом сучасної культурної платформи — простором для театру, діалогу та натхнення. Скоро тут з’являться репертуар, події та новини.', 'dgutheater'))));
                }
                ?>
            </div>
            <div class="coming-soon__actions">
                <a class="coming-soon__button coming-soon__button--mail" href="mailto:<?php echo esc_attr($email); ?>">
                    <svg class="coming-soon__button-icon" aria-hidden="true" viewBox="0 0 24 24" fill="none"><path d="M4 5h16v14H4zM4 7l8 6 8-6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    <span><?php esc_html_e('Написати нам', 'dgutheater'); ?></span>
                </a>
                <a class="coming-soon__button coming-soon__button--facebook" href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer">
                    <svg class="coming-soon__button-icon" aria-hidden="true" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-8h2.7l.4-3h-3.1V8.1c0-.9.2-1.5 1.6-1.5h1.7V4a23 23 0 0 0-2.5-.1c-2.5 0-4.2 1.5-4.2 4.3V10H7.3v3h2.8v8h3.4Z"/></svg>
                    <span>Facebook</span>
                </a>
            </div>
            <p class="coming-soon__location"><?php echo esc_html(dgut_option('dgut_coming_soon_location', __('м. Дніпро', 'dgutheater'))); ?></p>
        </section>
    </div>
</main>
<?php wp_footer(); ?>
</body>
</html>

