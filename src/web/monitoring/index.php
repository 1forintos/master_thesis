<?php
	require_once "db/db_methods.php";
?>

<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Monitoring</title>
	
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

	<script src="https://code.highcharts.com/highcharts.js"></script>
	<script src="https://code.highcharts.com/modules/exporting.js"></script>

	<script src="js/script.js"></script>
</head>

<body>
	<div hidden id="content" class="container">
		<div class="row">
			<label id="label-main">Please select a lecture of a course to monitor collected data</label>
		</div>
		<div class="row">
			<label for="select-course" class="label-secondary">Course:</label>
			<select id="select-course" class="selectpicker col-lg-6 col-md-8 col-sm-10 col-xs-12"></select>
		</div>
		<div class="row">
			<label for="select-lecture" class="label-secondary">Lecture:</label>
			<select id="select-lecture" class="selectpicker col-lg-6 col-md-8 col-sm-10 col-xs-12"></select>
		</div>
		<hr>
		<div class="row">
			<label for="select-question" id="label-question">Question:</label>
			<select id="select-question" class="selectpicker col-lg-6 col-md-8 col-sm-10 col-xs-12"></select>
		</div>
		<div class="row">
			<div id="checkbox-container" class="col-sm-3 col-sm-offset-3">
				<div class="checkbox">
					<label><input type="checkbox" value="">Temperature</label>
				</div>
				<div class="checkbox" class="col-md-3 col-md-5">
					<label><input type="checkbox" value="">Brightness</label>
				</div>
			</div>
			<div id="button-container">
				<button id="button-view" type="button" 
					class="col-xs-4 col-md-3 col-lg-3 btn btn-default"><span class="button-text">View</span></button>
			</div>
		</div>
			
		</div>
		<div hidden id="temperature-container">
			<hr>
			<div class="row">
				<div id="chart-temperature" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
			</div>
		</div>
		<div hidden id="brightness-container">
			<hr>
			<div class="row">
				<div id="chart-brightness" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
			</div>
		</div>
	</div>
</body>
</html>