<?php

	// Quick check to help prevent direct access to this script.
	// Should do more validation beforehand
	if (!isset($_POST["submit"])) {
		die("How did you get here? Go Away");
	}

	// Setup the database variable
	require_once("includes/functions.php");

	// Since we need to pass the POST data but the SQL query doesn't have a column for submit, unset the submit key
	// This kind of logic would be better suited in the create_tank function.
	unset($_POST["submit"]);
	
	// Attempt to create a new tank in the database and redirect back to home page.
	if (create_tank($_POST)) {
		header("Location:index.php");
	} else {
		echo "Something didn't work...";
		var_dump($_POST);
	}

?>
