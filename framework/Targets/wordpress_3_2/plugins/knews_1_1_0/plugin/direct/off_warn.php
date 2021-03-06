<?php
ob_start();
if (!function_exists('add_action')) {
	$path='./';
	for ($x=1; $x<6; $x++) {
		$path .= '../';
		if (@file_exists($path . 'wp-config.php')) {
		    require_once($path . "wp-config.php");
			break;
		}
	}
}
ob_end_clean();

if ($Knews_plugin) {

	$Knews_plugin->security_for_direct_pages();

	if (! $Knews_plugin->initialized) $Knews_plugin->init();

	$warn=$Knews_plugin->get_safe('w');
	
	if ($warn == 'no_warn_cron_knews' || $warn == 'no_warn_ml_knews' || $warn == 'config_knews') {
		$knewsOptions[$warn]='yes';
		update_option($Knews_plugin->adminOptionsName, $knewsOptions);
	}

	wp_redirect( urldecode($Knews_plugin->get_safe('b')));
	exit;
}
?>