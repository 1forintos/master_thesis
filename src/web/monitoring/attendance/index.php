<?php
	require_once "../db/db_methods.php";
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
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.10/css/dataTables.bootstrap.min.css">

	<link rel="stylesheet" href="css/styles.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
		crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.10/js/dataTables.bootstrap.min.js"></script>

	<script src="js/script.js"></script>
</head>

<body>
	<div hidden id="content" class="container">
		<label id="label-main">Attendance</label>
		<div class="row">
			<label for="select-course" class="label-secondary">Course:</label>
			<select id="select-course" class="selectpicker col-lg-6 col-md-8 col-sm-10 col-xs-12" data-live-search="true"></select>
		</div>
		<div class="row">
			<label for="select-lecture" class="label-secondary">Lecture:</label>
			<select id="select-lecture" class="selectpicker col-lg-6 col-md-8 col-sm-10 col-xs-12" data-live-search="true"></select>
		</div>
		<hr>
		<div id="attencance-container">
			<div class="panel panel-default col-sm-6 col-sm-offset-3">
	  			<div class="panel-body">
						<div class="table-responsive">
							<table id="table-attendance" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th width="65%">Student ID</th>
										<th width="35%">Attended</th>
									</tr>
									</thead>
								<tbody/>
							</table>
						</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>