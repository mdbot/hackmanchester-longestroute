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
			$station->getName(),
			$station->getLatitude(),
			$station->getLongitude()
		);
	}
}
