<?php
	ini_set("log_errors", 1);
	ini_set('display_errors', 'On');
	require_once "db_init.php";

	prepareStatements();

	if(isset($_POST['method'])) {
		if($_POST['method'] == "getMeasurements") {
			getMeasurements($_POST['data']);
		} 
	}

	function getMeasurements($data) {
		if(!$data['lecture_id']) {
			throwError("No lecture selected.");
		}
		if(!lectureExists($data['lecture_id'])) {
			throwError("Lecture not found. [ID: " . $data['lecture_id'] . "]");
		}
		$result = pg_execute($GLOBALS['db'], "get_measurements", array(
			$data['lecture_id'],
			$data['type']
		));
		if(!$result) {
			throwError("Failed to retrieve measurements. [L_ID: " . $data['lecture_id'] . 
				", type: " . $data['type'] . "]");
		}
		$results = pg_fetch_all($result);

		$results = array(
			"status" => "success",
			"data" => $results
		);
		echo json_encode($results);
	}

	function lectureExists($lectureId) {
		$result = pg_execute($GLOBALS['db'], "lecture_exists", array($lectureId));
		$result = pg_fetch_array($result);

		if($result['lecture_exists']) {
			return true;
		}
		return false;
	}

	function throwError($msg) {
		$errorData = array(
			"error" => $msg
		);
		echo json_encode($errorData);
		exit;
	}

	function prepareStatements() {
		$results = Array();

		$sql = "
			SELECT value, timestamp
			FROM Measurement
			WHERE lecture_id = $1
				AND type = $2
		";
		$results[] = pg_prepare($GLOBALS['db'], "get_measurements", $sql);

		$sql = "
			SELECT COUNT(*) AS lecture_exists
			FROM Lecture
			WHERE id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "lecture_exists", $sql);

		foreach($results as $result) {
			if(!$result) {
				error_log("Failed to prepare statement. SQL: " . $sql);
				throwError("Oops... something went wrong.");
			}
		}
	}
?>
