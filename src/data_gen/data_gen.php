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

	$sql = "
		INSERT INTO Measurement(type, value, lecture_id, timestamp)
		VALUES($1, $2, $3, $4)
	";
	$results[] = pg_prepare($GLOBALS['db'], "insert_measurement", $sql);

	$lectureId = 2;

	pg_query("BEGIN");

	$baseValue = 15;
	$type = "temperature";
	$numOfData = 1000;
	$add = true;
	$hour = 12;
	$minute = 0;
	$second = 0;

	for($i = 0; $i < $numOfData; $i++) {
		$random = rand(-6, 6);
		$randomFloat = rand(0, 10) / 10;
		$timestamp = "2016-09-02 " . $hour . ":" . $minute . ":" . $second;

		$newValue = $baseValue + $random + $randomFloat;
		
		$result = pg_execute($GLOBALS['db'], "insert_measurement", array(
			$type, $newValue, $lectureId, $timestamp
		));

		if(!$result) {
			echo "FAILED\n";
			pg_query("ROLLBACK");
			break;
		} else {
			echo "INSERTED DATA: Type [" . $type . "], Value [" . $newValue . 
			"], Lecture ID [" . $lectureId . "], Timestamp [" . $timestamp . "]\n";
		}

		$second += 6;
		if($second > 59) {
			$minute++;
			$second = 0;
			if($minute > 59) {
				$hour++;
				$minute = 0;
			}
		}
	}


	$type = "light";
	$baseValue = 60;
	$numOfData = 1000;
	$add = true;
	$hour = 12;
	$minute = 0;
	$second = 0;

	for($i = 0; $i < $numOfData; $i++) {
		$random = rand(-10, 10);
		$randomFloat = rand(0, 10) / 10;
		$timestamp = "2016-09-02 " . $hour . ":" . $minute . ":" . $second;

		$newValue = $baseValue + $random + $randomFloat;
		
		$result = pg_execute($GLOBALS['db'], "insert_measurement", array(
			$type, $newValue, $lectureId, $timestamp
		));

		if(!$result) {
			echo "FAILED\n";
			pg_query("ROLLBACK");
			break;
		} else {
			echo "INSERTED DATA: Type [" . $type . "], Value [" . $newValue . 
			"], Lecture ID [" . $lectureId . "], Timestamp [" . $timestamp . "]\n";
		}

		$second += 6;
		if($second > 59) {
			$minute++;
			$second = 0;
			if($minute > 59) {
				$hour++;
				$minute = 0;
			}
		}
	}

	pg_query("END");
	pg_query("COMMIT");
	pg_close($GLOBALS['db']);

?>