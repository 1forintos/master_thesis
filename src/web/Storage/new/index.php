<?php 
	include("server_script.php"); 

	chdir(getRootFolder());
	
	include("header/header_script.php"); 
	include("header/header_begin.php"); 
?>

<div id="content" class="container">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">New Storage</h3>
	  </div>
	  <div class="panel-body">
	    This section allows you to add new Storages to the system which correspond to real storages.
	  </div>
	</div>

	<div class="panel panel-default">	  
	  <div class="panel-body">
	  	<div class="row section-label">	  		
	     	<div class="col-sm-12">
		      <span>Attributes</span> 
		    </div>
	  	</div>
	    <div class="row" class="input-container">
		    <div class="col-sm-5">
		     	<div class="input-group">
				  <span class="input-group-addon">Storage Name</span>
				  <input type="text"  id="input-name" class="form-control" aria-describedby="basic-addon3">
				</div>
		    </div>
		    <div class="col-sm-7">
		      	<div class="input-group">
				  <span class="input-group-addon">Location</span>
				  <input type="text" id="input-location" class="form-control" aria-describedby="basic-addon3">
				</div>
		    </div>
		    <hr>	
	    </div>
	   	<div class="row" class="input-container">
		     <div class="col-sm-5">
		      	<div class="input-group">
				  <span class="input-group-addon">Notes</span>
				  <input type="text" id="input-notes" class="form-control" aria-describedby="basic-addon3">
				</div>
		    </div>
		    <div class="col-sm-7">
		      	<div class="input-group">				 
					<span class="input-group-addon">Template</span> 
					<select id="select-templates" name="select-templates" aria-describedby="basic-addon3" class="form-control selectpicker selectpicker-default select-templates" data-live-search="true" data-size="5">
			  			<option value="-1">No template<options>
			  		</select>
				</div>
		    </div>
	  	</div>		
	  	<div class="row">	  		
	     	<div class="col-sm-11"></div>
	     	<div class="col-sm-1"> 
      			<button type="button" data-toggle="tooltip" title="Submit" 
		      		class="btn btn-default glyphicon glyphicon-ok" id="button-submit-storage"></button>	 
			</div>
	  	</div>
	  </div> 
	</div>
</div>
<?php include("header/header_end.php"); ?>