<?php

	class Database {
		
		public function __construct() {
			// TODO: Not hard coded.
			$this->db = new PDO(sprintf('%s:host=%s;dbname=%s', 'mysql', '127.0.0.1', 'longestroute'), 'root', '');
		}

		public function setupStationsTable() {
			$this->db->query("DROP TABLE IF EXISTS `stations`;");
			$this->db->query("CREATE TABLE `stations` ( `name` text NOT NULL, `tiploc` char(32) NOT NULL, `latitude` double NOT NULL, `longitude` double NOT NULL, PRIMARY KEY (`tiploc`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		}

		public function storeStation($tiploc, $name, $latitude, $longitude) {
			$stmt = $this->db->prepare('INSERT INTO stations(tiploc, name, latitude, longitude) VALUES (:tiploc, :name, :latitude, :longitude)');
			$stmt->execute(
				array(
					":tiploc" => $tiploc,
					":name" => $name,
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
	}
