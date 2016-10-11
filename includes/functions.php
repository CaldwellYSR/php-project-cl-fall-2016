<?php 

	require_once("credentials.php");

	/** 
	 * Helper Function that returns PDO object
	 * Include at the top of any file you want to access the database
	 */
	function get_db() {

		$options = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
		);

		$dbstring = "mysql:host=".DBHOST.";dbname=".DBNAME;

		return new PDO($dbstring, DBUSER, DBPASS, $options);

	}

	/**
	 * Helper Function that returns roman numeral representation of 1-10
	 * @param Number $integer - Integer to be transformed
	 */
	function roman_numeral($integer) {
		$table = array('X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1);
		$return = "";
		while ($integer > 0) {
			foreach($table as $rom => $arb) {
				if ($integer >= $arb) {
					$integer -= $arb;
					$return .= $rom;
					break;
				}
			}
		}
		return $return;
	}

	/**
	 * Get All Tanks
	 * @param PDO $db - PDO Object that should have been returned from get_db()
	 */
	function get_tanks($db) {

		$sql = "
			SELECT *
			FROM `tanks`
		";

		$stmt = $db->query($sql);
		return $stmt->fetchAll();
	}

	/**
	 * Get specific tank by id, limited to 1 tank
	 * @param PDO $db - PDO Object that should have been returned from get_db()
	 */
	function get_tank_by_id($db, $id = 1) {
		$sql = "
			SELECT *
			FROM `tanks`
			WHERE id = :id
			LIMIT 1
		";

		$stmt = $db->prepare($sql);
		$stmt->execute(array(
			'id' => $id
		));
		$results = $stmt->fetchAll();
		if (count($results) == 0) {
			return false;
		}
		return $results;
	}

	/**
	 * Get all tanks with included options
	 * @param PDO $db - PDO Object that should have been returned from get_db()
	 * @param String $nation [optional] - Text representation of the tanks nation of origin
	 * @param Number $tier [optional] - Tier of the tank in the WoT Tech Tree
	 * @param String $type [optional] - Type of tank in the WoT Tech Tree
	 */
	function get_tanks_with_specs($db, $nation = "USSR", $tier = 4, $type = "Tank Destroyer") {

		$sql = "
			SELECT *
			FROM `tanks`
			WHERE nation = :nation
			AND tier = :tier
			AND type = :type
		";

		$stmt = $db->prepare($sql);
		$stmt->execute(array(
			'nation' => $nation,
			'tier' => $tier,
			'type' => $type
		));
		$results = $stmt->fetchAll();
		if (count($results) == 0) {
			return false;
		}
		return $results;

	}

	/**
	 * Create a new tank with the given options
	 * @param PDO $db - PDO Object that should have been returned from get_db()
	 * @param Array $details - An array of the column => value pairs of the tank to be put in the DB
	 */
	function create_tank($db, $details) {

		$details["id"] = NULL;

		if (!isset($details["hit_points"])) { $details["hit_points"] = null; }
		if (!isset($details["damage"])) { $details["damage"] = null; }
		if (!isset($details["penetration"])) { $details["penetration"] = null; }
		if (!isset($details["damage_per_minute"])) { $details["damage_per_minute"] = null; }


		$sql = "
			INSERT INTO `tanks`
			(`id`, `name`, `tier`, `nation`, `type`, `hit_points`, `damage`, `penetration`, `damage_per_minute`)
			VALUES
			(:id, :name, :tier, :nation, :type, :hit_points, :damage, :penetration, :damage_per_minute)
		";

		$stmt = $db->prepare($sql);
		return $stmt->execute($details);

	}

	/**
	 * Delete tank with given ID
	 * @param PDO $db - PDO Object that should have been returned from get_db()
	 * @param Number $id - ID of the tank to be deleted
	 */
	function delete_tank($db, $id) {
		
		if (!isset($id) || $id < 0) {
			return false;
		}

		$sql = "
			DELETE
			FROM `tanks`
			WHERE id = :id
			LIMIT 1
		";

		$stmt = $db->prepare($sql);
		return $stmt->execute(array(
			'id' => $id
		));

	}

?>