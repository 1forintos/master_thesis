<?php
	require_once "db_init.php";
	require_once "auth.php";

	prepareStatements();

	authenticate();

	if(isset($_POST['method'])) {
		if($_POST['method'] == "loadData") {
			loadData($_POST['data']);
		} else if($_POST['method'] == "createUserAccount") {
			createUserAccount($_POST['data']);
		} else if($_POST['method'] == "modifyUserAccount") {
			modifyUserAccount($_POST['data']);
		} else if($_POST['method'] == "deleteUserAccount") {
			deleteUserAccount($_POST['data']);
		} else if($_POST['method'] == "createCourse") {
			createCourse($_POST['data']);
		} else if($_POST['method'] == "modifyCourse") {
			modifyCourse($_POST['data']);
		} else if($_POST['method'] == "deleteCourse") {
			deleteCourse($_POST['data']);
		} else if($_POST['method'] == "loadCoursesForLecturers") {
			loadCoursesForLecturers($_POST['data']);
		} else if($_POST['method'] == "assignLecturers") {
			assignLecturers($_POST['data']);
		} else if($_POST['method'] == "unassignLecturers") {
			unassignLecturers($_POST['data']);
		} else if($_POST['method'] == "loadEnrollments") {
			loadEnrollments($_POST['data']);
		} else if($_POST['method'] == "removeEnrollments") {
			removeEnrollments($_POST['data']);
		} 
	}

	function loadData($info) {
		if($info == "user_accounts") {
			$results = pg_execute($GLOBALS['db'], "load_users", array());
		} else if($info == "courses") {
			$results = pg_execute($GLOBALS['db'], "load_courses", array());			
		} else if($info == "lecturers") {
			$results = pg_execute($GLOBALS['db'], "load_lecturers", array());			
		} 

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

	# USER ACCOUNT

	function createUserAccount($accountData) {
		if(!filter_var($accountData['email'], FILTER_VALIDATE_EMAIL)) {
			throwError("Invalid E-mail format.");
		}
		if(emailAlreadyUsed($accountData['email'])) {
			throwError("Email [" . $accountData['email'] . "] already used.");
		}
		if($accountData['full_name'] == "") {
			throwError("Full name cannot be empty.");
		}

		$result = pg_execute($GLOBALS['db'], "create_user", array(
			$accountData['email'],
			$accountData['full_name'],
			$accountData['user_type'],
			$accountData['notes']
		));

		if(!$result) {
			pg_free_result($result);
			throwError("Failed to insert account.");
		}

		echo "success";
	}

	function modifyUserAccount($accountData) {
		if(!userAccountExists($accountData['id'])) {
			throwError("Account not found. ID [" . $accountData['id'] . "]");
		}

		$result = pg_execute($GLOBALS['db'], "modify_user", array(
			$accountData['full_name'],
			$accountData['user_type'],
			$accountData['notes'],
			$accountData['id']
		));

		if(!$result) {
			pg_free_result($result);
			throwError("Failed to update user account.");
		}

		echo "success";
	}

	function deleteUserAccount($accountData) {
		if(!userAccountExists($accountData['id'])) {
			throwError("Account not found. ID [" . $accountData['id'] . "]");
		}

		$result = pg_execute($GLOBALS['db'], "delete_user", array(
			$accountData['id']
		));

		if(!$result) {
			pg_free_result($result);
			throwError("Failed to DELETE account.");
		}

		echo "success";
	}

	function userAccountExists($id) {
		$result = pg_execute($GLOBALS['db'], "user_exists", array($id));
		$result = pg_fetch_array($result);

		if($result['user_exists']) {
			return true;
		}
		return false;
	}

	function emailAlreadyUsed($email) {
		$result = pg_execute($GLOBALS['db'], "email_used", array($email));
		$result = pg_fetch_array($result);

		if($result['email_used']) {
			return true;
		}
		return false;
	}

	# COURSES

	function createCourse($courseData) {
		if($courseData['course_code'] == '' || $courseData['title'] == '') {
			throwError("Course Code and Title cannot be empty.");
		}
		if(courseCodeAlreadyUsed($courseData['course_code'])) {
			throwError("Course Code [" . $courseData['course_code'] . "] already used.");
		}

		$result = pg_execute($GLOBALS['db'], "create_course", array(
			$courseData['course_code'],
			$courseData['title'],
			$courseData['notes']
		));

		if(!$result) {
			pg_free_result($result);
			throwError("Failed to insert course.");
		}

		echo "success";
	}

	function modifyCourse($courseData) {
		if(!courseExists($courseData['id'])) {
			throwError("Account not found. ID [" . $courseData['id'] . "]");
		}

		$result = pg_execute($GLOBALS['db'], "modify_course", array(
			$courseData['course_code'],
			$courseData['title'],
			$courseData['notes'],
			$courseData['id']
		));

		if(!$result) {
			pg_free_result($result);
			throwError("Failed to update course.");
		}

		echo "success";
	}

	function deleteCourse($course) {
		if(!courseExists($course['id'])) {
			throwError("Course not found. ID [" . $course['id'] . "]");
		}

		$result = pg_execute($GLOBALS['db'], "delete_course", array(
			$course['id']
		));

		if(!$result) {
			pg_free_result($result);
			throwError("Failed to delete course.");
		}

		echo "success";
	}

	function courseExists($id) {
		$result = pg_execute($GLOBALS['db'], "course_exists", array($id));
		$result = pg_fetch_array($result);

		if($result['course_exists']) {
			return true;
		}
		return false;
	}

	function courseCodeAlreadyUsed($email) {
		$result = pg_execute($GLOBALS['db'], "course_code_used", array($email));
		$result = pg_fetch_array($result);

		if($result['course_code_used']) {
			return true;
		}
		return false;
	}

	function loadCoursesForLecturers($lecturerIds) {
		if(!$lecturerIds) {
			throwError("No lecturer is selected.");
		}	
		$tmp = array();	
		foreach($lecturerIds as $id) {
			$result = pg_execute($GLOBALS['db'], "courses_of_lecturer", array($id));
			if(!$result) {
				pg_free_result($result);
				throwError("Failed to get lecturers course. [ID: " . $id . "]");
			}	
			$data = array();		
			while($row = pg_fetch_array($result)) {						
				$data[] = $row['course_id'];	
			}
			$tmp[] = array();
			$tmp[sizeof($tmp) - 1] = $data;
			pg_free_result($result);
		}
		$courseIds = reset($tmp);
		foreach($tmp as $ids) {
			$tmp2 = $courseIds;
			$courseIds = array_intersect($tmp2, $ids); 
		}

		$courseIds = array_values($courseIds);				
		$result = array(
			"status" => "success",
			"data" => $courseIds
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

	function assignLecturers($data) {		
		if(!$data['lecturerIds']) {
			throwError("No lecturer selected.");
		}
		if(!$data['courseIds']) {
			throwError("No lecturer selected.");
		}
		foreach($data['lecturerIds'] as $lecturerId) {
			if(!lecturerExists($lecturerId)) {
				throwError("Lecturer not found. ID [" . $lecturerId . "]");
			}
			unassignLecturer($lecturerId);
			foreach($data['courseIds'] as $courseId) {
				if(!courseExists($courseId)) {
					throwError("Course not found. ID [" . $courseId . "]");
				}
				$result = pg_execute($GLOBALS['db'], "assign_lecturer", array(
					$lecturerId, $courseId
				));

				if(!$result) {
					pg_free_result($result);
					throwError("Failed to assign lecturer to course. " 
						. "[UserID: " . $lecturerId . " CourseID: " . $courseId . "]");
				}
			}
		}
		
		echo "success";
	}

	function unassignLecturer($lecturerId) {
		if(!lecturerExists($lecturerId)) {
			throwError("Lecturer not found. ID [" . $lecturerId . "]");
		}
		$result = pg_execute($GLOBALS['db'], "unassign_lecturer", array(
			$lecturerId
		));

		if(!$result) {
			pg_free_result($result);
			throwError("Failed to unassign lecturer. [UserID: " . $lecturerId . "]");
		}
	}

	function unassignLecturers($data) {
		foreach($data['lecturerIds'] as $lecturerId) {
			unassignLecturer($lecturerId);
		}
		
		echo "success";
	}

	/* ENROLLMENT */
	function loadEnrollments($courseId) {
		if(!$courseId) {
			throwError("No course selected.");
		}
		if(!courseExists($courseId)) {
			throwError("Course not found. [ID: " . $courseId . "]");
		}
		$results = pg_execute($GLOBALS['db'], "load_enrollments", array($courseId));
		
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

	function removeEnrollments($courseId) {
		if(!$courseId) {
			throwError("No course selected.");
		}
		if(!courseExists($courseId)) {
			throwError("Course not found. ID [" . $courseId . "]");
		}

		$result = pg_execute($GLOBALS['db'], "remove_enrollments", array(
			$courseId
		));

		if(!$result) {
			pg_free_result($result);
			throwError("Failed to delete course.");
		}
		pg_free_result($result);

		echo "success";
	}

	function insertEnrollments($courseId, $studentIds) {
		if(!$courseId) {
			throwError("No course selected.");
		}
		if(!courseExists($courseId)) {
			throwError("Course not found. ID [" . $courseId . "]");
		}
		if(!$studentIds) {
			throwError("No student IDs.");
		}

		foreach($studentIds as $studentId) {
			if(!studentEnrolled($courseId, $studentId)) {
				$result = pg_execute($GLOBALS['db'], "enroll_student", array(
					$courseId, $studentId
				));

				if(!$result) {
					pg_free_result($result);
					throwError("Failed to enroll student. ID [" . $studentId . "]");
				}
				pg_free_result($result);
			}
		}
	}

	function studentEnrolled($courseId, $studentId) {
		$result = pg_execute($GLOBALS['db'], "student_enrolled", array(
			$courseId, $studentId
		));
		$result = pg_fetch_array($result);

		if($result['student_enrolled']) {
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

		# USER ACCOUNTS
		$sql = "
			SELECT id, email, full_name, notes, user_type, last_modification::date
			FROM Webuser
		";
		$results[] = pg_prepare($GLOBALS['db'], "load_users", $sql);

		$sql = "
			SELECT COUNT(*) AS user_exists
			FROM Webuser
			WHERE id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "user_exists", $sql);

		$sql = "
			SELECT COUNT(*) AS email_used
			FROM Webuser
			WHERE email = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "email_used", $sql);

		$sql = "
			DELETE FROM Webuser
			WHERE id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "delete_user", $sql);

		$sql = "
			INSERT INTO Webuser (email, password, full_name, user_type, notes, last_modification)
			VALUES ($1, '', $2, $3, $4, now());
		";
		$results[] = pg_prepare($GLOBALS['db'], "create_user", $sql);

		$sql = "
			UPDATE Webuser
			SET
				full_name = $1,
				user_type = $2,
				notes = $3,
				last_modification = now()
			WHERE id = $4
		";
		$results[] = pg_prepare($GLOBALS['db'], "modify_user", $sql);

		# COURSES

		$sql = "
			SELECT id, course_code, title, notes, last_modification::date
			FROM Course
		";
		$results[] = pg_prepare($GLOBALS['db'], "load_courses", $sql);

		$sql = "
			SELECT COUNT(*) AS course_exists
			FROM Course
			WHERE id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "course_exists", $sql);

		$sql = "
			SELECT COUNT(*) AS course_code_used
			FROM Course
			WHERE course_code = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "course_code_used", $sql);

		$sql = "
			DELETE FROM Course
			WHERE id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "delete_course", $sql);

		$sql = "
			INSERT INTO Course (course_code, title, notes, last_modification)
			VALUES ($1, $2, $3, now());
		";
		$results[] = pg_prepare($GLOBALS['db'], "create_course", $sql);

		$sql = "
			UPDATE Course
			SET
				course_code = $1,
				title = $2,
				notes = $3,
				last_modification = now()
			WHERE id = $4
		";
		$results[] = pg_prepare($GLOBALS['db'], "modify_course", $sql);


		# LECTURERS
		$sql = "
			SELECT id, email, full_name
			FROM Webuser
			WHERE user_type = 'lecturer'
		";
		$results[] = pg_prepare($GLOBALS['db'], "load_lecturers", $sql);

		$sql = "
			SELECT course_id
			FROM Lecturer
			WHERE user_id = $1				
		";
		$results[] = pg_prepare($GLOBALS['db'], "courses_of_lecturer", $sql);

		$sql = "
			INSERT INTO Lecturer(user_id, course_id)
			VALUES($1, $2)							
		";
		$results[] = pg_prepare($GLOBALS['db'], "assign_lecturer", $sql);

		$sql = "
			SELECT COUNT(*) AS lecturer_exists
			FROM Webuser
			WHERE 
				id = $1
				AND user_type = 'lecturer'
		";
		$results[] = pg_prepare($GLOBALS['db'], "lecturer_exists", $sql);

		$sql = "
			DELETE FROM Lecturer
			WHERE user_id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "unassign_lecturer", $sql);

		/* enrollments */
		$sql = "
			SELECT id, student_id
			FROM Enrollment
			WHERE course_id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "load_enrollments", $sql);

		$sql = "
			DELETE FROM Enrollment
			WHERE course_id = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "remove_enrollments", $sql);

		$sql = "
			SELECT COUNT(*) as student_enrolled 
			FROM Enrollment
			WHERE course_id = $1
				AND student_id = $2
		";
		$results[] = pg_prepare($GLOBALS['db'], "student_enrolled", $sql);

		$sql = "
			INSERT INTO Enrollment(course_id, student_id)
			VALUES($1, $2)
		";
		$results[] = pg_prepare($GLOBALS['db'], "enroll_student", $sql);

		foreach($results as $result) {
			if(!$result) {
				error_log("Failed to prepare statement. SQL: " . $sql);
				throwError("Oops... something went wrong.");
			}
		}
	}
?>
