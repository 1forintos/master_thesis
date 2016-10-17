<?php 
	include("server_script.php"); 

	chdir(getRootFolder());
	
	include("header/header_script.php"); 
	include("header/header_begin.php"); 
?>

<div id="content" class="container">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">New Storage Template</h3>
	  </div>
	  <div class="panel-body">
	    This section allows you to create a new type Storage Template. 
	    A template can be used in case of adding new Storages to the system. 
	    The new storage will have a default set of items according to the template.
	  </div>
	</div>

	<div class="panel panel-default">	  
	  <div class="panel-body">
	  	<div class="row section-label">	  		
	     	<div class="col-sm-12">
		      <span>General</span> 
		    </div>
	  	</div>
	    <div class="row" id="general-input-container" class="input-container">
		    <div class="col-sm-4">
		     	<div class="input-group">
				  <span class="input-group-addon">Template Name</span>
				  <input type="text"  id="input-name" class="form-control" aria-describedby="basic-addon3">
				</div>
		    </div>
		    <div class="col-sm-8">
		      	<div class="input-group">
				  <span class="input-group-addon">Notes</span>
				  <input type="text" id="input-notes" class="form-control" aria-describedby="basic-addon3">
				</div>
		    </div>
		    <hr>		   
	  	</div>
		<div class="row section-label">	  		
	     	<div class="col-sm-12">
		      <span>Default items</span> 		        			   
			</div>
	  	</div>
	  	<div id="itemTypes-input-container"></div>
	  	<div class="row section-label">	  		
	     	<div class="col-sm-10"></div>
	     	<div class="col-sm-2">
		      	<button type="button" data-toggle="tooltip" title="Add new row" 
		      		class="btn btn-default glyphicon glyphicon-plus" id="button-add-item_type"></button>	 
      			<button type="button" data-toggle="tooltip" title="Submit" 
		      		class="btn btn-default glyphicon glyphicon-ok" id="button-submit-template"></button>	 
			</div>
	  	</div>	  	
	  </div> 
	</div>
</div>
<!-- New input for item types -->
<div class="row input-container input-container-item_type" style="display: none;">
    <div class="col-sm-6">
     	<div class="input-group">
		  <span class="input-group-addon">Item type</span>
		  <select name="select-item_type" aria-describedby="basic-addon3" class="form-control selectpicker selectpicker-default select-item_type" data-live-search="true" data-size="5">
		  </select>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="input-group">
		  <span class="input-group-addon" id="input-name">(Opt) Default quantity</span>
		  <input type="text" class="form-control input-quantity" aria-describedby="basic-addon3">
		  <span class="input-group-addon" id="input-quantity_unit"></span>	
		</div>
    </div>	       
    <div class="col-sm-2">
    	<button type="button" class="btn btn-default button-remove glyphicon glyphicon-remove"  data-toggle="tooltip" title="Remove row"></button>	   			    
    </div>
</div>
<?php include("header/header_end.php"); ?>		