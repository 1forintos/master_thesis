<?php 
	include("server_script.php"); 

	chdir(getRootFolder());
	
	include("header/header_script.php"); 
	include("header/header_begin.php"); 
?>

<div id="content" class="container">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">Storages</h3>
	  </div>
	  <div class="panel-body">
	    This section allows you to list, edit the items and their amounts stored in Storages.
	  </div>
	</div>

	<div class="panel panel-default">	
	<div class="panel-heading">
	    <h3 class="panel-title">Stored items</h3>
	  </div>  
	  <div class="panel-body">
		  	<div class="row">	  		
		     	<div class="col-sm-12 table-responsive">
			    	<table id="table-stored_items" class="table table-striped table-bordered" cellspacing="0" width="100%">
			    		 <thead>
				            <tr>
				            	<th width="5%">#</th>
				            	<th>Storage</th>
				                <th width="15%">Item type</th>
				                <th width="10%">Quantity</th>
				                <th>Unit</th>
				                <th width="18%">Timestamp</th>
				                <th width="5%">Edit</th>
				                <th width="8%">Delete</th>
				            </tr>
				       	 </thead>				       
				        <tbody/>		    		
			    	</table>
			    </div>
		  	</div>
		  	<div class="row">
		     	<div class="col-sm-11"></div>
		     	<div class="col-sm-1" id="button-add-container">
		     		<button type="button" data-toggle="modal" title="Add new item" data-target="#popup-add-item"
		      			class="btn btn-default glyphicon glyphicon-plus" id="button-add-item" ></button>
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
	        <h4 class="modal-title">Modify</h4>
	      </div>
	      <div class="modal-body">
	        <div class="row" class="input-container" id="popup-name-container">
			    <div class="col-sm-12">
			     	<div class="input-group">
					  <span class="input-group-addon">Name:</span>
					  <span class="input-group-addon" id="text-name"></span>
					</div>
				</div>
			</div>
			<div class="row" class="input-container">
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
	        <button type="button" id="popup-button-save" class="btn btn-success">Save changes</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<div id="popup-add-item" class="modal fade" tabindex="-1" role="dialog">
	<div class="container">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Add item to storage</h4>
	      </div>
	      <div class="modal-body">
	     	<div class="row" class="input-container" id="container-input-storage">
			    <div class="col-sm-12">
			     	<div class="input-group">
					  <span class="input-group-addon">Storage</span>
					  <select id="select-storage" name="select-storage" aria-describedby="basic-addon3" class="form-control selectpicker selectpicker-default select-storage" data-live-search="true" data-size="5">
					  </select>
					</div>
				</div>
			</div>
	        <div class="row" class="input-container" id="container-input-item_type">
			    <div class="col-sm-12">
			     	<div class="input-group">
					  <span class="input-group-addon">Item type</span>
					  <select id="select-item_type" name="select-item_type" aria-describedby="basic-addon3" class="form-control selectpicker selectpicker-default select-item_type" data-live-search="true" data-size="5">
					  </select>
					</div>
				</div>
			</div>
			<div class="row" class="input-container" id="container-input-quantity">
				<div class="col-sm-12">
			     	<div class="input-group">
					  <span class="input-group-addon">Quantity</span>
					  <input type="text" id="input-new-quantity" class="form-control" aria-describedby="basic-addon3">
					  <span class="input-group-addon" id="text-quantity_unit"></span>
					</div>
				</div>
			</div>			
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" id="popup-button-save-item" class="btn btn-success">Save changes</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>

<?php include("header/header_end.php"); ?>		