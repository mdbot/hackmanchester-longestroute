<?php
	require_once(dirname(__FILE__) . '/../classes/ParseJourneys.php');

	if (php_sapi_name() != 'cli') { die('This script takes about 20 minutes to run, please run from CLI.'); }

	if (!file_exists(dirname(__FILE__) . '/data/schedule.gz')) {
		die('This needs a traindata file to work.');
	}

	$database = new Database();
	$database->clearJourneys();
	$stations = new ParseJourneys(dirname(__FILE__) . '/data/schedule.gz', $database);
	$stations->parse();
