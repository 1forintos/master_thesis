<?php

	$host        = "host=127.0.0.1";
	$port        = "port=5432";
	$dbname      = "dbname=crm";
	$credentials = "user=crm password=crm";

	$db = pg_connect( "$host $port $dbname $credentials"  );
	if(!$db){
		error_log("Error : Unable to open database");
	} else {
		$GLOBALS['db'] = $db;
	}

?>
