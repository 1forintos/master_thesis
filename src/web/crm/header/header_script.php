<?php
	chdir(substr(__DIR__, 0, strpos(__DIR__, "/crm/") + 4));
	require_once "db/auth.php";
	require_once "init.php";

	authenticate();
	if(isset($_POST['value'])) {
		if($_POST['value'] == 'logout') {
			logout();
		} else if($_POST['value'] == 'navigate' && isset($_POST['where'])) {
			if($_POST['where'] == 'home') {
				$url = $GLOBALS['root'] . "Home";
				echo $url;
			}
		}
	}

	function console_log( $data ){
		echo '<script>';
		echo 'console.log('. json_encode( $data ) .')';
		echo '</script>';
	}

	function getModules() {
		$params = parse_ini_file("conf/modules.ini", true);
		$modulesToLoad =  array();
		$userType = $_SESSION['user_type'];

		foreach ($params as $moduleName => $subModules) {
			foreach ($subModules as $subModuleName => $subModule) {
				if(array_key_exists("users", $subModule) && strpos($subModule["users"], $userType) !== false) {
					if(!array_key_exists($moduleName, $modulesToLoad)) {
						$modulesToLoad[$moduleName] = array();
					}
					$dirSpecified = array_key_exists("dir", $subModule);
					$newSubmoduleToLoad = array(
						"path" =>  $dirSpecified ? $GLOBALS['root'] . str_replace("_", "", $moduleName) . "/" . $subModule["dir"] : $GLOBALS['root'] . str_replace("_", "", $moduleName),
						"name" => str_replace("_", " ", $subModuleName)
					);

					$modulesToLoad[$moduleName][] = $newSubmoduleToLoad;
				}
			}
		}
		return $modulesToLoad;
	}
?>
