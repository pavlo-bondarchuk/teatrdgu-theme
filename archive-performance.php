<?php

/**
 * Archive Репертуар
 */

get_header();

$repertoire_posts = function_exists('dgut_repertoire_posts') ? dgut_repertoire_posts() : [];
$performances = array_values(array_filter(array_map('dgut_repertoire_card_data', $repertoire_posts)));
$filters = function_exists('dgut_repertoire_filters') ? dgut_repertoire_filters() : [];
$archive_title = post_type_archive_title('', false);

if ($archive_title === '') {
    $post_type_object = get_post_type_object('performance');
    $title = $post_type_object && !empty($post_type_object->labels->name)
        ? $post_type_object->labels->name
        : __('Репертуар', 'dgutheater');
}
?>

<main id="primary" class="site-main dgut-repertoire-page">
    <?php
    get_template_part('template-parts/repertoire/breadcrumbs');

    get_template_part('template-parts/repertoire/grid', null, [
        'performances' => $performances,
        'filters' => $filters,
        'title' => $title,
    ]);
    ?>
</main>

<?php
get_footer();
