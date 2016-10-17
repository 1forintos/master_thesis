<?php
	include("server_script.php");

	chdir(getRootFolder());

	include("header/header_script.php");
	include("header/header_begin.php");
?>

<div id="content" class="container">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">Sample submodule title</h3>
	  </div>
	  <div class="panel-body">
	    Sample submodule content
	  </div>
	</div>
</div>

<?php include("header/header_end.php"); ?>
