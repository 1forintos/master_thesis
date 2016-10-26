<?php
	require_once "db_init.php";
	require_once "auth.php";

	authenticate();
	prepareStatements();

	if(isset($_POST['method'])) {
		if($_POST['method'] == "loadCoursesOfLecturer") {
			loadCoursesOfLecturer();
		} else if($_POST['method'] == "startLecture") {
			startLecture($_POST['data']);
		} else if($_POST['method'] == "stopLecture") {
			deleteLectureCodes($_POST['data']);
		} 
	}

	function loadCoursesOfLecturer() {
		if(!isset($_SESSION['user_id'])) {
			error_log("User ID not found in SESSION.");
			throwError("Something went wrong");
		}
		if(!lecturerExists($_SESSION['user_id'])) {
			throwError("User is not a lecturer. [ID: " . $_SESSION['user_id'] . "]");
		}
		$results = pg_execute($GLOBALS['db'], "load_courses_of_lecturer", array($_SESSION['user_id']));

		$data = array();
		while($row = pg_fetch_array($results)) {
			$data[] = array();
			foreach($row as $key => $value) {
				$data[count($data) - 1][$key] = utf8_encode($value);
			}
		}

		pg_free_result($results);

		$result = array(
			"status" => "success",
			"data" => $data
		);
		echo json_encode($result);
	}

	function lecturerExists($id) {
		$result = pg_execute($GLOBALS['db'], "lecturer_exists", array($id));
		$result = pg_fetch_array($result);

		if($result['lecturer_exists']) {
			return true;
		}
		return false;
	}

	function courseExists($id) {
		$result = pg_execute($GLOBALS['db'], "course_exists", array($id));
		$result = pg_fetch_array($result);

		if($result['course_exists']) {
			return true;
		}
		return false;
	}

	function startLecture($courseId) {
		if(!courseExists($courseId)) {
			throwError("Course not found. [" . $courseId . "]");
		}

		$codes = generateLectureCodes($courseId);
		$result = array(
			"status" => "success",
			"data" => $codes
		);
		echo json_encode($result);
	}

	function generateLectureCodes($courseId) {
		$result = pg_execute($GLOBALS['db'], "num_of_students", array($courseId));
		$result = pg_fetch_array($result);

		$numToGen = $result['num_of_students'];
		if(!$numToGen) {
			return Array();
		}
		
		$lengthOfCodes = 5;
		$codes = generateRandomStrings($numToGen, $lengthOfCodes);

		foreach($codes as $code) {
			$result = pg_execute($GLOBALS['db'], "insert_lecture_code", array($courseId, $code));

			if(!$result) { 
				throwError("Failed to insert codes.");
			}
		}
		
		return $codes;
	}

	function generateRandomStrings($numOfStrings, $lengthOfString) {
		$i = 0;
		$strings = Array();

		while($i < $numOfStrings) {
			$newString = getRandomString($lengthOfString);
			if(!in_array($newString, $strings)) {
				$strings[] = $newString;
				$i++;
			}
		}

		return $strings;
	}

	function getRandomString($length) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$string = '';

		for ($i = 0; $i < $length; $i++) {
			$string .= $characters[mt_rand(0, strlen($characters) - 1)];
		}

		return $string;
	}

	function deleteLectureCodes($courseId) {
		$result = pg_execute($GLOBALS['db'], "delete_lecture_codes", array($courseId));

		if(!$result) { 
			throwError("Failed to delete lecture codes.");
		}

		echo "success";
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
			SELECT COUNT(*) AS lecturer_exists
			FROM Webuser
			WHERE 
				id = $1
				AND user_type = 'lecturer'
		";
		$results[] = pg_prepare($GLOBALS['db'], "lecturer_exists", $sql);

		$sql = "
			SELECT COUNT(*) AS course_exists
			FROM Course
			WHERE id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "course_exists", $sql);


		$sql = "
			SELECT 
				C.id as id, 
				C.course_code as course_code, 
				C.title as title 
			FROM Lecturer as L 
				LEFT JOIN Course as C 
				ON L.course_id = C.id 
			WHERE L.user_id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "load_courses_of_lecturer", $sql);

		$sql = "
			SELECT COUNT(*) AS num_of_students
			FROM Enrollment
			WHERE course_id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "num_of_students", $sql);

		$sql = "
			INSERT INTO Lecture_Code(course_id, code)
			VALUES($1, $2)
		";
		$results[] = pg_prepare($GLOBALS['db'], "insert_lecture_code", $sql);

		$sql = "
			DELETE FROM Lecture_Code
			WHERE course_id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "delete_lecture_codes", $sql);


		foreach($results as $result) {
			if(!$result) {
				error_log("Failed to prepare statement. SQL: " . $sql);
				throwError("Oops... something went wrong.");
			}
		}
	}
?>
