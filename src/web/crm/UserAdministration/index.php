<?php
	include("server_script.php");

	chdir(getRootFolder());

	include("header/header_script.php");
	include("header/header_begin.php");
?>

<div id="content" class="container" hidden>
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">Users</h3>
	  </div>
	  <div class="panel-body">
	    This section allows you to create, edit and delete User Accounts.
	  </div>
	</div>

	<div class="panel panel-default">
	<div class="panel-heading">
	    <h3 class="panel-title">Users</h3>
	  </div>
	  <div class="panel-body">
		  	<div class="row">
		     	<div class="col-sm-12 table-responsive">
			    	<table id="table-user_accounts" class="table table-striped table-bordered" cellspacing="0" width="100%">
			    		 <thead>
			            <tr>
			            	<th width="5%">#</th>
		                <th width="15%">E-mail</th>
		                <th width="25%">Full Name</th>
		                <th width="23%">Notes</th>
		                <th width="5%">User Type</th>
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
		     		<button type="button" data-toggle="modal" title="Create User Account" data-target="#popup-add-new"
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
	        <h4 class="modal-title">Modify User Account</h4>
	      </div>
	      <div class="modal-body">
	        <div class="row input-container popup-row" id="popup-email-container">
			    	<div class="col-sm-12">
				     	<div class="input-group">
							  <span class="input-group-addon">E-mail:</span>
								<input disabled type="text" id="input-edit-email" class="form-control" aria-describedby="basic-addon3">
							</div>
						</div>
					</div>
					<div class="row input-container popup-row" id="popup-full_name-container">
						<div class="col-sm-12">
							<div class="input-group">
								<span class="input-group-addon">Full Name:</span>
								<input type="text" id="input-edit-full_name" class="form-control" aria-describedby="basic-addon3">
							</div>
						</div>
					</div>
					<div class="row input-container popup-row" id="popup-notes-container">
			    	<div class="col-sm-12">
				     	<div class="input-group">
							  <span class="input-group-addon">Notes:</span>
								<input type="text" id="input-edit-notes" class="form-control" aria-describedby="basic-addon3">
							</div>
						</div>
					</div>
					<div class="row input-container popup-row" id="popup-notes-container">
			    	<div class="col-sm-12">
				     	<div class="input-group">
							  <span class="input-group-addon">User Type:</span>
								<select id="select-edit-user_type" name="select-user_type" aria-describedby="basic-addon3" class="form-control selectpicker selectpicker-default select-user_type" data-size="5">
									<option value="dev">Developer</option>
									<option value="user_admin">User Administrator</option>
									<option value="course_admin">Course Administrator</option>
									<option value="lecturer">Lecturer</option>
							  </select>
							</div>
						</div>
					</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" id="popup-button-save-modifications" class="btn btn-success">Save changes</button>
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
	        <h4 class="modal-title">Create User Account</h4>
	      </div>
	      <div class="modal-body">
		     	<div class="row popup-row" class="input-container">
				    <div class="col-sm-12">
							<div class="input-group">
							  <span class="input-group-addon">E-mail</span>
								<input type="text" id="input-new-email" class="form-control" aria-describedby="basic-addon3">
							</div>
						</div>
					</div>
	        <div class="row popup-row" class="input-container">
				    <div class="col-sm-12">
				     	<div class="input-group">
							  <span class="input-group-addon">Full Name</span>
							  <input type="text" id="input-new-full_name" class="form-control" aria-describedby="basic-addon3">
							</div>
						</div>
					</div>
					<div class="row popup-row" class="input-container">
				    <div class="col-sm-12">
							<div class="input-group">
							  <span class="input-group-addon">Notes</span>
								<input type="text" id="input-new-notes" class="form-control" aria-describedby="basic-addon3">
							</div>
						</div>
					</div>
					<div class="row popup-row" class="input-container">
				    <div class="col-sm-12">
							<div class="input-group">
							  <span class="input-group-addon">User Type</span>
								<select id="select-user_type" name="select-user_type" aria-describedby="basic-addon3" class="form-control selectpicker selectpicker-default select-item_type" data-size="5">
									<option value="dev">Developer</option>
									<option value="user_admin">User Administrator</option>
									<option value="course_admin">Course Administrator</option>
									<option value="lecturer">Lecturer</option>
							  </select>
							</div>
						</div>
					</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" id="popup-button-close-new" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" id="popup-button-save-new" class="btn btn-success">Create</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<?php include("header/header_end.php"); ?>
