<?php
	require_once(dirname(__FILE__) . '/Database.php');

	/**
	 * Class to parse journey data from an unzipped traindata.gz file.
	 */
	class ParseJourneys {
		/** File to parse. */
		private $file;
		
		/** Database class. */
		private $db;

		/**
		 * Create a new journey parser.
		 *
		 * @param $file File to parse.
		 * @param $database Database class.
		 */
		public function __construct($file, $database) {
			$this->file = $file;
			$this->db = $database;
		}

		/**
		 * Parse an individual line of data.
		 *
		 * @param $line Line to parse.
		 * @return true if the line was parsed as location data, else false.
		 */
		private function parseLine($line) {
			$json = json_decode($line);
			if (!isset($json->JsonScheduleV1->schedule_segment->schedule_location)) {
				return FALSE;
			}

			$locations = $json->JsonScheduleV1->schedule_segment->schedule_location;
			$validLocs = array();
			foreach ($locations as $loc) {
				// Ignore Junctions
				if (!isset($loc->public_arrival) && !isset($loc->public_departure)) { continue; }

				$validLocs[] = $loc->tiploc_code;
			}

			foreach ($validLocs as $source) {
				// We only care about stations AFTER the source in the schedule.
				$valid = false;
				foreach ($validLocs as $destination) {
					if ($valid) {
						$this->db->storeJourney($source, $destination);
					} else {
						$valid = ($source == $destination);
						continue;
					}
				}
			}

			return TRUE;
		}

		/**
		 * Parse the file.
		 *
		 * @return True if we were able to parse some data.
		 */
		public function parse() {
			$handle = gzopen($this->file, 'r');
			if ($handle) {
				$this->db->db->beginTransaction();
				$i = 1;
				while (($line = fgets($handle)) !== false) {
					echo $i++ , "\n";
					if ($i % 10000 == 0) {
						$this->db->db->commit();
						$this->db->db->beginTransaction();
					}
					$this->parseLine($line);
				}
				$this->db->db->commit();
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}
?>
