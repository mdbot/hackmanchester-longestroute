<?php
	require('../classes/CsvRailStation.php');
	require('../classes/Database.php');
	require('../classes/RailStations.php');

	$railStations = new RailStations(new Database());
	$railStations->initStationsStorage();
?>
<html>
<head>
	<title>Importing rail stations</title>
</head>
<body>
<table>
	<thead>
	<tr>
		<th>Name</th>
		<th>TIPLOC</th>
		<th>Latitude</th>
		<th>Longitude</th>
	</tr>
	</thead>
	<tbody>
		<? foreach (CsvRailStation::loadFromCsv() as $railStation) : ?>
			<? $railStations->storeStation($railStation); ?>
			<tr>
				<td><?= $railStation->getName(); ?> (<?= $railStation->getCrs(); ?>)</td>
				<td><?= $railStation->getTiploc(); ?></td>
				<td><?= $railStation->getLatitude(); ?></td>
				<td><?= $railStation->getLongitude(); ?></td>
			</tr>
		<? endforeach; ?>
	</tbody>
</table>
</body>
</html>
