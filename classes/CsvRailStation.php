<?php

require( dirname(__FILE__) . '/../vendors/phpcoord-2.3.php' );

class CsvRailStation
{
	/**
	 * @return CsvRailStation[]
	 */
	static public function loadFromCsv() {
		$railStations = array();
		$handle = fopen('data/RailReferences.csv', 'r');
		fgetcsv($handle); // Skip header line in CSV
		while ($line = fgetcsv($handle)) {
			$railStations[] = new self($line);
		}
		return $railStations;
	}

	public function __construct($line) {
		$this->_tiploc = $line[1];
		$this->_crs = $line[2];
		$this->_name = mb_substr($line[3], 0, mb_strlen($line[3]) - 13); // strip 'Rail Station' off end of name
		$this->_latLng = new OSRef($line[6], $line[7]);
		$this->_latLng = $this->_latLng->toLatLng();
	}

	public function getTiploc() {
		return $this->_tiploc;
	}

	public function getCrs() {
		return $this->_crs;
	}

	public function getName() {
		return $this->_name;
	}

	public function getLatitude() {
		return $this->_latLng->lat;
	}

	public function getLongitude() {
		return $this->_latLng->lng;
	}

	private $_tiploc;
	private $_name;
	private $_latLng;
	private $_crs;

}