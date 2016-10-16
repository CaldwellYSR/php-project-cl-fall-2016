<?php 

	require_once("config.php");

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

    function selected($expected, $actual) {
        if ($expected == $actual) { echo " selected"; }
        return null;
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
	 */
	function get_tanks() {

        $db = Config::getConnection();

		$sql = "
			SELECT *
			FROM `tanks`
		";

		$stmt = $db->query($sql);
		return $stmt->fetchAll();
	}

	/**
	 * Get specific tank by id, limited to 1 tank
	 */
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
		return $results[0];
	}

	/**
	 * Get all tanks with included options
	 * @param String $nation [optional] - Text representation of the tanks nation of origin
	 * @param Number $tier [optional] - Tier of the tank in the WoT Tech Tree
	 * @param String $type [optional] - Type of tank in the WoT Tech Tree
	 */
	function get_tanks_with_specs($nation = "USSR", $tier = 4, $type = "Tank Destroyer") {

        $db = Config::getConnection();

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
	 * @param Array $details - An array of the column => value pairs of the tank to be put in the DB
	 */
	function create_tank($details) {

        $db = Config::getConnection();
        
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
	 * @param Number $id - ID of the tank to be deleted
	 */
	function delete_tank($id) {

        $db = Config::getConnection();
		
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

    /**
     * Update tank with given id and details
     * @param Number $id - ID of the tank to be updated
     * @param Array $details - Array of key value pairs of the columns to be updated
     */
    function update_tank($id, $details) {

        $db = Config::getConnection();
        $sql = "
            UPDATE `tanks`
            SET name = :name, 
                tier = :tier, 
                nation = :nation, 
                type = :type, 
                hit_points = :hit_points, 
                damage = :damage, 
                penetration = :penetration, 
                damage_per_minute = :damage_per_minute
            WHERE id = :id
        ";
        $stmt = $db->prepare($sql);
        return $stmt->execute($details);

    }

    /**
     * Returns output buffer of the form to render based on the tank input
     * @param Object $tank [optional] - Tank object retrieved from the database
     */
    function render_form($tank = null) {
        ob_start(); ?>
            <form action="<?php echo (isset($tank)) ? "update_tank.php" : "new_tank.php"; ?>" method="POST">
                <input type="hidden" name="id" id="id" value="<?php echo (isset($tank)) ? $tank->id : ""; ?>" />
                <label for="name">Name: 
                    <input type="text" id="name" name="name" value="<?php echo (isset($tank)) ? $tank->name : ""; ?>" />
                </label>
                <label for="nation">Nation:
                    <select name="nation" id="nation">
                        <option value="USSR"<?php (isset($tank)) ? selected("USSR", $tank->nation) : ""; ?>>USSR</option>
                        <option value="GERMANY"<?php (isset($tank)) ? selected("GERMANY", $tank->nation) : ""; ?>>Germany</option>
                        <option value="USA"<?php (isset($tank)) ? selected("USA", $tank->nation) : ""; ?>>U.S.A.</option>
                        <option value="CHINA"<?php (isset($tank)) ? selected("CHINA", $tank->nation) : ""; ?>>China</option>
                        <option value="FRANCE"<?php (isset($tank)) ? selected("FRANCE", $tank->nation) : ""; ?>>France</option>
                        <option value="UK"<?php (isset($tank)) ? selected("UK", $tank->nation) : ""; ?>>U.K.</option>
                        <option value="JAPAN"<?php (isset($tank)) ? selected("JAPAN", $tank->nation) : ""; ?>>Japan</option>
                        <option value="CZECHOSLOVAKIA"<?php (isset($tank)) ? selected("CZECHOSLOVAKIA", $tank->nation) : ""; ?>>Czechoslovakia</option>
                        <option value="SWEDEN"<?php (isset($tank)) ? selected("SWEDEN", $tank->nation) : ""; ?>>Sweden</option>
                    </select>
                </label>
                <label for="type">Type:
                    <select name="type" id="type">
                        <option value="Light Tank"<?php (isset($tank)) ? selected("Light Tank", $tank->type) : ""; ?>>Light Tank</option>
                        <option value="Medium Tank"<?php (isset($tank)) ? selected("Medium Tank", $tank->type) : ""; ?>>Medium Tank</option>
                        <option value="Heavy Tank"<?php (isset($tank)) ? selected("Heavy Tank", $tank->type) : ""; ?>>Heavy Tank</option>
                        <option value="Tank Destroyer"<?php (isset($tank)) ? selected("Tank Destroyer", $tank->type) : ""; ?>>Tank Destroyer</option>
                        <option value="SPG"<?php (isset($tank)) ? selected("SPG", $tank->type) : ""; ?>>Artillery Tank</option>
                    </select>
                </label>
                <label for="tier">Tier:
                    <select name="tier" id="tier">
                        <option value="1"<?php (isset($tank)) ? selected("1", $tank->tier) : ""; ?>>I</option>
                        <option value="2"<?php (isset($tank)) ? selected("2", $tank->tier) : ""; ?>>II</option>
                        <option value="3"<?php (isset($tank)) ? selected("3", $tank->tier) : ""; ?>>III</option>
                        <option value="4"<?php (isset($tank)) ? selected("4", $tank->tier) : ""; ?>>IV</option>
                        <option value="5"<?php (isset($tank)) ? selected("5", $tank->tier) : ""; ?>>V</option>
                        <option value="6"<?php (isset($tank)) ? selected("6", $tank->tier) : ""; ?>>VI</option>
                        <option value="7"<?php (isset($tank)) ? selected("7", $tank->tier) : ""; ?>>VII</option>
                        <option value="8"<?php (isset($tank)) ? selected("8", $tank->tier) : ""; ?>>VIII</option>
                        <option value="9"<?php (isset($tank)) ? selected("9", $tank->tier) : ""; ?>>IX</option>
                        <option value="10"<?php (isset($tank)) ? selected("10", $tank->tier) : ""; ?>>X</option>
                    </select>
                </label>
                <label for="hit_points">HP: 
                    <input type="number" min="1" step="1" id="hit_points" name="hit_points" value="<?php echo (isset($tank)) ? $tank->hit_points : ""; ?>" />
                </label>
                <label for="damage">Standard Shell Damage: 
                    <input type="number" min="1" step="1" id="damage" name="damage" value="<?php echo (isset($tank)) ? $tank->damage : ""; ?>" />
                </label>
                <label for="penetration">Standard Shell Penetration: 
                    <input type="number" min="1" step="1" id="penetration" name="penetration" value="<?php echo (isset($tank)) ? $tank->penetration : ""; ?>" />
                </label>
                <label for="damage_per_minute">DPM: 
                    <input type="number" min="1" step="1" id="damage_per_minute" name="damage_per_minute" value="<?php echo (isset($tank)) ? $tank->damage_per_minute : ""; ?>" />
                </label>
                <input class="button" type="submit" name="submit" id="submit" value="Submit" />
            </form>
        <?php echo ob_get_clean(); 
    }

?>
