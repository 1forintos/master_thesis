$(document).ready(function() {	  
  	$('.button-remove').button();  	

  	$('#button-submit-item_type').button();
  	$('#button-submit-item_type').click(function() {
  		submitItemType();
  	});
});

function submitItemType() {
	var itemName = $('#input-name').val().trim();
	var itemNotes = $('#input-notes').val().trim();
	var itemQuantityUnit = $('#input-quantity_unit').val().trim();
	var inputsOk = true;
	if(itemName == null || itemName == '') {
		$('#input-name').addClass("danger");
		inputsOk = false;
	} else {
		$('#input-name').removeClass("danger");
	}	
	if(itemQuantityUnit == null || itemQuantityUnit == '') {
		$('#input-quantity_unit').addClass("danger");
		inputsOk = false;
	} else {
		$('#input-quantity_unit').removeClass("danger");
	}
	if(!inputsOk) {
		alert("Both Name and Quantity Unit of the item have to be provided.");
		return;
	}
	
	var itemData = {};
	itemData.name = itemName;
	itemData.notes = itemNotes;
	itemData.quantityUnit = itemQuantityUnit;	

	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "insertItemType",
			data: itemData
		},
		success: function(result) {
			if(result.indexOf("success") > -1) {
				alert("Success!");
				location.reload();
			} else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
					alert("Failed to submit item type: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			} 
		}
	});
}