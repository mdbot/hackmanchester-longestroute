<?php
	/** Database details. */
	$dbconfig = array('type' => 'mysql',
	                  'username' => 'root',
	                  'password' => '',
	                  'host' => '127.0.0.1',
	                  'database' => 'longestroute',
	);
	
	if (file_exists(dirname(__FILE__) . '/config.local.php')) {
		require_once(dirname(__FILE__) . '/config.local.php');
	}
?>
