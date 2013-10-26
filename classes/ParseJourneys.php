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
		 * Parse the file.
		 *
		 * @return True if we were able to parse some data.
		 */
		public function parse() {
			$handle = fopen($this->file, 'r');
			if ($handle) {
				$this->db->clearJourneys();
				while (($line = fgets($handle)) !== false) {
					$json = json_decode($line);
					if (isset($json->JsonScheduleV1->schedule_segment->schedule_location)) {
						$locations = $json->JsonScheduleV1->schedule_segment->schedule_location;
						$prev = '';
						foreach ($locations as $loc) {
							// Ignore Junctions
							if (!isset($loc->public_arrival) && !isset($loc->public_departure)) { continue; }
						
							if (empty($prev)) {
								$prev = $loc->tiploc_code;
							} else {
								if ($this->db->storeJourney($prev, $loc->tiploc_code)) {
									echo $prev, ' -> ', $loc->tiploc_code, "\n";
								}
								$prev = $loc->tiploc_code;
							}
						}
					}
				}
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}
?>
