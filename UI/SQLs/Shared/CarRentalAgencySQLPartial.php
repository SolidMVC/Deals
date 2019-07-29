<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );

$arrPluginReplaceSQL = !isset($arrPluginReplaceSQL) ? array() : $arrPluginReplaceSQL;

// NOTE: 'deal_id' does not matter here and can be set automatically
$arrPluginReplaceSQL['deals'] = "(`deal_title`, `deal_image`, `demo_deal_image`, `target_url`, `deal_description`, `deal_enabled`, `deal_order`, `blog_id`) VALUES
('Pro Team', 'deal_pro-team.jpg', 1, '[SITE_URL]/about-us/', 'Our team has over 20 years of experience in car rental business', 1, 1, [BLOG_ID]),
('Classy Cars', 'deal_classy-cars.jpg', 1, '[SITE_URL]/cars/', 'With Native Rental, you can always be sure, that you can ride in style', 1, 2, [BLOG_ID]),
('-50%', 'deal_50-percent-off.jpg', 1, '[SITE_URL]/prices/', 'If you will rent a car for a longer than 30 days period, you can expect to get up to 50% off the original price', 1, 3, [BLOG_ID]),
('New Cars', 'deal_new-cars.jpg', 1, '[SITE_URL]/cars/', 'All our cars, except classic cars, are not older than 5 years', 1, 4, [BLOG_ID]),
('Convertibles', 'deal_convertibles.jpg', 1, '[SITE_URL]/cars/', '', 1, 5, [BLOG_ID]);";

// Note: 'settings' table is different for each demo version, so it is not listed here