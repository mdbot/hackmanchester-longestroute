<?php
	require_once(dirname(__FILE__) . '/config.php');
	require_once(dirname(__FILE__) . '/classes/Database.php');
	require_once(dirname(__FILE__) . '/classes/Journey.php');
	require_once(dirname(__FILE__) . '/classes/JourneyPlanner.php');
	require_once(dirname(__FILE__) . '/classes/RailStations.php');
	require_once(dirname(__FILE__) . '/classes/RailStation.php');
	require_once(dirname(__FILE__) . '/classes/UnrecognisedCodeException.php');
	require_once(dirname(__FILE__) . '/vendors/phpcoord-2.3.php');
	require_once(dirname(__FILE__) . '/vendors/clockwork-php/class-Clockwork.php');
	

	$fromNumber = isset($_REQUEST['from']) ? $_REQUEST['from'] : '';
	$toNumber = isset($_REQUEST['to']) ? $_REQUEST['to'] : '';
	$content = isset($_REQUEST['content']) ? strtoupper($_REQUEST['content']) : '';
	$messageID = isset($_REQUEST['msg_id']) ? $_REQUEST['msg_id'] : '';

	$message = 'There was an unknown error.';

	if (empty($fromNumber)) {
		// Can't return anything without a source message, oh well.
		die();
	} else if (empty($content)) {
		$message = 'To find a journey, please text "TRAIN" followed by 2 station codes - eg "TRAIN MAN EUS" for Manchester Piccadilly to London Euston';
	} else if (preg_match('/^TRAIN ([A-Z]+) ([A-Z]+)/i', $content, $matches)) {
		$fromStation = $matches[1];
		$toStation = $matches[2];

		$railStations = new RailStations(new Database($dbconfig));

		try {
			$journeyPlanner = new JourneyPlanner($railStations);
			$journey = $journeyPlanner->plan($fromStation, $toStation);

			$stops = array();
			foreach ($journey->getStops() as $stop){
				$stops[] = $stop->getName();
			}

			$message = 'Found a ' . round($journey->getDistance()) . ' km journey from ' . $fromStation . ' to ' . $toStation . ': ' . implode(' -> ', $stops);
		} catch (UnrecognisedCodeException $e) {
			$message = 'Unrecognised station code: ' . $e->getStationCode();
		}
	} else if (!preg_match('/^TRAIN/i', $content)) {
		$message = 'To find a journey, please text "TRAIN" followed by 2 station codes - eg "TRAIN MAN EUS" for Manchester Piccadilly to London Euston';
	}
	
	$clockwork = new Clockwork($clockworkconfig['apikey']);
	$result = $clockwork->send(array('to' => $fromNumber, 'message' => $message, 'from' => $clockworkconfig['from']));
	
	var_dump($result);
?>
