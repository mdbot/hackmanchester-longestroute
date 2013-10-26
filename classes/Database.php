<?php

	class Database {
		
		public function __construct() {
			// TODO: Not hard coded.
			$this->db = new PDO(sprintf('%s:host=%s;dbname=%s', 'mysql', 'localhost', 'longestroute'), 'root', '');
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

?>
