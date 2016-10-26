<?php
	require_once "db_init.php";
	require_once "auth.php";

	authenticate();
	prepareStatements();

	if(isset($_POST['method'])) {
		if($_POST['method'] == "submitEvaluation") {
			submitEvaluation($_POST['data']);
		}
	}

	function submitEvaluation($data) {
		if(!$data) {
			echo "success";
			exit;
		}

		foreach($data as $evaluation) {
			$result = pg_execute($GLOBALS['db'], "submit_evaluation", array(
				$evaluation['question_id'], $evaluation['value']	
			));
			if(!$result) {
				throwError("Failed to submit evaluation. [Q_ID: " . $evaluation['question_id'] . "]");
			}
		}
		echo "success";
	}

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

		# EVALUATE
		$sql = "
			INSERT INTO Feedback(question_id, feedback)
			VALUES($1, $2)
		";
		$results[] = pg_prepare($GLOBALS['db'], "submit_evaluation", $sql);

		foreach($results as $result) {
			if(!$result) {
				error_log("Failed to prepare statement. SQL: " . $sql);
				throwError("Oops... something went wrong.");
			}
		}
	}
?>
