<?php
	include("server_script.php");

	chdir(getRootFolder());

	include("header/header_script.php");
	include("header/header_begin.php");
?>

<div id="content" class="container">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">Lecturers</h3>
	  </div>
	  <div class="panel-body">
	    This section allows you to assign Lecturerers to Courses.
	  </div>
	</div>

	<div class="panel panel-default">
	<div class="panel-heading">
	    <h3 class="panel-title">Assignments</h3>
	  </div>
	  <div class="panel-body">
				<div class="row">
					<div class="form-group">
						<div class="col-sm-6">
							<label for="courses" class="col-sm-6 control-label">Lecturers</label>							
							<select class="visible-md visible-lg form-control select-bigger" id="select-lecturers" multiple="multiple"></select>  
							<select class="visible-xs form-control lecturers select-smaller" id="select-lecturers" multiple="multiple"></select>          						
						</div>
						<div class="col-sm-6">
							<label for="courses" class="visible-md visible-lg col-sm-6 control-label">Courses</label>
							<label for="courses" class="visible-xs col-sm-6 control-label label-smaller">Courses</label>
								<select id="select-courses" class="visible-md visible-lg form-control courses select-bigger"  multiple="multiple"></select>     
								<select id="select-courses" class="visible-xs form-control courses select-smaller" multiple="multiple"></select>         						
							</div>
						</div>		  
					</div>
  	</div>
</div>
<?php include("header/header_end.php"); ?>
