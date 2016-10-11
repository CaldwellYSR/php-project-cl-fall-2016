<?php

	// Quick check to help prevent direct access to this script.
	// Should do more validation beforehand
	if (!isset($_GET["id"])) {
		die("How did you get here? Go Away");
	}

	// Setup the database variable
	require_once("includes/functions.php");
	$db = get_db();

	// Try deleting the tank @see includes/functions.php
	if (delete_tank($db, $_GET["id"])) {
		header("Location:index.php");
	} else {
		echo "Something went wrong... sorry about that";
	}

?>