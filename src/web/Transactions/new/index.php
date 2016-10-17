<?php 
	include("server_script.php"); 

	chdir(getRootFolder());
	
	include("header/header_script.php"); 
	include("header/header_begin.php"); 
?>

<div id="content" class="container">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">New Transaction</h3>
	  </div>
	  <div class="panel-body">
	    This section allows you to create new transactions in the future.
	  </div>
	</div>

	<div class="panel panel-default">	  
		<div class="panel-heading" style="margin-bottom: 1em;">
		    <h3 class="panel-title">Transaction details</h3>
	  	</div>
	  <div class="panel-body">	  
	 	 <div class="row input-container">
		    <div class="col-sm-5">
		     	<div class="input-group">
				  <span class="input-group-addon">Title</span>
				  <input type="text"  id="input-name" class="form-control" aria-describedby="basic-addon3">
				</div>
		    </div>
		    <div class="col-sm-7">
		      	<div class="input-group">
				  <span class="input-group-addon">Notes</span>
				  <input type="text" id="input-location" class="form-control" aria-describedby="basic-addon3">
				</div>
		    </div>
		    <hr>	
	    </div>	
	    <div class="row input-container" id="general-input-container">
		    <div class="col-sm-4">
		     	<div class="input-group">
				  <span class="input-group-addon">Source</span>
				  <select id="select-source" name="select-source" aria-describedby="basic-addon3" class="form-control selectpicker selectpicker-default select-storage" data-live-search="true" data-size="5">
				  </select>
				</div>
		    </div>
		    <div class="col-sm-4">
		      	<div class="input-group">
				  <span class="input-group-addon">Target</span>
				   <select id="select-target" name="select-target" aria-describedby="basic-addon3" class="form-control selectpicker selectpicker-default select-storage" data-live-search="true" data-size="5">
				  </select>
				</div>
		    </div>
		     <div class="col-sm-4">
		      	<div class="input-group">
				  <span class="input-group-addon">Date</span>
				 <div class="input-group date">
				    <input type="text" class="form-control datepicker" data-provide="datepicker">
					    <div class="input-group-addon">
					        <span class="glyphicon glyphicon-th"></span>
					    </div>
					</div>
				</div>
		    </div>
		    <hr>		   
	  	</div>		
	  	<div class="row">	  		
	     	<div class="col-sm-11"></div>
	     	<div class="col-sm-1" style="text-align: left;"> 
      			<button type="button" data-toggle="tooltip" title="Submit" 
		      		class="btn btn-default glyphicon glyphicon-ok" id="button-submit-item_type"></button>	 
			</div>
	  	</div>
	  </div> 
	</div>
</div>
<?php include("header/header_end.php"); ?>		