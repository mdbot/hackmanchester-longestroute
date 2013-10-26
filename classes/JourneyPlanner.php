<?php

class JourneyPlanner
{
	/** @var RailStations */
	private $_railStations;

	public function __construct($railStations) {
		$this->_railStations = $railStations;
	}

	/**
	 * @param $origin string CRS code of origin
	 * @param $destination string CRS code of destination
	 *
	 * @throws UnrecognisedCodeException when origin/destination unrecognised
	 * @return Journey
	 */
	public function plan($origin, $destination) {
		$origin = $this->_railStations->getByCrs($origin);
		$destination = $this->_railStations->getByCrs($destination);

		$journey = array($origin);

		$firstHopStations = $this->_railStations->getStationsDirectlyReachableFrom($origin);
		if ($this->isDestinationInArray($destination, $firstHopStations)) {
			$journey[] = $destination;
		} else {
			$options = array();
			foreach ($firstHopStations as $station) {
				$options[] = array(
					'cost' => $origin->getDistanceTo($station),
					'routeHere' => array($station),
					'station' => $station,
					'children' => $this->_railStations->getStationsDirectlyReachableFrom($station)
				);
			}

			while (!($bestOption = $this->desinationInOptions($destination, $options))) {
				$options = $this->expand($options);
			}
			$journey = array_merge($journey, $bestOption, array($destination));
		}

		return new Journey($journey);
	}

	/**
	 * @param $destination RailStation
	 * @param $stations RailStation[]
	 *
	 * @return bool
	 */
	private function isDestinationInArray($destination, $stations) {
		foreach ($stations as $station) {
			if ($station->getTiploc() == $destination->getTiploc()) {
				return true;
			}
		}
		return false;
	}

	private function expand($options) {
		$newOptions = array();
		foreach ($options as $option) {
			$newOptions = array_merge($newOptions, $this->expandOption($option));
		}
		return $newOptions;
 	}

	private function expandOption($option) {
		$newOptions = array();
		foreach ($option['children'] as $station) {
			$newOptions[] = array(
				'cost' => $option['cost'] + $option['station']->getDistanceTo($station),
				'expanded' => false,
				'routeHere' => array_merge($option['routeHere'], array($station)),
				'children' => $this->_railStations->getStationsDirectlyReachableFrom($station)
			);
		}
		return $newOptions;
	}

	private function desinationInOptions($destination, $options) {
		$currentLowestCost = INF;
		$bestOption = false;
		foreach ($options as $option) {
			if ($this->isDestinationInArray($destination, $option['children'])) {
				if ($option['cost'] < $currentLowestCost) {
					$bestOption = $option['routeHere'];
					$currentLowestCost = $option['cost'];
				}
			}
		}
		return $bestOption;
	}
}