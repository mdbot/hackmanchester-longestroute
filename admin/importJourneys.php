<?php
	require_once(dirname(__FILE__) . '/../classes/ParseJourneys.php');

	if (php_sapi_name() != 'cli') { die('This script takes a long time to run, please run from CLI.'); }

	$database = new Database();
	$stations = new ParseJourneys(dirname(__FILE__) . '/traindata', $database);
	$stations->parse();
?>
