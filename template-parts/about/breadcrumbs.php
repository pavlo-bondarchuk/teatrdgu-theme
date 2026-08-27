<?php
$breadcrumbs = dgut_yoast_breadcrumbs('dgut-breadcrumbs dgut-about-breadcrumbs');

if ($breadcrumbs === '') {
    return;
}
?>
<div class="container dgut-breadcrumbs-wrap dgut-about-breadcrumbs-wrap">
    <?php echo $breadcrumbs; ?>
</div>
