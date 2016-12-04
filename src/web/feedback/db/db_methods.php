<?php
	require_once "db_init.php";
	require_once "auth.php";

	authenticate();
	prepareStatements();

	if(isset($_POST['method'])) {
		if($_POST['method'] == "submitEvaluation") {
			submitEvaluation($_POST['data']);
		} else if($_POST['method'] == "submitComment") {
			submitComment($_POST['data']);
		} else if($_POST['method'] == "getEntryCode") {
			getEntryCode();
		} if($_POST['method'] == "logout") {
			logout();
		}
	}

	function submitEvaluation($data) {
		if(!$data) {
			echo "success";
			exit;
		}

		foreach($data as $evaluation) {
			$result = pg_execute($GLOBALS['db'], "submit_evaluation", array(
				$_SESSION['lecture_id'], $evaluation['question_id'], $evaluation['value']	
			));
			if(!$result) {
				throwError("Failed to submit evaluation. [Q_ID: " . $evaluation['question_id'] . "]");
			}
		}
		echo "success";
	}

	function submitComment($comment) {
		if(!$comment) {
			throwError("No data submited.");
		}
		$lecture_id = $_SESSION['lecture_id']; 

		$result = pg_execute($GLOBALS['db'], "submit_comment", array(
			$lecture_id, $comment	
		));
		if(!$result) {
			throwError("Failed to submit comment. [C_ID: " . $lecture_id . "]");
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

	function getEntryCode() {
		if(!isset($_SESSION['code'])) {
			throwError("Entry code not set.");
		}
		$data = array(
			"status" => "success",
			"code" => $_SESSION['code']
		);
		echo json_encode($data);
	}

	function toggleAttendance() {
		$attendanceInfo = pg_execute($GLOBALS['db'], "get_attendance_info", array(
			$_SESSION['code']
		));
		$attendanceInfo = pg_fetch_array($attendanceInfo);
		$result = pg_execute($GLOBALS['db'], "attended", array(
			$attendanceInfo['lecture_id'],
			$attendanceInfo['student_id']
		));
		if(!$result) {
			error_log("Failed to set attendance. ");
		}
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
			INSERT INTO Feedback(lecture_id, question_id, feedback)
			VALUES($1, $2, $3)
		";
		$results[] = pg_prepare($GLOBALS['db'], "submit_evaluation", $sql);

		# COMMENT
		$sql = "
			INSERT INTO Comment(lecture_id, comment_text)
			VALUES($1, $2)
		";
		$results[] = pg_prepare($GLOBALS['db'], "submit_comment", $sql);

		# ATTENDANCE
		$sql = "
			SELECT 
				L.id AS lecture_id, 
				LC.student_id AS student_id
			FROM lecture as L
			JOIN lecture_code as LC
				ON L.id = LC.lecture_id
			WHERE LC.code = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "get_attendance_info", $sql);

		$sql = "
			UPDATE Attendance
			SET attended = TRUE
			WHERE lecture_id = $1
				AND student_id = $2
		";
		$results[] = pg_prepare($GLOBALS['db'], "attended", $sql);


		foreach($results as $result) {
			if(!$result) {
				error_log("Failed to prepare statement. SQL: " . $sql);
				throwError("Oops... something went wrong.");
			}
		}
	}
?>
