<?php

class JourneyPlanner
{
	/** @var RailStations */
	private $_railStations;
	private $_expandedNodes = array();

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
		$this->_expandedNodes = array();
		$origin = $this->_railStations->getByCrs($origin);
		$destination = $this->_railStations->getByCrs($destination);

		$options = $this->expandOption(
			array(
				'cost' => 0,
				'station' => $origin,
				'routeHere' => array($origin)
			)
		);

		while (!($bestOption = $this->desinationInOptions($destination, $options, 0))) {
			$options = $this->expand($options);
		}

		if ($bestOption['cost'] > 600) {
			$scenicMultiplier = 1.25;
		} elseif ($bestOption['cost'] > 400) {
			$scenicMultiplier = 1.5;
		} elseif ($bestOption['cost'] > 200) {
			$scenicMultiplier = 1.75;
		} elseif ($bestOption['cost'] > 50) {
			$scenicMultiplier = 2;
		} elseif ($bestOption['cost'] > 20) {
			$scenicMultiplier = 3;
		} else {
			$scenicMultiplier = 5;
		}

		while (
			!(
				$scenicOption = $this->desinationInOptions(
					$destination, $options, $bestOption['cost'] * $scenicMultiplier
				)
			)
		) {
			$options = $this->expand($options);
		}

		return new Journey($bestOption['routeHere'], $scenicOption['routeHere']);
	}

	/**
	 * @param $needleStation RailStation
	 * @param $haystack RailStation[]
	 *
	 * @return bool
	 */
	private function isStationInArray($needleStation, $haystack) {
		foreach ($haystack as $station) {
			if ($station->getTiploc() == $needleStation->getTiploc()) {
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
		foreach ($this->_railStations->getStationsDirectlyReachableFrom($option['station']) as $station) {
			if (!$this->isStationInArray($station, $option['routeHere'])) { // prevent loops
				$newOptions[] = array(
					'cost' => $option['cost'] + $option['station']->getDistanceTo($station),
					'station' => $station,
					'routeHere' => array_merge($option['routeHere'], array($station))
				);
			}
		}
		return $newOptions;
	}

	/**
	 * @param $destination RailStation
	 * @param $options
	 * @param $costLimit int
	 *
	 * @return bool
	 */
	private function desinationInOptions($destination, $options, $costLimit) {
		$currentLowestCost = INF;
		$bestOption = false;
		foreach ($options as $option) {
			if (
				$destination->getTiploc() == array_slice($option['routeHere'], -1, 1)[0]->getTiploc()
				&& $option['cost'] < $currentLowestCost
				&& $option['cost'] > $costLimit
			) {
				$bestOption = $option;
				$currentLowestCost = $option['cost'];
			}
		}
		return $bestOption;
	}
}