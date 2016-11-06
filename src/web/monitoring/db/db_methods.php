<?php
	ini_set("log_errors", 1);
	ini_set('display_errors', 'On');
	require_once "db_init.php";

	prepareStatements();

	if(isset($_POST['method'])) {
		if($_POST['method'] == "getMeasurements") {
			getMeasurements($_POST['data']);
		} else if($_POST['method'] == "loadCourses") {
			loadCourses();
		} else if($_POST['method'] == "loadLectures") {
			loadLectures($_POST['data']);
		} else if($_POST['method'] == "loadQuestions") {
			loadQuestions($_POST['data']);
		} else if($_POST['method'] == "getFeedback") {
			getFeedback($_POST['data']);
		} else if($_POST['method'] == "loadAttendance") {
			loadAttendance($_POST['data']);
		} 
	}

	function loadCourses() {
		$result = pg_execute($GLOBALS['db'], "load_courses", array());
		if(!$result) {
			pg_free_result($result);
			throwError("Failed to get courses.");
		}	
		$data = pg_fetch_all($result);
		pg_free_result($result);
	
		$result = array(
			"status" => "success",
			"data" => $data
		);
		echo json_encode($result);		
	}

	function loadLectures($courseId) {
		if(!$courseId) {
			throwError("No course selected.");
		}
		$result = pg_execute($GLOBALS['db'], "load_lectures", array($courseId));
		if(!$result) {
			pg_free_result($result);
			throwError("Failed to get lectures. [C_ID: " . $courseId . "]");
		}	
		$data = pg_fetch_all($result);
		pg_free_result($result);
	
		$result = array(
			"status" => "success",
			"data" => $data
		);
		echo json_encode($result);		
	}



	function loadQuestions($courseId) {
		if(!$courseId) {
			throwError("No course selected.");
		}
		$result = pg_execute($GLOBALS['db'], "load_questions", array($courseId));
		if(!$result) {
			pg_free_result($result);
			throwError("Failed to get questions. [C_ID: " . $courseId . "]");
		}	
		$data = array();
		while($row = pg_fetch_array($result)) {
			$data[] = array();
			foreach($row as $key => $value) {
				$data[count($data) - 1][$key] = utf8_encode($value);
			}
		}
		pg_free_result($result);

		$result = array(
			"status" => "success",
			"data" => $data
		);
		echo json_encode($result);		
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
		$data = array();
		while($row = pg_fetch_array($result)) {
			$data[] = array();
			foreach($row as $key => $value) {
				$data[count($data) - 1][$key] = utf8_encode($value);
			}
		}
		pg_free_result($result);

		$result = array(
			"status" => "success",
			"data" => $data
		);
		echo json_encode($result);
	}

	function getFeedback($data) {
		if(!$data['lecture_id']) {
			throwError("No lecture selected.");
		}
		if(!$data['question_id']) {
			throwError("No question selected.");
		}
		if(!lectureExists($data['lecture_id'])) {
			throwError("Lecture not found. [ID: " . $data['lecture_id'] . "]");
		}
		if(!questionExists($data['question_id'])) {
			throwError("Question not found. [ID: " . $data['question_id'] . "]");
		}

		$result = pg_execute($GLOBALS['db'], "load_feedback_for_question", array(
			$data['lecture_id'], $data['question_id']
		));
		if(!$result) {
			pg_free_result($result);
			throwError("Failed to load feedback.");
		}	
		$data = array();
		while($row = pg_fetch_array($result)) {
			$data[] = array();
			foreach($row as $key => $value) {
				$data[count($data) - 1][$key] = utf8_encode($value);
			}
		}
		pg_free_result($result);

		$result = array(
			"status" => "success",
			"data" => $data
		);
		echo json_encode($result);		
	}

	function loadAttendance($lectureId) {
		if(!$lectureId) {
			throwError("No course selected.");
		}
		$result = pg_execute($GLOBALS['db'], "load_attendance", array($lectureId));
		if(!$result) {
			pg_free_result($result);
			throwError("Failed to load attendance. [L_ID: " . $lectureId . "]");
		}	
		$data = pg_fetch_all($result);
		pg_free_result($result);
	
		error_log(print_r($data, true));
		$result = array(
			"status" => "success",
			"data" => $data
		);
		echo json_encode($result);		
	}

	function lectureExists($lectureId) {
		$result = pg_execute($GLOBALS['db'], "lecture_exists", array($lectureId));
		$result = pg_fetch_array($result);

		if($result['lecture_exists']) {
			return true;
		}
		return false;
	}


	function questionExists($questionId) {
		$result = pg_execute($GLOBALS['db'], "question_exists", array($questionId));
		$result = pg_fetch_array($result);

		if($result['question_exists']) {
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
			SELECT id, course_code, title
			FROM Course
		";
		$results[] = pg_prepare($GLOBALS['db'], "load_courses", $sql);

		$sql = "
			SELECT id, title, start_date, end_date
			FROM Lecture
			WHERE course_id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "load_lectures", $sql);
		
		$sql = "
			SELECT id, question_text
			FROM Question
			WHERE course_id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "load_questions", $sql);

		$sql = "
			SELECT student_id, attended
			FROM Attendance
			WHERE lecture_id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "load_attendance", $sql);


		$sql = "
			SELECT value, timestamp
			FROM Measurement
			WHERE lecture_id = $1
				AND type = $2
		";
		$results[] = pg_prepare($GLOBALS['db'], "get_measurements", $sql);

		$sql = "
			SELECT feedback, timestamp
			FROM Feedback
			WHERE lecture_id = $1
				AND question_id = $2
		";
		$results[] = pg_prepare($GLOBALS['db'], "load_feedback_for_question", $sql);

		$sql = "
			SELECT COUNT(*) AS lecture_exists
			FROM Lecture
			WHERE id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "lecture_exists", $sql);

		$sql = "
			SELECT COUNT(*) AS question_exists
			FROM Question
			WHERE id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "question_exists", $sql);

		foreach($results as $result) {
			if(!$result) {
				error_log("Failed to prepare statement. SQL: " . $sql);
				throwError("Oops... something went wrong.");
			}
		}
	}
?>
