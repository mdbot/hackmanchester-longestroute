<?php
	/** Database details. */
	$dbconfig = array('type' => 'mysql',
	                  'username' => 'root',
	                  'password' => '',
	                  'host' => '127.0.0.1',
	                  'database' => 'longestroute',
	);
	
	if (stristr(gethostname(), 'shanetest') !== false) {
		$dbconfig['host'] = 'mariadb-shanetest.j.layershift.co.uk';
		$dbconfig['password'] = 'pedgfZR1tg';
	}
?>
