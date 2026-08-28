<?php

/**
 * DGU Theater theme bootstrap.
 */

if (!defined('ABSPATH')) {
    exit;
}

define('DGUTHEME_VERSION', '0.3.1');
define('DGUTHEME_DIR', get_template_directory());
define('DGUTHEME_URI', get_template_directory_uri());

require_once DGUTHEME_DIR . '/inc/helpers.php';
require_once DGUTHEME_DIR . '/inc/schema.php';
require_once DGUTHEME_DIR . '/inc/gtm.php';
require_once DGUTHEME_DIR . '/inc/setup.php';
require_once DGUTHEME_DIR . '/inc/assets.php';
require_once DGUTHEME_DIR . '/inc/post-types.php';
require_once DGUTHEME_DIR . '/inc/afisha.php';
require_once DGUTHEME_DIR . '/inc/scf.php';
require_once DGUTHEME_DIR . '/inc/llms.php';
require_once DGUTHEME_DIR . '/inc/maintenance.php';
