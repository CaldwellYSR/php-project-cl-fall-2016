<?php

	// Quick check to help prevent direct access to this script.
	// Should do more validation beforehand
	if (!isset($_GET["id"])) {
		die("How did you get here? Go Away");
	}

	// Setup the database variable
	require_once("includes/functions.php");

	// Try deleting the tank @see includes/functions.php
	if (Tank::delete_by_id($_GET["id"])) {
		header("Location:index.php");
	} else {
		echo "Something went wrong... sorry about that";
	}

?>
