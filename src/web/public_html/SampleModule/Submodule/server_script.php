<?php
	chdir(getRootFolder());
	// for authentication
	require_once "db/auth.php";

	authenticate();

	function getRootFolder() {
		return substr(__DIR__, 0, strpos(__DIR__, "/crm/") + 4);
	}
?>
