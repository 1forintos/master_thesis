<?php
	chdir(substr(__DIR__, 0, strpos(__DIR__, "/feedback/") + 9));
	require_once "db/auth.php";
	require_once "init.php";
	require_once "db/db_methods.php";
	authenticate();
	toggleAttendance();
?>

<!DOCTYPE html>
<html>

<head>
	<title>Comment</title>

	<link rel="stylesheet" href="css/styles.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
		crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
		crossorigin="anonymous">

	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
		crossorigin="anonymous"></script>
	<script src="js/script.js" type="text/javascript"></script>
</head>

<body>
	<div id="input-container">
		<input id="input-comment" class="form-control input-lg" type="text" 
			placeholder="Comment..." onkeypress="onkey(event)">
	</div>
	<div class="button-container">
		<button id="button-send" type="button" class="btn btn-primary"><span id="button-send-text">Send</span></button>
	</div>
	<div class="button-container">
		<a href="/feedback/evaluate/"><button id="button-evaluation" type="button" class="btn btn-primary glyphicon "><span id="button-evaluation-text">Evaluation<span class="glyphicon-chevron-right"></span></span></button></a>
	</div>
</body>

</html>