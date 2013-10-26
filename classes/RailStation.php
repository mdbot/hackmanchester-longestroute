<?php

class RailStation
{
	private $_name;
	private $_latLng;
	private $_tiploc;


	public function __construct($params) {
		$this->_name = $params['name'];
		$this->_latLng = new LatLng($params['latitude'], $params['longitude']);
		$this->_tiploc = $params['tiploc'];
	}

	public function getName() {
		return $this->_name;
	}

	/**
	 * @return LatLng
	 */
	public function getLatLng() {
		return $this->_latLng;
	}

	public function getTiploc() {
		return $this->_tiploc;
	}

	/**
	 * @param $station RailStation
	 */
	public function getDistanceTo($station) {
		return $this->getLatLng()->distance($station->getLatLng());
	}
}