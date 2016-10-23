<?php
	chdir(getRootFolder());
	// for authentication
	require_once "db/auth.php";

	authenticate();
	$_SESSION['current_module'] = "Course_Administration";

	function getRootFolder() {
		return substr(__DIR__, 0, strpos(__DIR__, "/crm/") + 4);
	}
?>
