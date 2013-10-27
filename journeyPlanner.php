<?php
	require('config.php');
	require('classes/Database.php');
	require('classes/Journey.php');
	require('classes/JourneyPlanner.php');
	require('classes/RailStations.php');
	require('classes/RailStation.php');
	require('classes/UnrecognisedCodeException.php');
	require('vendors/phpcoord-2.3.php');

	$railStations = new RailStations(new Database($dbconfig));

	$journeyPlanner = new JourneyPlanner($railStations);
	$journey = $journeyPlanner->plan($_GET['from'], $_GET['to']);
?>
<html>
<head>
	<title>Journey Planner</title>
</head>
<body>
	<h1>
		From: <?= $journey->getOrigin(); ?>
		To: <?= $journey->getDestination(); ?>
	</h1>
	<h2>Most Direct Route (<?= round($journey->getDistance()); ?> km)</h2>
	<ol>
		<? foreach ($journey->getStops() as $stop) : ?>
			<li><?= $stop->getName(); ?></li>
		<? endforeach; ?>
	</ol>
</body>
</html>