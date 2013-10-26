<?php

	class Database {
		
		public function __construct() {
			// TODO: Not hard coded.
			$this->db = new PDO(sprintf('%s:host=%s;dbname=%s', 'mysql', '127.0.0.1', 'longestroute'), 'root', '');
		}

		public function setupStationsTable() {
			$this->db->query("DROP TABLE IF EXISTS `stations`;");
			$this->db->query("CREATE TABLE `stations` ( `name` text NOT NULL, `crs` char(3) NOT NULL, `tiploc` char(32) NOT NULL, `latitude` double NOT NULL, `longitude` double NOT NULL, PRIMARY KEY (`tiploc`), UNIQUE KEY `crs` (`crs`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		}

		public function storeStation($tiploc, $crs, $name, $latitude, $longitude) {
			$stmt = $this->db->prepare('INSERT INTO stations(tiploc, crs, name, latitude, longitude) VALUES (:tiploc, :crs, :name, :latitude, :longitude)');
			$stmt->execute(
				array(
					":tiploc" => $tiploc,
					":name" => $name,
					":crs" => $crs,
					":latitude" => $latitude,
					":longitude" => $longitude
				)
			);
		}
		
		public function clearJourneys() {
			$this->db->query('DELETE from journeys');
		}
		
		public function storeJourney($source, $destination) {
			$stmt = $this->db->prepare('INSERT INTO journeys (source, destination) VALUES (:source, :destination)');
				if ($stmt) {
					if ($stmt->execute(array(':source' => $source, ':destination' => $destination))) {
						return TRUE;
					}
			}
			return FALSE;
		}

		public function fetchStationByCrs($crs) {
			$stmt = $this->db->prepare("SELECT name, crs, tiploc, latitude, longitude FROM stations WHERE crs=:crs");
			$stmt->execute(array('crs' => $crs));
			return $stmt->fetch();
		}
	}
