<?php
	include("server_script.php");

	chdir(getRootFolder());

	include("header/header_script.php");
	include("header/header_begin.php");
?>

<div id="content" class="container">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">Questions</h3>
	  </div>
	  <div class="panel-body">
	    This section allows you to add new or edit/delete existing Questions of Courses.
	  </div>
	</div>

	<div class="panel panel-default">
	  <div class="panel-body">
			<div class="row">
				<div class="col-sm-2 col-md-2 col-sm-offset-2  col-md-offset-3" id="label-container">
					<label id="label-select-course" for="select-course" class="control-label">Please select a Course:</label>
				</div>
				<div class="col-sm-7 col-md-5">
					<select id="select-course" class="selectpicker form-control" data-live-search="true">
					</select>
				</div>
			</div>
			<div class="h-divider"></div>
			<div class="row">
				<div class="col-sm-12 table-responsive">
					<table id="table-questions" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
									<tr>
										<th width="5%">#</th>
										<th>Question Text</th>
										<th width="5%">Last Modification</th>
										<th width="5%">Edit</th>
										<th width="5%">Delete</th>
									</tr>
								</thead>
							<tbody/>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-11"></div>
				<div class="col-sm-1" id="button-add-container">
					<button type="button" data-toggle="modal" title="Add Question" data-target="#popup-add-new"
							class="btn btn-default glyphicon glyphicon-plus" id="button-add-new" ></button>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="popup-edit" class="modal fade" tabindex="-1" role="dialog">
	<div class="container">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Edit Question</h4>
	      </div>
	      <div class="modal-body">
	        <div class="row input-container popup-row" id="popup-course_code-container">
			    	<div class="col-sm-12">
				     	<div class="input-group">
							  <span class="input-group-addon">Question Text:</span>
								<input type="text" id="input-edit-question_text" class="form-control" aria-describedby="basic-addon3">
							</div>
						</div>
					</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" id="popup-button-save-modifications" class="btn btn-success">Save</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<div id="popup-add-new" class="modal fade" tabindex="-1" role="dialog">
	<div class="container">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Add New Question</h4>
	      </div>
	      <div class="modal-body">
		     	<div class="row popup-row" class="input-container">
				    <div class="col-sm-12">
							<div class="input-group">
							  <span class="input-group-addon">Question Text</span>
								<input type="text" id="input-new-question_text" class="form-control" aria-describedby="basic-addon3">
							</div>
						</div>
					</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" id="popup-button-close-new" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" id="popup-button-save-new" class="btn btn-success">Add</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<?php include("header/header_end.php"); ?>
