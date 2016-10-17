<?php 
	include("server_script.php"); 

	chdir(getRootFolder());
	
	include("header/header_script.php"); 
	include("header/header_begin.php"); 
?>

<div id="content" class="container">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">Item Types</h3>
	  </div>
	  <div class="panel-body">
	    This section allows to list and edit available Item Types in the system.
	  </div>
	</div>

	<div class="panel panel-default">	  
	  <div class="panel-body">
		  	<div class="row">	  		
		     	<div class="col-sm-12 table-responsive">
			    	<table id="table-item_types" class="table table-striped table-bordered" cellspacing="0" width="100%">
			    		 <thead>
				            <tr>
				            	<th width="8%">#</th>
				                <th>Name</th>
				                <th width="15%">Quantity Unit</th>
				                <th>Notes</th>
				                <th width="18%">Timestamp</th>
				                <th width="5%">Edit</th>
				                <th width="8%">Delete</th>
				            </tr>
				       	 </thead>				       
				        <tbody/>		    		
			    	</table>
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
	        <h4 class="modal-title">Modify Item Type</h4>
	      </div>
	      <div class="modal-body">
	        <div class="row" class="input-container">
			    <div class="col-sm-6">
			     	<div class="input-group">
					  <span class="input-group-addon">Name:</span>
					  <span class="input-group-addon" id="input-name"></span>
					</div>
				</div>
				<div class="col-sm-6">
			     	<div class="input-group">
					  <span class="input-group-addon">Quantity Unit</span>
					  <input type="text" id="input-quantity_unit" class="form-control" aria-describedby="basic-addon3">
					</div>
				</div>
			</div>
			<div class="row" id="container-input-notes" class="input-container">
			    <div class="col-sm-12">
			     	<div class="input-group">
					  <span class="input-group-addon">Notes</span>
					  <input type="text" id="input-notes" class="form-control" aria-describedby="basic-addon3">
					</div>
				</div>				
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" id="popup-button-save" class="btn btn-success">Save changes</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>


<?php include("header/header_end.php"); ?>		