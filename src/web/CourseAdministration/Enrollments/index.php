<?php
	include("server_script.php");

	chdir(getRootFolder());

	include("header/header_script.php");
	include("header/header_begin.php");
?>

<div id="content" class="container" hidden>
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">Enrollments</h3>
	  </div>
	  <div class="panel-body">
	    This section allows to remove, view and upload list of students for the selected course.
	  </div>
	</div>

	<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Course Enrollments</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-1 col-md-1 col-sm-offset-2  col-md-offset-3" id="label-container">
				<label for="select-course" class="control-label">Course:</label>
			</div>
			<div class="col-sm-7 col-md-5">
				<select id="select-course" class="selectpicker form-control" data-live-search="true">
				</select>
			</div>
		</div>
		<div class="row form-group" id="buttons-container">
			<div class="col-xs-10  col-md-2 col-lg-2 col-xs-offset-1 col-sm-offset-1 col-md-offset-3">
				<button type="button" data-toggle="modal" data-target="#confirm-remove" id="button-remove" 
						class="btn btn-default glyphicon glyphicon-remove form-control"> Remove</button>				 
			</div>
			<div class="col-xs-10 col-md-2 col-lg-2 col-xs-offset-1 col-sm-offset-1 col-md-offset-0">
				<button type="button" data-toggle="modal" title="Enrollment" data-target="#popup-view_enrollment"  id="button-view"
						class="btn btn-default glyphicon glyphicon-search form-control"> View</button>					 
			</div>
			<div class="col-xs-10 col-md-2 col-lg-2 col-xs-offset-1 col-sm-offset-1 col-md-offset-0">
				<button type="button" data-toggle="modal" title="Upload List" data-target="#popup-upload-file" id="button-upload "
						class="btn btn-default glyphicon glyphicon-open form-control"> Upload</button>
			</div>
		</div>
	</div>
</div>

<div id="popup-view_enrollment" class="modal fade" tabindex="-1" role="dialog">
	<div class="container">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Enrollment</h4>
	      </div>
	      <div class="modal-body">
	        <div class="row">
						<div class="col-sm-12 table-responsive">
							<table id="table-students" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th width="5%"></th>
										<th>Student ID</th>
									</tr>
								</thead>
								<tbody/>
							</table>
						</div>
					</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>	        
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<div class="modal fade" id="confirm-remove" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Confirm Removal</h4>
			</div>
	
			<div class="modal-body">
					<p>You are about to remove enrollment information of the selected course.</p>
			</div>
			
			<div class="modal-footer">
					<button id="button-cancel_confirmation" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<a class="btn btn-danger btn-ok">Proceed</a>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="popup-upload-file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Upload List</h4>
			</div>
	
			<div class="modal-body">
					<p>Please select a file containing a list of student IDs then press the Upload button to enroll students in the selecte course.</p>										
					<input id="input-file-enrollment" name="file-input[]" type="file" class="file-loading" data-allowed-file-extensions='["json"]'>
			</div>			
		</div>
	</div>
</div>

<?php include("header/header_end.php"); ?>
