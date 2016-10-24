<?php
	require_once "db_init.php";
	require_once "auth.php";

	authenticate();
	prepareStatements();

	function loadQuestions($courseId) {
		$results = pg_execute($GLOBALS['db'], "load_questions", array($courseId));

		$data = array();
		while($row = pg_fetch_array($results)) {
			$data[] = array();
			foreach($row as $key => $value) {
				$data[count($data) - 1][$key] = utf8_encode($value);
			}
		}

		pg_free_result($results);
		return $data;
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

		# QUESTIONS
		$sql = "
			SELECT id, question_text
			FROM Question
			WHERE course_id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "load_questions", $sql);

		foreach($results as $result) {
			if(!$result) {
				error_log("Failed to prepare statement. SQL: " . $sql);
				throwError("Oops... something went wrong.");
			}
		}
	}
?>
