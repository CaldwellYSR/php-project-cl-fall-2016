<?php 

	require_once("config.php");
    require_once("tank.php");

    function validate_data($post) {
        if (filter_var($post["tier"], FILTER_VALIDATE_INT)) {
            $post["id"] = intval($post["id"]);
            $post["tier"] = intval($post["tier"]);
            $post["hit_points"] = intval($post["hit_points"]);
            $post["damage"] = intval($post["damage"]);
            $post["penetration"] = intval($post["penetration"]);
            $post["damage_per_minute"] = intval($post["damage_per_minute"]);
            return $post;
        }
        return false;
    }

	/**
	 * Get All Tanks
	 */
	function get_tanks() {

        $db = Config::getConnection();

		$sql = "
			SELECT *
			FROM `tanks`
		";

		$stmt = $db->query($sql);
		$tanks = $stmt->fetchAll();
        $output = array();
        foreach ($tanks as $tank) {
            $output[] = new Tank($tank);
        }
        return $output;
	}

	function get_tank_by_id($id = 1) {

        $db = Config::getConnection();

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
		return new Tank($results[0]);
	}

	/**
	 * Get all tanks with included options
	 * @param String $nation [optional] - Text representation of the tanks nation of origin
	 * @param Number $tier [optional] - Tier of the tank in the WoT Tech Tree
	 * @param String $type [optional] - Type of tank in the WoT Tech Tree
	 */
	function get_tanks_with_specs($nation = "%", $tier = "%", $type = "%") {

        $db = Config::getConnection();

		$sql = "
			SELECT *
			FROM `tanks`
			WHERE nation LIKE :nation
			AND tier LIKE :tier
			AND type LIKE :type
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
        $output = array();
        foreach ($results as $tank) {
            $output[] = new Tank($tank);
        }
        return $output;

	}

?>
