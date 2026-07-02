<?php
if (!defined('ABSPATH')) {
    exit;
}

add_filter('wpseo_schema_graph', function (array $graph): array {
    $organization = dgut_schema_organization();
    $organization_id = $organization['@id'];

    $graph = dgut_schema_upsert_node($graph, $organization);

    foreach ($graph as &$piece) {
        if (!is_array($piece) || empty($piece['@type'])) {
            continue;
        }

        $types = (array) $piece['@type'];
        if (in_array('WebSite', $types, true)) {
            $piece['publisher'] = ['@id' => $organization_id];
        }

        if (in_array('WebPage', $types, true)) {
            $piece['publisher'] = ['@id' => $organization_id];
            $piece['about'] = ['@id' => $organization_id];

            if (is_front_page()) {
                $piece['@type'] = array_values(array_unique(array_merge($types, ['CollectionPage'])));
            } elseif (is_page_template('template-contacts.php')) {
                $piece['@type'] = array_values(array_unique(array_merge($types, ['ContactPage'])));
            }
        }
    }
    unset($piece);

    if (is_singular('performance')) {
        $event = dgut_schema_performance_event(get_queried_object_id(), $organization_id);
        if (!empty($event)) {
            $graph = dgut_schema_upsert_node($graph, $event);
            $graph = dgut_schema_set_webpage_primary_entity($graph, $event['@id']);
        }
    }

    if (is_singular('post')) {
        $article = dgut_schema_news_article(get_queried_object_id(), $organization_id);
        if (!empty($article)) {
            $graph = dgut_schema_upsert_node($graph, $article);
            $graph = dgut_schema_set_webpage_primary_entity($graph, $article['@id']);
        }
    }

    return $graph;
});

add_filter('pll_rel_hreflang_attributes', function (array $hreflangs): array {
    if (!isset($hreflangs['ua'])) {
        return $hreflangs;
    }

    $hreflangs['uk'] = $hreflangs['ua'];
    unset($hreflangs['ua']);

    return $hreflangs;
});

add_filter('language_attributes', function (string $output): string {
    return preg_replace('/\blang=(["\'])ua\1/', 'lang=$1uk$1', $output) ?? $output;
}, 20);

function dgut_schema_upsert_node(array $graph, array $node): array
{
    if (empty($node['@id'])) {
        $graph[] = $node;
        return $graph;
    }

    foreach ($graph as $index => $piece) {
        if (is_array($piece) && ($piece['@id'] ?? '') === $node['@id']) {
            $graph[$index] = array_merge($piece, $node);
            return $graph;
        }
    }

    $graph[] = $node;
    return $graph;
}

function dgut_schema_set_webpage_primary_entity(array $graph, string $entity_id): array
{
    foreach ($graph as &$piece) {
        if (is_array($piece) && in_array('WebPage', (array) ($piece['@type'] ?? []), true)) {
            $piece['primaryEntity'] = ['@id' => $entity_id];
        }
    }
    unset($piece);

    return $graph;
}

function dgut_schema_organization(): array
{
    $home_url = home_url('/');
    $logo_url = DGUTHEME_URI . '/assets/img/logo-dark.svg';
    $phone = (string) dgut_option('dgut_footer_phone', '+38 (067) 560-63-20');
    $email = (string) dgut_option('dgut_footer_email', 'kvn.dgu@dhp.dniprorada.gov.ua');
    $address = (string) dgut_option('dgut_footer_address', "Троїцька площа, 5А\nДніпро, 49100");
    $map_url = (string) dgut_option('dgut_footer_map_url', 'https://www.google.com/maps/search/?api=1&query=Троїцька+площа+5А,+Дніпро');
    $hours = dgut_schema_opening_hours((string) dgut_option('dgut_footer_hours', 'Пн-Пт: 10:30-19:30'));

    $node = [
        '@type' => ['Organization', 'PerformingArtsTheater'],
        '@id' => $home_url . '#organization',
        'name' => get_bloginfo('name') ?: 'Театр ДГУ',
        'alternateName' => 'Театр ДГУ',
        'url' => $home_url,
        'logo' => [
            '@type' => 'ImageObject',
            '@id' => $home_url . '#logo',
            'url' => $logo_url,
            'contentUrl' => $logo_url,
            'width' => 106,
            'height' => 62,
        ],
        'image' => ['@id' => $home_url . '#logo'],
        'description' => get_bloginfo('description') ?: 'Сучасна культурна платформа Дніпра',
        'telephone' => $phone,
        'email' => $email,
        'address' => dgut_schema_postal_address($address),
        'hasMap' => $map_url,
        'sameAs' => dgut_schema_same_as(),
        'contactPoint' => [
            [
                '@type' => 'ContactPoint',
                'telephone' => $phone,
                'email' => $email,
                'contactType' => 'customer support',
                'areaServed' => 'UA',
                'availableLanguage' => ['uk', 'en'],
            ],
        ],
    ];

    if (!empty($hours)) {
        $node['openingHours'] = [$hours];
    }

    return $node;
}

