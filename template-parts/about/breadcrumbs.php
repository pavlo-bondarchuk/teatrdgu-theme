<?php
$breadcrumbs = dgut_yoast_breadcrumbs('dgut-breadcrumbs dgut-about-breadcrumbs');

if ($breadcrumbs === '') {
    return;
}
?>
<div class="dgut-about-breadcrumbs-wrap">
    <?php echo $breadcrumbs; ?>
</div>
