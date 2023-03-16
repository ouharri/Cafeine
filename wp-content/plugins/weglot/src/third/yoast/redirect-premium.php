<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once $dir_yoast_premium_inside . 'classes/redirect/redirect-handler.php';
require_once $dir_yoast_premium_inside . 'classes/redirect/redirect-util.php';
require_once __DIR__ . '/class-redirect-handler-weglot.php';

$redirect_handler = new Redirect_Handler_Weglot();
$redirect_handler->load();
