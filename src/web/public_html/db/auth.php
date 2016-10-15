<?php
	chdir(substr(__DIR__, 0, strpos(__DIR__, "/sm/") + 3));
	require_once "db/db_init.php";
	require_once "init.php";

	session_start();

	function login() {
		$ps = $GLOBALS['pdo']->prepare("
			SELECT
				COUNT(*) AS user_found,
				user_type,
				user_id
			FROM `Users`
			WHERE login_name = ?
				AND password = ?
		");

		$userName = $_POST["user"];
		$password = $_POST["pass"];
		$ps->execute(array($userName, $password));
		$ps->setFetchMode(PDO::FETCH_OBJ);
		$result = $ps->fetch();
		if($result->user_found) {
			$_SESSION['authenticated'] = true;
			$_SESSION['timeout'] = time();
			$_SESSION['user_type'] = $result->user_type;
			$_SESSION['user_id'] = $result->user_id;
			$url = $GLOBALS['root'] . "Home";
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
		if(!($_SESSION['authenticated'] && checkTimeout())) {
			logout();
		}
	}

	function checkTimeout() {
		# Check for session timeout, else initiliaze time
		if (isset($_SESSION['timeout'])) {
			# Check Session Time for expiry
			$minutes = 10;
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
