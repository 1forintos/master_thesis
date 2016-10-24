<?php
	chdir(substr(__DIR__, 0, strpos(__DIR__, "/feedback/") + 9));
	require_once "db/db_init.php";
	require_once "init.php";

	session_start();

	function login() {
/*
		$sql = "
		  SELECT
		    COUNT(*) AS user_found,
		    user_type,
		    id
		  FROM Webuser
		  WHERE email = $1
		    AND password = $2
		  GROUP BY id
		";

		$result = pg_prepare($GLOBALS['db'], "login_data", $sql);
		if(!$result) {
		  error_log("Failed to prepare statement");
		}
		$result = pg_execute($GLOBALS['db'], "login_data", array($_POST["email"], $_POST["pass"]));
		$user = pg_fetch_array($result);
		pg_free_result($result);
*/
		//if($user['user_found']) {
			$_SESSION['authenticated'] = true;
			$_SESSION['timeout'] = time();
			//$_SESSION['user_type'] = $user['user_type'];
			//$_SESSION['user_id'] = $user['id'];
			$url = $GLOBALS['root'] . "comment";
			navigateBrowser($url);
		//} else {
		//	logout();
		//}
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
			$minutes = 30;
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
