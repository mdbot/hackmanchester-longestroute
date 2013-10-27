<?php

class Journey
{
	/** @var RailStation[] */
	private $_scenicStops;

	public function __construct($directStops, $scenicStops) {
		$this->_scenicStops = $scenicStops;
		$this->_directStops = $directStops;
	}

	public function getOrigin() {
		return $this->_scenicStops[0]->getName();
	}

	public function getDestination() {
		return array_slice($this->_scenicStops, -1, 1)[0]->getName();
	}

	public function getScenicDistance() {
		return $this->getDistance($this->_scenicStops);
	}

	public function getDirectDistance() {
		return $this->getDistance($this->_directStops);
	}

	/**
	 * @return RailStation[]
	 */
	public function getStops() {
		return $this->_scenicStops;
	}

	/**
	 * @param $stops RailStation[]
	 *
	 * @return int
	 */
	private function getDistance($stops) {
		$distance = 0;
		for ( $i = 1; $i < count($stops); ++$i ) {
			$distance += $stops[$i - 1]->getDistanceTo($stops[$i]);
		}
		return $distance;
	}
}