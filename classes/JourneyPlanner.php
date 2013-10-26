<?php

class JourneyPlanner
{
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

		return new Journey(array($origin, $destination));
	}
}