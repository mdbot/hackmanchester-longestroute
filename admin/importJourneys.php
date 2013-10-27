<?php
	require_once(dirname(__FILE__) . '/../classes/ParseJourneys.php');
	require_once(dirname(__FILE__) . '/../config.php');

	if (php_sapi_name() != 'cli') { echo '<pre>'; }

	if (!file_exists(dirname(__FILE__) . '/data/schedule.gz')) {
		die('This needs a traindata file to work.');
	}

	$database = new Database($dbconfig);
	$database->clearJourneys();
	$stations = new ParseJourneys(dirname(__FILE__) . '/data/schedule.gz', $database);
	$stations->parse();
