<?php
	chdir(substr(__DIR__, 0, strpos(__DIR__, "/sm/") + 3));

	require_once "db/auth.php";
	require_once "header/header_script.php";

	authenticate();
?>