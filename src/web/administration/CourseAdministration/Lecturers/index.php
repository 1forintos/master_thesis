<?php
	include("server_script.php");

	chdir(getRootFolder());

	include("header/header_script.php");
	include("header/header_begin.php");
?>

<div id="content" class="container" hidden>
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
					<select id="select-lecturers" class=" form-control" multiple="multiple"></select>
				</div>
				<div class="col-sm-6">
					<label for="courses" class="col-sm-6 control-label">Courses</label>
					<select id="select-courses" class="form-control courses"  multiple="multiple"></select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-5" id="button-unassign-container">
				<button type="button" data-toggle="modal" title="Unassign"
						class="btn btn-default glyphicon glyphicon-remove" id="button-unassign"><span style="margin-left: 0.5em;">Unassign</span></button>
			</div>
			<div class="col-xs-2"></div>
			<div class="col-xs-5" id="button-assign-container">
				<button type="button" data-toggle="modal" title="Assign"
						class="btn btn-default glyphicon glyphicon-ok" id="button-assign"><span style="margin-left: 0.5em;">Assign</span></button>
			</div>
		</div>
	</div>
</div>
<?php include("header/header_end.php"); ?>
