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
				LC.lecture_id AS lecture_id,
				L.course_id AS course_id
			FROM Lecture_Code AS LC
			JOIN Lecture AS L
				ON LC.lecture_id = L.id	
			WHERE LC.code = $1
		";

		$result = pg_prepare($GLOBALS['db'], "login_data", $sql);
		if(!$result) {
		  error_log("Failed to prepare statement");
		}
		$result = pg_execute($GLOBALS['db'], "login_data", array($_POST["code"]));
		$data = pg_fetch_array($result);
		pg_free_result($result);

		if($data) {
			$_SESSION['authenticated'] = true;
			$_SESSION['lecture_id'] = $data['lecture_id'];
			$_SESSION['course_id'] = $data['course_id'];
			$_SESSION['code'] = $_POST["code"];
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
		if(!($_SESSION['authenticated'] && checkTimeout())) {
			logout();
		}
	}

	function checkTimeout() {
		# Check for session timeout, else initiliaze time
		if (isset($_SESSION['timeout'])) {
			# Check Session Time for expiry
			$minutes = 150;
			$seconds = 0;
			if ($_SESSION['timeout'] + $minutes * 60 + $seconds < time()) {
				return false;
			} else {
				# refresh
				$_SESSION['timeout'] = time();
			}
		}
		else {
			# Initialize time
			$_SESSION['timeout'] = time();
		}
		return true;
	}

	function navigateBrowser($url) {
		header("Location: " . $url);
		exit();
	}
?>
