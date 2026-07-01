<?php
/**
 * Template Name: Репертуар
 */

get_header();

$performances = array_values(array_filter(array_map('dgut_repertoire_card_data', dgut_repertoire_posts())));
$filters = dgut_repertoire_filters();
?>
<main id="primary" class="site-main dgut-repertoire-page">
    <?php
    get_template_part('template-parts/repertoire/breadcrumbs');

    get_template_part('template-parts/repertoire/grid', null, [
        'performances' => $performances,
        'filters' => $filters,
    ]);
    ?>
</main>
<?php
get_footer();
