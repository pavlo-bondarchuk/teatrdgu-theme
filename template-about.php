<?php
/**
 * Template Name: Про нас
 */

get_header();

$fields = dgut_page_fields();
$defaults = dgut_about_defaults();
?>
<main id="primary" class="site-main dgut-about-page">
    <?php
    get_template_part('template-parts/about/breadcrumbs');

    get_template_part('template-parts/about/hero', null, [
        'fields' => $fields,
        'defaults' => $defaults,
    ]);

    get_template_part('template-parts/about/work', null, [
        'fields' => $fields,
        'defaults' => $defaults,
    ]);

    get_template_part('template-parts/about/initiatives', null, [
        'fields' => $fields,
        'defaults' => $defaults,
    ]);
    ?>
</main>
<?php
get_footer();
