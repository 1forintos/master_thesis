<?php
	chdir(getRootFolder());
	// for authentication
	require_once "db/auth.php";
	require_once "db/db_methods.php";

	authenticate();	

	function getRootFolder() {
		return substr(__DIR__, 0, strpos(__DIR__, "/sm/") + 3);
	}

?>