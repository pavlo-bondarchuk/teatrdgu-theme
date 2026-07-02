<?php
if (!defined('ABSPATH')) {
    exit;
}

add_filter('wpseo_title', 'dgut_seo_title');
add_filter('wpseo_metadesc', 'dgut_seo_description');
add_filter('wpseo_opengraph_title', 'dgut_seo_open_graph_title');
add_filter('wpseo_opengraph_desc', 'dgut_seo_open_graph_description');
add_filter('wpseo_twitter_title', 'dgut_seo_twitter_title');
add_filter('wpseo_twitter_description', 'dgut_seo_twitter_description');

function dgut_seo_title(string $title): string
{
    $data = dgut_seo_context();
    if (empty($data['title']) || dgut_seo_has_custom_meta('_yoast_wpseo_title')) {
        return $title;
    }

    return $data['title'];
}

function dgut_seo_description(string $description): string
{
    $data = dgut_seo_context();
    if (empty($data['description']) || dgut_seo_has_custom_meta('_yoast_wpseo_metadesc')) {
        return $description;
    }

    return $data['description'];
}

function dgut_seo_open_graph_title(string $title): string
{
    $data = dgut_seo_context();
    if (empty($data['og_title']) || dgut_seo_has_custom_meta('_yoast_wpseo_opengraph-title')) {
        return $title;
    }

    return $data['og_title'];
}

function dgut_seo_open_graph_description(string $description): string
{
    $data = dgut_seo_context();
    if (empty($data['og_description']) || dgut_seo_has_custom_meta('_yoast_wpseo_opengraph-description')) {
        return $description;
    }

    return $data['og_description'];
}

function dgut_seo_twitter_title(string $title): string
{
    $data = dgut_seo_context();
    if (empty($data['title']) || dgut_seo_has_custom_meta('_yoast_wpseo_twitter-title')) {
        return $title;
    }

    return $data['title'];
}

function dgut_seo_twitter_description(string $description): string
{
    $data = dgut_seo_context();
    if (empty($data['description']) || dgut_seo_has_custom_meta('_yoast_wpseo_twitter-description')) {
        return $description;
    }

    return $data['description'];
}

function dgut_seo_has_custom_meta(string $key): bool
{
    if (!is_singular()) {
        return false;
    }

    return trim((string) get_post_meta(get_queried_object_id(), $key, true)) !== '';
}

function dgut_seo_context(): array
{
    if (is_front_page()) {
        return [
            'title' => 'Театр ДГУ — репертуар, квитки та новини | Дніпро',
            'description' => 'Офіційний сайт Театру ДГУ в Дніпрі: актуальний репертуар, афіша вистав, квитки онлайн, новини культурної платформи та контакти.',
            'og_title' => 'Театр ДГУ — театр і культурна платформа Дніпра',
            'og_description' => 'Репертуар, афіша вистав, квитки онлайн, новини та культурні проєкти Театру ДГУ в Дніпрі.',
        ];
    }

    if (is_post_type_archive('performance') || is_page_template('template-repertoire.php')) {
        return [
            'title' => 'Репертуар Театру ДГУ — вистави та афіша | Дніпро',
            'description' => 'Актуальний репертуар Театру ДГУ: вистави для дорослих і дітей, жанри, описи, дати та перехід до квитків онлайн.',
            'og_title' => 'Репертуар Театру ДГУ',
            'og_description' => 'Афіша вистав Театру ДГУ в Дніпрі з описами, жанрами та переходом до квитків.',
        ];
    }

    if (is_home()) {
        return [
            'title' => 'Новини Театру ДГУ — культурна платформа Дніпра',
            'description' => 'Новини Театру ДГУ: прем’єри, нагороди, гастролі, культурні події та проєкти театральної платформи у Дніпрі.',
            'og_title' => 'Новини Театру ДГУ',
            'og_description' => 'Прем’єри, нагороди, гастролі та культурні події Театру ДГУ в Дніпрі.',
        ];
    }

    if (is_page_template('template-tickets.php')) {
        return [
            'title' => 'Квитки на вистави Театру ДГУ | Дніпро',
            'description' => 'Купуйте квитки на вистави Театру ДГУ через офіційні онлайн-сервіси або перегляньте актуальний репертуар театру.',
            'og_title' => 'Квитки на вистави Театру ДГУ',
            'og_description' => 'Офіційні онлайн-сервіси для купівлі квитків на вистави Театру ДГУ.',
        ];
    }

    if (is_page_template('template-about.php')) {
        return [
            'title' => 'Про Театр ДГУ — театр і культурна платформа Дніпра',
            'description' => 'Дізнайтеся про Театр ДГУ, команду, вистави, культурні проєкти та роботу театру для розвитку мистецького життя Дніпра.',
            'og_title' => 'Про Театр ДГУ',
            'og_description' => 'Театр ДГУ як сцена, команда і культурна платформа Дніпра.',
        ];
    }

    if (is_page_template('template-contacts.php')) {
        return [
            'title' => 'Контакти Театру ДГУ — адреса, телефон, email | Дніпро',
            'description' => 'Контакти Театру ДГУ: адреса у Дніпрі, телефон, email, графік роботи, мапа та форма для звернень.',
            'og_title' => 'Контакти Театру ДГУ',
            'og_description' => 'Адреса, телефон, email, графік роботи, мапа та форма зв’язку Театру ДГУ.',
        ];
    }

    return [];
}
