<?php

class RailStations
{
	/** @var Database */
	private $_db;
	private $_memoisedResults;

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
			throw new UnrecognisedCodeException($crs);
		} else {
			return new RailStation($response);
		}
	}

	/**
	 * @param $station RailStation
	 *
	 * @return array
	 */
	public function getStationsDirectlyReachableFrom($station) {
		if (isset($this->_memoisedResults[$station->getTiploc()])) {
			return $this->_memoisedResults[$station->getTiploc()];
		} else {
			error_log("cache miss");
			$stations = array();
			foreach ($this->_db->fetchDirectlyReachableStations($station->getTiploc()) as $result) {
				$stations[] = new RailStation($result);
			}
			$this->_memoisedResults[$station->getTiploc()] = $stations;
			return $stations;
		}
	}
}
