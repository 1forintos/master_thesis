<?php

ini_set("log_errors", 1);

require_once "db_init.php";

prepareStatements();

function prepareStatements() {
	$results = Array();

	$sql = "
		SELECT lecture_id
		FROM Lecture_Code
		WHERE code = $1
	";
	$results[] = pg_prepare($GLOBALS['db'], "get_lecture_id_for_code", $sql);

	foreach($results as $result) {
		if(!$result) {
			error_log("Failed to prepare statement. SQL: " . $sql);
		}
	}
}

?>