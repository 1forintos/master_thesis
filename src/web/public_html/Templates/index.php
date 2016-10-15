<?php 
	include("server_script.php"); 

	chdir(getRootFolder());
	
	include("header/header_script.php"); 
	include("header/header_begin.php"); 
?>

<div id="content" class="container">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">Storage Templates</h3>
	  </div>
	  <div class="panel-body">
	    This section allows you to list and modify available Storage Templates and the corresponding items list.
	  </div>
	</div>

	<div class="panel panel-default">
		<div class="panel-heading">
	    <h3 class="panel-title">General</h3>
	  </div>	  
	  <div class="panel-body">
		  	<div class="row">	  		
		     	<div class="col-sm-12 table-responsive">
			    	<table id="table-templates" class="table table-striped table-bordered" cellspacing="0" width="100%">
			    		 <thead>
				            <tr>
				            	<th width="5%">#</th>
				                <th>Name</th>
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

  	<div class="panel panel-default">
		<div class="panel-heading">
	    <h3 class="panel-title">Default template items</h3>
	  </div>	  
	  <div class="panel-body">
		  	<div class="row">	  		
		     	<div class="col-sm-12 table-responsive">
			    	<table id="table-template-items" class="table table-striped table-bordered" cellspacing="0" width="100%">
			    		 <thead>
				            <tr>
				            	<th width="5%">#</th>
				                <th>Template</th>
				                <th>Item Type</th>				                
				                <th width="18%">Quantity</th>
				                <th width="5%">Unit</th>
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

<div id="popup-edit-template" class="modal fade" tabindex="-1" role="dialog">
	<div class="container">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Modify Template</h4>
	      </div>
	      <div class="modal-body">
	        <div class="row" class="input-container">
			    <div class="col-sm-12">
			     	<div class="input-group">
					  <span class="input-group-addon">Name</span>
					  <input type="text" id="input-name" class="form-control" aria-describedby="basic-addon3">
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
	        <button type="button" id="popup-button-save-template" class="btn btn-success">Save changes</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<div id="popup-edit-template-item" class="modal fade" tabindex="-1" role="dialog">
	<div class="container">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Modify</h4>
	      </div>
	      <div class="modal-body">
	     	<div class="row" class="input-container" id="popup-name-container">
			    <div class="col-sm-12">
			     	<div class="input-group">
					  <span class="input-group-addon">Template:</span>
					  <span class="input-group-addon" id="text-template"></span>
					</div>
				</div>
			</div>
	        <div class="row" class="input-container" id="container-text-name">
			    <div class="col-sm-12">
			     	<div class="input-group">
					  <span class="input-group-addon">Item name:</span>
					  <span class="input-group-addon" id="text-item_name"></span>
					</div>
				</div>
			</div>
			<div class="row" class="input-container" id="container-input-quantity">
				<div class="col-sm-12">
			     	<div class="input-group">
					  <span class="input-group-addon">Quantity</span>
					  <input type="text" id="input-quantity" class="form-control" aria-describedby="basic-addon3">
					  <span class="input-group-addon" id="text-quantity_unit"></span>
					</div>
				</div>
			</div>			
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" id="popup-button-save-template-item" class="btn btn-success">Save changes</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<?php include("header/header_end.php"); ?>		