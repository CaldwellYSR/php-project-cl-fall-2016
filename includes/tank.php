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

	function delete() {

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

}

?>
