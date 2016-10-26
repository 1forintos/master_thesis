<?php
	require_once "../db/auth.php";
	authenticate();
?>

<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Start Lecture</title>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
		crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
		crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="css/styles.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
		crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
	
	<script src="js/script.js"></script>
</head>

<body>
	<div hidden id="content" class="container">
		<div class="row">
			<label>Select a course to start/stop a lecture:</label>
		</div>
		<div class="row">
			<select id="select-course" class="selectpicker" ></select>
		</div>
		<hr>
		<div class="row">
			<button id="button-stop-lecture" type="button" class="control-button col-xs-5 col-md-3 btn btn-default"><span class="button-text">Stop</span></button>
			<div class="col-xs-2 col-md-6"></div>
			<button id="button-start-lecture" type="button" class="control-button col-xs-5 col-md-3 btn btn-default"><span class="button-text">Start</span></button>
		</div>
		<hr>
			<a href="/lecture/view_comments/" ><button id="button-comment" type="button" 
				class="control-button btn btn-default"><span class="button-text">Comments</span></button></a>
	</div>
</body>
</html>