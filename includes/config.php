<?php 

class Config {

    private static $db, $dbname, $dbuser, $dbpass, $dbhost;

    private function __construct() {}

	/** 
	 * Helper Function that returns PDO object
	 * Include at the top of any file you want to access the database
	 */
	public static function getConnection() {

        static $self;
        if (!isset($self)) {
            $dbstring = "mysql:host=localhost;dbname=world_of_tanks";
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );
            $self = new PDO($dbstring, 'dev_wot', '32$a_2JDM%@2aDme', $options);
        }

        return $self;

	}

}

?>
