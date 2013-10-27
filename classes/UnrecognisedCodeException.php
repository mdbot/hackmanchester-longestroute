<?php

class UnrecognisedCodeException extends Exception {
	private $stationCode = '';

	public function __construct($stationCode) {
		$this->stationCode = $stationCode;
	}

	public function getStationCode() {
		return $this->stationCode;
	}
}
