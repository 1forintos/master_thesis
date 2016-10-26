<?php
	chdir(substr(__DIR__, 0, strpos(__DIR__, "/feedback/") + 9));
	require_once "db/db_init.php";
	require_once "init.php";

	session_start();

	function login() {
		if(!isset($_POST["code"])) {
			logout();
		}

		$sql = "
		  SELECT
		    COUNT(*) AS code_found,
				course_id
		  FROM Lecture_Code
		  WHERE code = $1
		  GROUP BY id
		";

		$result = pg_prepare($GLOBALS['db'], "login_data", $sql);
		if(!$result) {
		  error_log("Failed to prepare statement");
		}
		$result = pg_execute($GLOBALS['db'], "login_data", array($_POST["code"]));
		$data = pg_fetch_array($result);
		pg_free_result($result);

		if($data['code_found']) {
			$_SESSION['authenticated'] = true;
			$_SESSION['course_id'] = $data['course_id'];
			$url = $GLOBALS['root'] . "evaluate";
			navigateBrowser($url);
		} else {
			logout();
		}
	}

	function logout() {
		session_destroy();
		$url = $GLOBALS['root'] . "Login";
		navigateBrowser($url);
	}

	function authenticate() {
		if(!isset($_SESSION['authenticated'])) {
			logout();
		}
		if(!($_SESSION['authenticated'])) {
			logout();
		}
	}

	function navigateBrowser($url) {
		header("Location: " . $url);
		exit();
	}
?>
