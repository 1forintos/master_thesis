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

	$sql = "
		INSERT INTO Feedback(lecture_id, question_id, feedback, timestamp)
		VALUES($1, $2, $3, $4)
	";
	$results[] = pg_prepare($GLOBALS['db'], "insert_feedback", $sql);

	$lectureId = 1;

	pg_query("BEGIN");

	$baseValue = 15;
	$type = "temperature";
	$numOfData = 1000;
	$hour = 14;
	$minute = 2;
	$second = 4;

	for($i = 0; $i < $numOfData; $i++) {
		$random = rand(-6, 6);
		$randomFloat = rand(0, 10) / 10;
		$timestamp = "2016-11-09 " . $hour . ":" . $minute . ":" . $second;

		$newValue = $baseValue + $random + $randomFloat;

		$result = pg_execute($GLOBALS['db'], "insert_measurement", array(
			$type, $newValue, $lectureId, $timestamp
		));

		if(!$result) {
			echo "FAILED\n";
			pg_query("ROLLBACK");
			break;
		} else {
			//echo "INSERTED DATA: Type [" . $type . "], Value [" . $newValue .
			//"], Lecture ID [" . $lectureId . "], Timestamp [" . $timestamp . "]\n";
			echo "inserted measurement.\n";
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

	$type = "brightness";
	$baseValue = 60;
	$numOfData = 1000;
	$hour = 12;
	$minute = 3;
	$second = 2;

	for($i = 0; $i < $numOfData; $i++) {
		$random = rand(-5, 5);
		$randomFloat = rand(0, 10) / 10;
		$timestamp = "2016-11-09 " . $hour . ":" . $minute . ":" . $second;

		$newValue = $baseValue + $random + $randomFloat;

		$result = pg_execute($GLOBALS['db'], "insert_measurement", array(
			$type, $newValue, $lectureId, $timestamp
		));

		if(!$result) {
			echo "FAILED\n";
			pg_query("ROLLBACK");
			break;
		} else {
			//echo "INSERTED DATA: Type [" . $type . "], Value [" . $newValue .
			//"], Lecture ID [" . $lectureId . "], Timestamp [" . $timestamp . "]\n";
			echo "inserted measurement.\n";
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

	$numOfData = 1000;
	$hour = 12;
	$minute = 0;
	$second = 7;
	for($i = 0; $i < $numOfData; $i++) {
		$random = rand(1, 10);
		$newValue = rand(1, 10);
		$timestamp = "2016-11-09 " . $hour . ":" . $minute . ":" . $second;

		$questionId = 7;
		$result = pg_execute($GLOBALS['db'], "insert_feedback", array(
			$lectureId, $questionId, $newValue, $timestamp
		));

		$newValue = rand(1, 10);
		$questionId = 8;
		$result = pg_execute($GLOBALS['db'], "insert_feedback", array(
			$lectureId, $questionId, $newValue, $timestamp
		));
		$newValue = rand(1, 10);
		$questionId = 9;
		$result = pg_execute($GLOBALS['db'], "insert_feedback", array(
			$lectureId, $questionId, $newValue, $timestamp
		));
		if(!$result) {
			echo "FAILED\n";
			pg_query("ROLLBACK");
			break;
		} else {
			echo "inserted feedback.\n";
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
