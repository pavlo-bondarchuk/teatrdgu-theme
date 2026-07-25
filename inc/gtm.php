<?php

/**
 * Google Tag Manager integration.
 */

if (!defined('ABSPATH')) {
    exit;
}

const DGUT_GTM_ID = 'GTM-M23WR4PQ';
const DGUT_GTAG_ID = 'G-GPRKDTEMN0';

add_action('wp_head', 'dgut_render_gtm_head', 1);
add_action('wp_body_open', 'dgut_render_gtm_body', 1);

function dgut_render_gtm_head(): void
{
    ?>
    <!-- Google Tag Manager -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
            window.dataLayer.push(arguments);
        }

        window.dataLayer.push({
            'gtm.start': new Date().getTime(),
            event: 'gtm.js'
        });
        gtag('js', new Date());
        gtag('config', '<?php echo esc_js(DGUT_GTAG_ID); ?>');

        (function(w, d, l, gtmId, gtagId) {
            var loaded = false;

            function loadTags() {
                if (loaded) {
                    return;
                }
                loaded = true;

                var firstScript = d.getElementsByTagName('script')[0];
                var gtmScript = d.createElement('script');
                var gtagScript = d.createElement('script');

                gtmScript.async = true;
                gtmScript.src = 'https://www.googletagmanager.com/gtm.js?id=' + gtmId + (l !== 'dataLayer' ? '&l=' + l : '');
                firstScript.parentNode.insertBefore(gtmScript, firstScript);

                gtagScript.async = true;
                gtagScript.src = 'https://www.googletagmanager.com/gtag/js?id=' + gtagId;
                firstScript.parentNode.insertBefore(gtagScript, firstScript);
            }

            if ('requestIdleCallback' in w) {
                w.requestIdleCallback(loadTags, {
                    timeout: 2500
                });
            } else {
                w.addEventListener('load', loadTags, {
                    once: true
                });
            }
        })(window, document, 'dataLayer', '<?php echo esc_js(DGUT_GTM_ID); ?>', '<?php echo esc_js(DGUT_GTAG_ID); ?>');
    </script>
    <!-- End Google Tag Manager -->
    <?php
}

function dgut_render_gtm_body(): void
{
    ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr(DGUT_GTM_ID); ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
}
