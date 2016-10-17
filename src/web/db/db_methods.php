<?php
	require_once "db_init.php";
	require_once "auth.php";

	prepareStatements();

	authenticate();

	if(isset($_POST['method'])) {
		if($_POST['method'] == "loadTable") {
			loadTable($_POST['tableName']);
		} else if($_POST['method'] == "loadTableData") {
			loadTableData($_POST['data']);
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
		}
	}

	function loadTableData($tableInfo) {
		$sql = null;
		if($tableInfo == "user_accounts") {
			$results = pg_execute($GLOBALS['db'], "get_users", array());
		} else {
			if($tableInfo == "courses") {
				$results = pg_execute($GLOBALS['db'], "get_courses", array());
			}
		}

		$tableData = array();
		while($row = pg_fetch_array($results)) {
			$tableData[] = array();
			foreach($row as $key => $value) {
				$tableData[count($tableData) - 1][$key] = utf8_encode($value);
			}
		}

		pg_free_result($results);

		$result = array(
			"status" => "success",
			"data" => $tableData
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
			SELECT id, email, full_name, notes, user_type, timestamp::date
			FROM Webuser
		";
		$results[] = pg_prepare($GLOBALS['db'], "get_users", $sql);

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
			INSERT INTO Webuser (email, password, full_name, user_type, notes, timestamp)
			VALUES ($1, '', $2, $3, $4, now());
		";
		$results[] = pg_prepare($GLOBALS['db'], "create_user", $sql);

		$sql = "
			UPDATE Webuser
			SET
				full_name = $1,
				user_type = $2,
				notes = $3,
				timestamp = now()
			WHERE id = $4
		";
		$results[] = pg_prepare($GLOBALS['db'], "modify_user", $sql);

		# COURSES

		$sql = "
			SELECT id, course_code, title, notes, timestamp::date
			FROM Course
		";
		$results[] = pg_prepare($GLOBALS['db'], "get_courses", $sql);

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
			INSERT INTO Course (course_code, title, notes, timestamp)
			VALUES ($1, $2, $3, now());
		";
		$results[] = pg_prepare($GLOBALS['db'], "create_course", $sql);

		$sql = "
			UPDATE Course
			SET
				course_code = $1,
				title = $2,
				notes = $3,
				timestamp = now()
			WHERE id = $4
		";
		$results[] = pg_prepare($GLOBALS['db'], "modify_course", $sql);


		foreach($results as $result) {
			if(!$result) {
				error_log("Failed to prepare statement. SQL: " . $sql);
				throwError("Oops... something went wrong.");
			}
		}
	}
?>
