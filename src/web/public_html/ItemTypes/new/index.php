<?php 
	include("server_script.php"); 

	chdir(getRootFolder());
	
	include("header/header_script.php"); 
	include("header/header_begin.php"); 
?>

<div id="content" class="container">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">New Item Type</h3>
	  </div>
	  <div class="panel-body">
	    This section allows you to create a new item types that can be used in Storages and Storage Templates. 	    
	  </div>
	</div>

	<div class="panel panel-default">	  
	  <div class="panel-body">
	  	<div class="row section-label">	  		
	     	<div class="col-sm-12">
		      <span>Attributes</span> 
		    </div>
	  	</div>
	    <div class="row" id="general-input-container" class="input-container">
		    <div class="col-sm-4">
		     	<div class="input-group">
				  <span class="input-group-addon">Item name</span>
				  <input type="text"  id="input-name" class="form-control" aria-describedby="basic-addon3">
				</div>
		    </div>
		    <div class="col-sm-4">
		      	<div class="input-group">
				  <span class="input-group-addon">Notes</span>
				  <input type="text" id="input-notes" class="form-control" aria-describedby="basic-addon3">
				</div>
		    </div>
		     <div class="col-sm-4">
		      	<div class="input-group">
				  <span class="input-group-addon">Quantity unit</span>
				  <input type="text" id="input-quantity_unit" class="form-control" aria-describedby="basic-addon3">
				</div>
		    </div>
		    <hr>		   
	  	</div>		
	  	<div class="row">	  		
	     	<div class="col-sm-11"></div>
	     	<div class="col-sm-1"> 
      			<button type="button" data-toggle="tooltip" title="Submit" 
		      		class="btn btn-default glyphicon glyphicon-ok" id="button-submit-item_type"></button>	 
			</div>
	  	</div>
	  </div> 
	</div>
</div>
<?php include("header/header_end.php"); ?>		