function dgut_schema_postal_address(string $raw_address): array
{
    $address = preg_split('/\R+/', trim($raw_address)) ?: [];
    $address = array_values(array_filter(array_map('trim', $address)));
    $street = $address[0] ?? 'Троїцька площа, 5А';
    $locality_line = $address[1] ?? 'Дніпро, 49100';
    $postal_code = '';
    if (preg_match('/\b(\d{5})\b/', $locality_line, $matches)) {
        $postal_code = $matches[1];
    }

    return array_filter([
        '@type' => 'PostalAddress',
        'streetAddress' => $street,
        'addressLocality' => 'Дніпро',
        'postalCode' => $postal_code,
        'addressCountry' => 'UA',
    ]);
}

function dgut_schema_opening_hours(string $hours): string
{
    if (preg_match('/Пн\s*-\s*Пт\s*:\s*(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})/u', $hours, $matches)) {
        return 'Mo-Fr ' . $matches[1] . '-' . $matches[2];
    }

    return '';
}

function dgut_schema_same_as(): array
{
    $links = [];
    if (function_exists('get_field')) {
        $rows = get_field('socials', 'option');
        if (!is_array($rows)) {
            $rows = get_field('socials', 'options');
        }
        if (is_array($rows)) {
            foreach ($rows as $row) {
                $url = is_array($row) ? trim((string) ($row['social_link'] ?? '')) : '';
                if ($url !== '') {
                    $links[] = esc_url_raw($url);
                }
            }
        }
    }

    if (empty($links)) {
        $links = array_filter(array_map(static fn(array $item): string => (string) $item['url'], dgut_social_links()));
    }

    return array_values(array_unique($links));
}

function dgut_schema_performance_event(int $post_id, string $organization_id): array
{
    $post = get_post($post_id);
    if (!$post) {
        return [];
    }

    $permalink = get_permalink($post);
    $image = get_the_post_thumbnail_url($post, 'full');
    $date_text = function_exists('get_field') ? (string) get_field('dgut_performance_date', $post_id) : '';
    $start_date = dgut_schema_parse_date($date_text);
    $services = dgut_schema_performance_services($post_id);

    $event = [
        '@type' => ['Event', 'TheaterEvent'],
        '@id' => $permalink . '#event',
        'name' => get_the_title($post),
        'description' => wp_strip_all_tags(get_the_excerpt($post) ?: wp_trim_words((string) get_post_field('post_content', $post_id), 35)),
        'url' => $permalink,
        'image' => $image ? [$image] : [],
        'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
        'eventStatus' => 'https://schema.org/EventScheduled',
        'location' => ['@id' => $organization_id],
        'organizer' => ['@id' => $organization_id],
        'performer' => ['@id' => $organization_id],
        'inLanguage' => get_bloginfo('language') ?: 'uk',
    ];

    if ($start_date !== '') {
        $event['startDate'] = $start_date;
    }

    if (!empty($services)) {
        $event['offers'] = array_values(array_map(static function (array $service): array {
            return array_filter([
                '@type' => 'Offer',
                'url' => esc_url_raw((string) ($service['url'] ?? '')),
                'name' => trim((string) ($service['name'] ?? 'Квитки')),
                'availability' => 'https://schema.org/InStock',
                'priceCurrency' => 'UAH',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => trim((string) ($service['name'] ?? 'Квитковий сервіс')),
                ],
            ]);
        }, $services));
    }

    return array_filter($event);
}

function dgut_schema_performance_services(int $post_id): array
{
    if (!function_exists('get_field')) {
        return [];
    }

    $rows = get_field('dgut_performance_ticket_services', $post_id);
    if (!is_array($rows)) {
        return [];
    }

    $services = [];
    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }

        $url = trim((string) ($row['url'] ?? ''));
        if ($url === '') {
            continue;
        }

        $services[] = [
            'name' => trim((string) ($row['name'] ?? 'Квитки')),
            'url' => $url,
        ];
    }

    return $services;
}

function dgut_schema_news_article(int $post_id, string $organization_id): array
{
    $post = get_post($post_id);
    if (!$post) {
        return [];
    }

    $permalink = get_permalink($post);
    $image = get_the_post_thumbnail_url($post, 'full');

    return array_filter([
        '@type' => 'NewsArticle',
        '@id' => $permalink . '#article',
        'headline' => get_the_title($post),
        'description' => wp_strip_all_tags(get_the_excerpt($post) ?: wp_trim_words((string) get_post_field('post_content', $post_id), 35)),
        'url' => $permalink,
        'image' => $image ? [$image] : [],
        'datePublished' => get_the_date(DATE_W3C, $post),
        'dateModified' => get_the_modified_date(DATE_W3C, $post),
        'author' => ['@id' => $organization_id],
        'publisher' => ['@id' => $organization_id],
        'mainEntityOfPage' => ['@id' => $permalink],
        'inLanguage' => get_bloginfo('language') ?: 'uk',
    ]);
}

function dgut_schema_parse_date(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    if (preg_match('/\b(\d{4})-(\d{2})-(\d{2})\b/', $value, $matches)) {
        return $matches[1] . '-' . $matches[2] . '-' . $matches[3];
    }

    if (preg_match('/\b(\d{1,2})\.(\d{1,2})\.(\d{4})\b/', $value, $matches)) {
        return sprintf('%04d-%02d-%02d', (int) $matches[3], (int) $matches[2], (int) $matches[1]);
    }

    return '';
}
