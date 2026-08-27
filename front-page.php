<?php
get_header();

$fields = function_exists('get_fields') ? get_fields() : [];
if (!is_array($fields)) {
    $fields = [];
}

$home_h1 = '';
$home_page = get_queried_object();
if ($home_page instanceof WP_Post) {
    $yoast_title = (string) get_post_meta($home_page->ID, '_yoast_wpseo_title', true);
    if ($yoast_title !== '' && function_exists('wpseo_replace_vars')) {
        $home_h1 = (string) wpseo_replace_vars($yoast_title, $home_page);
    }
}
if ($home_h1 === '') {
    $home_h1 = wp_get_document_title();
}
$home_h1 = trim(html_entity_decode(wp_strip_all_tags($home_h1), ENT_QUOTES | ENT_HTML5, get_bloginfo('charset') ?: 'UTF-8'));
if ($home_h1 === '') {
    $home_h1 = __('Театр ДГУ - театр і культурна платформа Дніпра', 'dgutheater');
}

$performance_posts = function_exists('dgut_ordered_translated_posts')
    ? dgut_ordered_translated_posts('performance', -1)
    : dgut_front_posts('performance', -1);
$performances = [];
foreach ($performance_posts as $performance_post) {
    $performances[] = dgut_get_performance_card_data($performance_post);
}

$hero_slides = dgut_front_hero_slides(dgut_front_field($fields, 'home_hero_slides', []));

$news_limit = max(1, (int) dgut_front_field($fields, 'home_news_count', 100));
$news_posts = function_exists('dgut_ordered_translated_posts')
    ? dgut_ordered_translated_posts('post', $news_limit)
    : dgut_front_posts('post', $news_limit);
$news_items = [];
foreach ($news_posts as $news_post) {
    $news_items[] = [
        'title' => get_the_title($news_post),
        'category' => get_the_category($news_post->ID)[0]->name ?? '',
        'date' => get_the_date('d.m.Y', $news_post),
        'excerpt' => get_the_excerpt($news_post),
        'image' => get_the_post_thumbnail_url($news_post, 'dgut-news-grid-card') ?: '',
        'url' => get_permalink($news_post),
    ];
}

$about_eyebrow = (string) dgut_front_field($fields, 'home_about_eyebrow');
$about_title = (string) dgut_front_field($fields, 'home_about_title');
$about_text_1 = (string) dgut_front_field($fields, 'home_about_text_1');
$about_text_2 = (string) dgut_front_field($fields, 'home_about_text_2');
$about_stats = [
    [
        'value' => (string) dgut_front_field($fields, 'home_about_stat_1_value'),
        'label' => (string) dgut_front_field($fields, 'home_about_stat_1_label'),
    ],
    [
        'value' => (string) dgut_front_field($fields, 'home_about_stat_2_value'),
        'label' => (string) dgut_front_field($fields, 'home_about_stat_2_label'),
    ],
    [
        'value' => (string) dgut_front_field($fields, 'home_about_stat_3_value'),
        'label' => (string) dgut_front_field($fields, 'home_about_stat_3_label'),
    ],
];
$about_stats = array_values(array_filter($about_stats, fn(array $stat): bool => $stat['value'] !== '' || $stat['label'] !== ''));
$about_images = array_values(array_filter(array_map(
    fn(mixed $image): string => dgut_front_image($image),
    (array) dgut_front_field($fields, 'home_about_images', [])
)));
$has_about = $about_eyebrow !== '' || $about_title !== '' || $about_text_1 !== '' || $about_text_2 !== '' || !empty($about_stats) || !empty($about_images);

$team_limit = max(1, (int) dgut_front_field($fields, 'home_team_count', 14));
$team_posts = function_exists('dgut_ordered_translated_posts')
    ? dgut_ordered_translated_posts('team_member', $team_limit)
    : dgut_front_posts('team_member', $team_limit);
$team = [];
foreach ($team_posts as $team_post) {
    $team[] = dgut_get_team_member_card_data($team_post);
}
?>
<main id="primary" class="site-main">
    <h1 class="screen-reader-text"><?php echo esc_html($home_h1); ?></h1>

    <?php
    get_template_part('template-parts/front-page/hero', null, [
        'fields' => $fields,
        'hero_slides' => $hero_slides,
    ]);

    get_template_part('template-parts/front-page/repertoire', null, [
        'fields' => $fields,
        'performances' => $performances,
    ]);

    get_template_part('template-parts/front-page/news', null, [
        'fields' => $fields,
        'news_items' => $news_items,
    ]);

    get_template_part('template-parts/front-page/about', null, [
        'fields' => $fields,
        'about_eyebrow' => $about_eyebrow,
        'about_title' => $about_title,
        'about_text_1' => $about_text_1,
        'about_text_2' => $about_text_2,
        'about_stats' => $about_stats,
        'about_images' => $about_images,
        'has_about' => $has_about,
    ]);

    get_template_part('template-parts/front-page/team', null, [
        'fields' => $fields,
        'team' => $team,
    ]);
    ?>
</main>
<?php
get_footer();
