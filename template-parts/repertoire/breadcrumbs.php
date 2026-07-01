<?php
$breadcrumbs = dgut_yoast_breadcrumbs('dgut-breadcrumbs dgut-repertoire-breadcrumbs');

if ($breadcrumbs === '') {
    return;
}
?>
<div class="dgut-repertoire-breadcrumbs-wrap">
    <?php echo $breadcrumbs; ?>
</div>
