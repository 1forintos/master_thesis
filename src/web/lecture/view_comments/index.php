<?php
	require_once "../db/auth.php";
	authenticate();
?>

<!DOCTYPE html>
<html>

<head>
	<title>Comments</title>

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

<body onload="init()">
	<div id="content" class="container container-fluid">
		<div class="panel panel-default" id="content-panel">
			<div class="panel-heading">
				<h1 class="panel-title">Comments</h1>
			</div>
			<div class="panel-body"  id="content-body">
				<div id="comments"></div>
			</div>
		</div>
	</div>
</body>

</html>