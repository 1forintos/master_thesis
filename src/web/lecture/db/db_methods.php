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
			stopLecture($_POST['data']);
		} else if($_POST['method'] == "getUserId") {
			getUserId();
		} 
	}

	function getUserId() {
		if(!isset($_SESSION['user_id'])) {
			error_log("User ID not found in SESSION.");
			throwError("Something went wrong");
		}
		$data = array(
			"status" => "success",
			"userId" => $_SESSION['user_id']
		);
		echo json_encode($data);
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

		$result = pg_execute($GLOBALS['db'], "lecture_started", array($courseId));
		$result = pg_fetch_array($result);
		if($result['lecture_started']) {
			throwError("A lecture is already in progress from the selected course.");
		}

		$title = "";
		$result = pg_execute($GLOBALS['db'], "start_lecture", array($courseId, $title));
		if(!$result) {
			throwError("Failed to start lecture for course. [ID: " . $courseId . "]");
		}
		$lectureId = getIdOfLectureInProgress($courseId);
		initAttendanceForNewLectureOfCourse($lectureId, $courseId);

		$codes = generateLectureCodes($courseId);
		$result = array(
			"status" => "success",
			"data" => $codes
		);
		echo json_encode($result);
	}

	function stopLecture($courseId) {
		if(!courseExists($courseId)) {
			throwError("Course not found. [" . $courseId . "]");
		}

		
		$lectureId = getIdOfLectureInProgress($courseId);
		if(!$lectureId) {
			throwError("There is no lecture in progress from the selected course.");
		} 

		deleteLectureCodes($lectureId);
		$result = pg_execute($GLOBALS['db'], "end_lecture", array($lectureId));
		if(!$result) {
			throwError("Failed to stop lecture for course.");
		}

		echo "success";
	}

	function initAttendanceForNewLectureOfCourse($lectureId, $courseId) {
		$result = pg_execute($GLOBALS['db'], "get_student_ids", array($courseId));
		$studentIds = pg_fetch_all($result);
		

		foreach($studentIds as $studentId) {
			$result = pg_execute($GLOBALS['db'], "insert_attendance", array(
				$lectureId, 
				$studentId['student_id']
			));
			if(!$result) {
				throwError("Failed to init attendance.");
			}
		}
	}

	function getIdOfLectureInProgress($courseId) {
		$result = pg_execute($GLOBALS['db'], "get_id_of_lecture_in_progress", array($courseId));
		$result = pg_fetch_array($result);
		return $result['id'];
	}

	function generateLectureCodes($courseId) {
		$result = pg_execute($GLOBALS['db'], "get_student_ids", array($courseId));
		$studentIds = pg_fetch_all($result);

		$numToGen = count($studentIds);
		if(!$numToGen) {
			return Array();
		}
		
		$lengthOfCodes = 5;
		$codes = generateRandomStrings($numToGen, $lengthOfCodes);
		$lectureId = getIdOfLectureInProgress($courseId);
		$studentsData = array_combine($codes, $studentIds);
		foreach($studentsData as $code => $studentId) {
			$result = pg_execute($GLOBALS['db'], "insert_lecture_code", array($lectureId, $code, $studentId['student_id']));
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

	function deleteLectureCodes($lectureId) {
		$result = pg_execute($GLOBALS['db'], "delete_lecture_codes", array($lectureId));

		if(!$result) { 
			throwError("Failed to delete lecture codes.");
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
			SELECT 
				COUNT(*) as lecture_started,
				id 
			FROM Lecture
			WHERE id = $1
				AND status = 'in_progress'
			GROUP BY id
		";
		$results[] = pg_prepare($GLOBALS['db'], "lecture_started", $sql);

		$sql = "
			INSERT INTO Lecture(course_id, title, start_date)
			VALUES($1, $2, now())
		";
		$results[] = pg_prepare($GLOBALS['db'], "start_lecture", $sql);

		$sql = "
			UPDATE Lecture
			SET 
				end_date = now(),
				status = 'finished'
			WHERE id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "end_lecture", $sql);

		$sql = "
			SELECT COUNT(*) AS num_of_students
			FROM Enrollment
			WHERE course_id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "num_of_students", $sql);

		$sql = "
			SELECT student_id
			FROM Enrollment
			WHERE course_id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "get_student_ids", $sql);

		$sql = "
			SELECT id
			FROM Lecture
			WHERE course_id = $1
				AND status = 'in_progress'
		";
		$results[] = pg_prepare($GLOBALS['db'], "get_id_of_lecture_in_progress", $sql);

		$sql = "
			INSERT INTO Lecture_Code(lecture_id, code, student_id)
			VALUES($1, $2, $3)
		";
		$results[] = pg_prepare($GLOBALS['db'], "insert_lecture_code", $sql);

		$sql = "
			DELETE FROM Lecture_Code
			WHERE lecture_id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "delete_lecture_codes", $sql);

		$sql = "
			INSERT INTO Attendance(lecture_id, student_id, attended)
			VALUES($1, $2, FALSE)
		";
		$results[] = pg_prepare($GLOBALS['db'], "insert_attendance", $sql);

		foreach($results as $result) {
			if(!$result) {
				error_log("Failed to prepare statement. SQL: " . $sql);
				throwError("Oops... something went wrong.");
			}
		}
	}
?>
