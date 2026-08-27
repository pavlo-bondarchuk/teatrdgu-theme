<?php
$breadcrumbs = dgut_yoast_breadcrumbs('dgut-breadcrumbs dgut-repertoire-breadcrumbs');

if ($breadcrumbs === '') {
    return;
}
?>
<div class="container dgut-breadcrumbs-wrap dgut-repertoire-breadcrumbs-wrap">
    <?php echo $breadcrumbs; ?>
</div>
