$(document).ready(function() {	
	var tableToFill = $('#table-stored_items').DataTable({
        "columnDefs": [ {
            "targets": -2,
            "data": null,
            "defaultContent": '<button class="button-edit btn btn-default glyphicon glyphicon-pencil" data-toggle="modal" data-target="#popup-edit"></button>'
        }, {
            "targets": -1,
            "data": null,
            "defaultContent": '<button class="button-remove btn btn-default glyphicon glyphicon-remove"></button>'
        }
    ]});

	selectedStoredItemId = null;
   
 
    $('#table-stored_items tbody').on( 'click', '.button-remove', function () {
        var data = tableToFill.row( $(this).parents('tr') ).data();        
        removeStoredItem(data[0]);
    } );
    $('#table-stored_items tbody').on( 'click', '.button-remove', function () {
        var data = tableToFill.row( $(this).parents('tr') ).data();
    } );
    $('#popup-button-save').click(function() {
    	submitChanges();
    });

	fillTable(tableToFill, 'stored_items');	

	$('#table tbody').on( 'click', '.button-edit', function () {
        var data = templateInfoTable.row( $(this).parents('tr') ).data();
        $('#input-name').val(data[1]);
        $('#input-notes').val(data[2]);
        selectedTemplateId = data[0];
    } );

    $('#popup-button-save-item').on( 'click', function () {
    	saveStorageItem();
   	});
   	loadStoragesIntoSelect();
	loadItemTypesIntoSelect();
});

function saveStorageItem() {
	var itemTypeId = $('#select-item_type').val();
	var storageId = $('#select-storage').val();
	var quantity = $('#input-new-quantity').val().trim();
		
	if(!(quantity >= 0)) {
		$('#input-new-quantity').addClass("danger");
		alert("Quantity has to be 0 or a positive number.");
		return;
	} else {
		$('#input-new-quantity').removeClass("danger");
	}
		
	var itemData = {};
	itemData.itemTypeId = itemTypeId;
	itemData.storageId = storageId;
	itemData.quantity = quantity;
	console.log(itemData);
	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "insertStoredItem",
			data: itemData
		},
		success: function(result) {
			console.log(result);
			if(result.indexOf("success") > -1) {
				alert("Success!");
				location.reload();
			} else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
					alert("Failed to submit item changes: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			} 
		}
	});
}

function removeStoredItem(storedItemId) {
	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "deleteStoredItem",
			data: storedItemId
		},
		success: function(result) {
			console.log(result);
			if(result.indexOf("success") > -1) {
				alert("Success!");
				location.reload();
			} else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
					alert("Failed to remove item: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			} 
		}
	});
}

function submitChanges() {	
	var itemQuantity = $('#input-quantity').val().trim();
		
	if(itemQuantity == null || itemQuantity == '') {
		$('#input-quantity').addClass("danger");
		alert("Quantity has to be 0 or a positive number.");
		return;
	} else {
		$('#input-quantity').removeClass("danger");
	}
		
	var itemData = {};
	itemData.storedItemId = selectedStoredItemId;
	itemData.quantity = itemQuantity	

	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "updateStoredItem",
			data: itemData
		},
		success: function(result) {
			console.log(result);
			if(result.indexOf("success") > -1) {
				alert("Success!");
				location.reload();
			} else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
					alert("Failed to submit item changes: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			} 
		}
	});
}

function fillTable(tableToFill, dataType) {
	tableToFill.clear();
	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "loadTableData",
			data: dataType
		},
		success: function(data) {
			var results = $.parseJSON(data);
			if('status' in results) {
				if(results.status == "success") {
					for(var i in results.data) {
						addRowToTable(tableToFill, results.data[i]);
					}
				}
			}
		}
	});
}


function addRowToTable(table, rowData) {	
	var dataArray = [];
	for(var key in rowData) {
		dataArray.push(rowData[key]);	
	}	
	table.row.add(dataArray).draw().node();
}

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

function loadStoragesIntoSelect() {
	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "loadStoragesForSelect"
		},
		success: function(results) {
			rows = jQuery.parseJSON(results);
			var storageSelect = $('#select-storage');
			for(var i in rows) {
				newOption = document.createElement("option");				
				newOption.innerHTML = rows[i].name;
				newOption.setAttribute("value", rows[i].storage_id);				
				storageSelect.append(newOption);
				storageSelect.selectpicker('refresh');	
			};				
		}
	});
}

function loadItemTypesIntoSelect() {
	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "loadItemTypesForSelect"
		},
		success: function(results) {
			rows = jQuery.parseJSON(results);
			var itemTypesSelect = $('.select-item_type');
			for(var i in rows) {
				newOption = document.createElement("option");				
				newOption.innerHTML = rows[i].name;
				newOption.setAttribute("value", rows[i].item_type_id);
				newOption.setAttribute("quantity_unit", rows[i].quantity_unit);
				itemTypesSelect.append(newOption);
				itemTypesSelect.selectpicker('refresh');	
			}		
			if(rows.length > 1) {
				var initialUnit = rows[0]['quantity_unit'];
				var initialUnitContainer = $('#container-input-quantity #text-quantity_unit');
				initialUnitContainer.attr("data-toggle", "tooltip");
				initialUnitContainer.attr("title", initialUnit);
				if(initialUnit.length > 5) {
					initialUnit = initialUnit.substr(0, 5) + "...";
				}
				$('#container-input-quantity #text-quantity_unit').text(initialUnit);
			}
			itemTypesSelect.change(function () {
			    $(this).val($(this).val());
				$(this).selectpicker('refresh')
				var initialUnit = $('option:selected', this).attr('quantity_unit');
				var correspondingUnitLabel = $('#container-input-quantity #text-quantity_unit');
				correspondingUnitLabel.attr("data-toggle", "tooltip");
				correspondingUnitLabel.attr("title", initialUnit);
				if(initialUnit.length > 5) {			
					initialUnit = initialUnit.substr(0, 5) + "...";			
				}
				correspondingUnitLabel.text(initialUnit);
			});
		}
	});
}