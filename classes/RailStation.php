<?php

class RailStation
{
	private $_name;
	private $_latLng;

	public function __construct($params) {
		$this->_name = $params['name'];
		$this->_latLng = new LatLng($params['latitude'], $params['longitude']);
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

	/**
	 * @param $station RailStation
	 */
	public function getDistanceTo($station) {
		return $this->getLatLng()->distance($station->getLatLng());
	}
}