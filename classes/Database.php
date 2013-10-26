<?php

	class Database {
		
		public function __construct($dbconfig) {
			// TODO: Not hard coded.
			$this->db = new PDO(sprintf('%s:host=%s;dbname=%s', $dbconfig['type'], $dbconfig['host'], $dbconfig['database']), $dbconfig['username'], $dbconfig['password']);
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
			$stmt->execute(array(':source' => $source, ':destination' => $destination));
		}

		public function fetchStationByCrs($crs) {
			$stmt = $this->db->prepare("SELECT name, crs, tiploc, latitude, longitude FROM stations WHERE crs=:crs");
			$stmt->execute(array('crs' => $crs));
			return $stmt->fetch();
		}

		public function fetchDirectlyReachableStations($tiploc) {
			$stmt = $this->db->prepare("SELECT stations.name, stations.crs, stations.tiploc, stations.latitude, stations.longitude FROM stations, journeys WHERE stations.tiploc = journeys.destination AND journeys.source = :tiploc");
			$stmt->execute(array('tiploc' => $tiploc));
			return $stmt->fetchAll();
		}
	}
