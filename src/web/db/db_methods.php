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
		}
	}

	function loadTableData($tableInfo) {
		$sql = null;
		if($tableInfo == "user_accounts") {
			$results = pg_execute($GLOBALS['db'], "get_users", array());
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

	function createUserAccount($accountData) {
		if(!filter_var($accountData['email'], FILTER_VALIDATE_EMAIL)) {
			throwError("Invalid E-mail format.");
		}
		if(userAccountExists($accountData['email'])) {
			throwError("Email [" . $accountData['email'] . "] already exists.");
		}

		$result = pg_execute($GLOBALS['db'], "create_user", array(
			$accountData['email'],
			$accountData['full_name'],
			$accountData['user_type'],
			$accountData['notes']
		));

		if(!$result) {
			pg_free_result($result);
			throwError("Failed to insert storage.");
		}

		echo "success";
	}

	function modifyUserAccount($accountData) {
		if(!filter_var($accountData['email'], FILTER_VALIDATE_EMAIL)) {
			throwError("Invalid E-mail format... I saw what you did there.. you fishy little you -.-");
		}
		if(!userAccountExists($accountData['email'])) {
			throwError("Account with E-mail [" . $accountData['email'] . "] does not exists.");
		}

		$result = pg_execute($GLOBALS['db'], "modify_user", array(
			$accountData['full_name'],
			$accountData['user_type'],
			$accountData['notes'],
			$accountData['email']
		));

		if(!$result) {
			pg_free_result($result);
			throwError("Failed to update user account.");
		}

		echo "success";
	}

	function deleteUserAccount($accountData) {
		if(!userAccountExists($accountData['email'])) {
			throwError("User with E-mail [" . $accountData['email'] . "] does not exists.");
		}

		$result = pg_execute($GLOBALS['db'], "delete_user", array(
			$accountData['email']
		));

		if(!$result) {
			pg_free_result($result);
			throwError("Failed to DELETE or NOT storage.");
		}

		echo "success";
	}

	function userAccountExists($email) {
		$result = pg_execute($GLOBALS['db'], "user_exists", array($email));
		$result = pg_fetch_array($result);
		error_log(print_r($result, true));

		if($result['user_exists']) {
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
			SELECT id, email, full_name, notes, user_type, timestamp::date
			FROM Webuser
		";
		$results[] = pg_prepare($GLOBALS['db'], "get_users", $sql);

		$sql = "
			SELECT COUNT(*) AS user_exists
			FROM Webuser
			WHERE email = $1
		";
		$results[] = pg_prepare($GLOBALS['db'], "user_exists", $sql);

		$sql = "
			DELETE FROM Webuser
			WHERE email = $1
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
			WHERE email = $4
		";
		$results[] = pg_prepare($GLOBALS['db'], "modify_user", $sql);

		foreach($results as $result) {
			if(!$result) {
				error_log("Failed to prepare statement. SQL: " . $sql);
				throwError("Oops... something went wrong.");
			}
		}
	}
?>
