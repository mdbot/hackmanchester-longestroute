<?php

class RailStations
{
	/** @var Database */
	private $_db;

	public function __construct($db) {
		$this->_db = $db;
	}

	public function initStationsStorage() {
		$this->_db->setupStationsTable();
	}

	/**
	 * @param $station CsvRailStation
	 */
	public function storeStation($station) {
		$this->_db->storeStation(
			$station->getTiploc(),
			$station->getCrs(),
			$station->getName(),
			$station->getLatitude(),
			$station->getLongitude()
		);
	}

	public function getByCrs($crs) {
		$response = $this->_db->fetchStationByCrs($crs);
		if (empty($response)) {
			throw new UnrecognisedCodeException();
		} else {
			return new RailStation($response);
		}
	}
}
