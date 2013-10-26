<?php

class Journey
{
	/** @var RailStation[] */
	private $_stops;

	public function __construct($stops) {
		$this->_stops = $stops;
	}

	public function getOrigin() {
		return $this->_stops[0]->getName();
	}

	public function getDestination() {
		return array_slice($this->_stops, -1, 1)[0]->getName();
	}

	public function getDistance() {
		$distance = 0;
		for ($i = 1; $i < count($this->_stops); ++$i) {
			$distance += $this->_stops[$i-1]->getDistanceTo($this->_stops[$i]);
		}
		return $distance;
	}

	/**
	 * @return RailStation[]
	 */
	public function getStops() {
		return $this->_stops;
	}
}