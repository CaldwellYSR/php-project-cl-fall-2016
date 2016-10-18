<?php 

class Tank {

    private $db;

    public function __construct(Array $obj) {
        $this->db = Config::getConnection();
        foreach ($obj as $key => $value) {
            $this->$key = $value;
        }
        $this->roman_tier = $this->roman_numeral($this->tier);
    }

    public function update(Array $details) {

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
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":name", $details["name"]);
        $stmt->bindParam(":tier", $details["tier"]);
        $stmt->bindParam(":nation", $details["nation"]);
        $stmt->bindParam(":type", $details["type"]);
        $stmt->bindParam(":hit_points", $details["hit_points"]);
        $stmt->bindParam(":damage", $details["damage"]);
        $stmt->bindParam(":penetration", $details["penetration"]);
        $stmt->bindParam(":damage_per_minute", $details["damage_per_minute"]);
        return $stmt->execute();

    }
    
    public function render_form() {
        ob_start(); ?>
            <form action="update_tank.php" method="POST">
                <input type="hidden" name="id" id="id" value="<?php echo $this->id; ?>" />
                <label for="name">Name: 
                    <input type="text" id="name" name="name" value="<?php echo $this->name; ?>" />
                </label>
                <label for="nation">Nation:
                    <select name="nation" id="nation">
                <option value="USSR"<?php selected("USSR", $this->nation); ?>>USSR</option>
                        <option value="GERMANY"<?php selected("GERMANY", $this->nation); ?>>Germany</option>
                        <option value="USA"<?php selected("USA", $this->nation); ?>>U.S.A.</option>
                        <option value="CHINA"<?php selected("CHINA", $this->nation); ?>>China</option>
                        <option value="FRANCE"<?php selected("FRANCE", $this->nation); ?>>France</option>
                        <option value="UK"<?php selected("UK", $this->nation); ?>>U.K.</option>
                        <option value="JAPAN"<?php selected("JAPAN", $this->nation); ?>>Japan</option>
                        <option value="CZECHOSLOVAKIA"<?php selected("CZECHOSLOVAKIA", $this->nation); ?>>Czechoslovakia</option>
                        <option value="SWEDEN"<?php selected("SWEDEN", $this->nation); ?>>Sweden</option>
                    </select>
                </label>
                <label for="type">Type:
                    <select name="type" id="type">
                        <option value="Light Tank"<?php selected("Light Tank", $this->type); ?>>Light Tank</option>
                        <option value="Medium Tank"<?php selected("Medium Tank", $this->type); ?>>Medium Tank</option>
                        <option value="Heavy Tank"<?php selected("Heavy Tank", $this->type); ?>>Heavy Tank</option>
                        <option value="Tank Destroyer"<?php selected("Tank Destroyer", $this->type); ?>>Tank Destroyer</option>
                        <option value="SPG"<?php selected("SPG", $this->type); ?>>Artillery Tank</option>
                    </select>
                </label>
                <label for="tier">Tier:
                    <select name="tier" id="tier">
                        <option value="1"<?php selected("1", $this->tier); ?>>I</option>
                        <option value="2"<?php selected("2", $this->tier); ?>>II</option>
                        <option value="3"<?php selected("3", $this->tier); ?>>III</option>
                        <option value="4"<?php selected("4", $this->tier); ?>>IV</option>
                        <option value="5"<?php selected("5", $this->tier); ?>>V</option>
                        <option value="6"<?php selected("6", $this->tier); ?>>VI</option>
                        <option value="7"<?php selected("7", $this->tier); ?>>VII</option>
                        <option value="8"<?php selected("8", $this->tier); ?>>VIII</option>
                        <option value="9"<?php selected("9", $this->tier); ?>>IX</option>
                        <option value="10"<?php selected("10", $this->tier); ?>>X</option>
                    </select>
                </label>
                <label for="hit_points">HP: 
                    <input type="number" min="1" step="1" id="hit_points" name="hit_points" value="<?php echo $this->hit_points; ?>" />
                </label>
                <label for="damage">Standard Shell Damage: 
                    <input type="number" min="1" step="1" id="damage" name="damage" value="<?php echo $this->damage; ?>" />
                </label>
                <label for="penetration">Standard Shell Penetration: 
                    <input type="number" min="1" step="1" id="penetration" name="penetration" value="<?php echo $this->penetration; ?>" />
                </label>
                <label for="damage_per_minute">DPM: 
                    <input type="number" min="1" step="1" id="damage_per_minute" name="damage_per_minute" value="<?php echo $this->damage_per_minute; ?>" />
                </label>
                <input class="button" type="submit" name="submit" id="submit" value="Submit" />
            </form>
        <?php echo ob_get_clean(); 
    }

	public function delete() {

		$sql = "
			DELETE
			FROM `tanks`
			WHERE id = :id
			LIMIT 1
		";

        $stmt = $this->db->prepare($sql);
		return $stmt->execute(array(
            'id' => $this->id
		));

	}

    public static function delete_by_id(int $id) {

		if (!isset($id) || $id < 0) {
			return false;
		}

		$sql = "
			DELETE
			FROM `tanks`
			WHERE id = :id
			LIMIT 1
		";

        $stmt = $this->db->prepare($sql);
		return $stmt->execute(array(
			'id' => $id
		));

    }

	public static function create_tank($details) {

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

        $stmt = $this->db->prepare($sql);
		return $stmt->execute($details);

	}

    public static function render_new_form() {
        ob_start(); ?>
            <form action="new_tank.php" method="POST">
                <label for="name">Name: 
                    <input type="text" id="name" name="name" />
                </label>
                <label for="nation">Nation:
                    <select name="nation" id="nation">
                        <option value="USSR">USSR</option>
                        <option value="GERMANY">Germany</option>
                        <option value="USA">U.S.A.</option>
                        <option value="CHINA">China</option>
                        <option value="FRANCE">France</option>
                        <option value="UK">U.K.</option>
                        <option value="JAPAN">Japan</option>
                        <option value="CZECHOSLOVAKIA">Czechoslovakia</option>
                        <option value="SWEDEN">Sweden</option>
                    </select>
                </label>
                <label for="type">Type:
                    <select name="type" id="type">
                        <option value="Light Tank">Light Tank</option>
                        <option value="Medium Tank">Medium Tank</option>
                        <option value="Heavy Tank">Heavy Tank</option>
                        <option value="Tank Destroyer">Tank Destroyer</option>
                        <option value="SPG">Artillery Tank</option>
                    </select>
                </label>
                <label for="tier">Tier:
                    <select name="tier" id="tier">
                        <option value="1">I</option>
                        <option value="2">II</option>
                        <option value="3">III</option>
                        <option value="4">IV</option>
                        <option value="5">V</option>
                        <option value="6">VI</option>
                        <option value="7">VII</option>
                        <option value="8">VII</option>
                        <option value="9">IX</option>
                        <option value="10">X</option>
                    </select>
                </label>
                <label for="hit_points">HP: 
                    <input type="number" min="1" step="1" id="hit_points" name="hit_points" />
                </label>
                <label for="damage">Standard Shell Damage: 
                    <input type="number" min="1" step="1" id="damage" name="damage" />
                </label>
                <label for="penetration">Standard Shell Penetration: 
                    <input type="number" min="1" step="1" id="penetration" name="penetration" />
                </label>
                <label for="damage_per_minute">DPM: 
                    <input type="number" min="1" step="1" id="damage_per_minute" name="damage_per_minute" />
                </label>
                <input class="button" type="submit" name="submit" id="submit" value="Submit" />
            </form>
        <?php echo ob_get_clean();
    }

	private function roman_numeral($integer) {
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

}

?>
