<?php

	class Database {
		
		public function __construct($dbconfig) {
			$this->db = new PDO(sprintf('%s:host=%s;dbname=%s', $dbconfig['type'], $dbconfig['host'], $dbconfig['database']), $dbconfig['username'], $dbconfig['password']);
			$this->queries = 0;
		}

		public function setupStationsTable() {
			$this->db->query("DROP TABLE IF EXISTS `stations`;");
			$this->queries++;
			$this->db->query("CREATE TABLE `stations` ( `name` text NOT NULL, `crs` char(3) NOT NULL, `tiploc` char(32) NOT NULL, `latitude` double NOT NULL, `longitude` double NOT NULL, PRIMARY KEY (`tiploc`), UNIQUE KEY `crs` (`crs`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
			$this->queries++;
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
			$this->queries++;
		}
		
		public function clearJourneys() {
			$this->db->query('DELETE from journeys');
			$this->queries++;
		}
		
		public function storeJourney($source, $destination, $direct) {
			$stmt = $this->db->prepare('INSERT INTO journeys (source, destination, direct) VALUES (:source, :destination, :direct)');
			$stmt->execute(array(':source' => $source, ':destination' => $destination, ':direct' => ($direct ? 'true' : 'false')));
			$this->queries++;
		}

		public function fetchStationByCrs($crs) {
			$stmt = $this->db->prepare("SELECT name, crs, tiploc, latitude, longitude FROM stations WHERE crs=:crs");
			$stmt->execute(array('crs' => $crs));
			$this->queries++;
			return $stmt->fetch();
		}

		public function fetchReachableStations($tiploc) {
			$stmt = $this->db->prepare("SELECT stations.name, stations.crs, stations.tiploc, stations.latitude, stations.longitude FROM stations, journeys WHERE stations.tiploc = journeys.destination AND journeys.source = :tiploc");
			$stmt->execute(array('tiploc' => $tiploc));
			$this->queries++;
			return $stmt->fetchAll();
		}

		public function fetchDirectlyReachableStations($tiploc) {
			$stmt = $this->db->prepare("SELECT stations.name, stations.crs, stations.tiploc, stations.latitude, stations.longitude FROM stations, journeys WHERE stations.tiploc = journeys.destination AND journeys.source = :tiploc AND journeys.direct = 'true'");
			$stmt->execute(array('tiploc' => $tiploc));
			$this->queries++;
			return $stmt->fetchAll();
		}
	}
