<?php
	chdir(substr(__DIR__, 0, strpos(__DIR__, "/administration/") + 15));
	include("header/header_script.php");
	include("header/header_begin.php");
?>

<div id="content" class="container">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">Home</h3>
	  </div>
	  <div class="panel-body">
	    Welcome to the Course and Room Management System! Please use the navigation bar to find the desired functionality.
	  </div>
	</div>
</div>
<?php include("header/header_end.php"); ?>
