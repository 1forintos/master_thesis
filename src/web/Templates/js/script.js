$(document).ready(function() {	
	initTemplatesTable();
	initTemplateItemsTable();
});

function initTemplatesTable() {
	var templateInfoTable = $('#table-templates').DataTable({
        "columnDefs": [ {
            "targets": -2,
            "data": null,
            "defaultContent": '<button class="button-edit btn btn-default glyphicon glyphicon-pencil" data-toggle="modal" data-target="#popup-edit-template"></button>'
        }, {
            "targets": -1,
            "data": null,
            "defaultContent": '<button class="button-remove btn btn-default glyphicon glyphicon-remove"></button>'
        }
    ]});

	selectedTemplateId = null;
    $('#table-templates tbody').on( 'click', '.button-edit', function () {
        var data = templateInfoTable.row( $(this).parents('tr') ).data();
        $('#input-name').val(data[1]);
        $('#input-notes').val(data[2]);
        selectedTemplateId = data[0];
    } );
    $('#table-templates tbody').on( 'click', '.button-remove', function () {
        var data = templateInfoTable.row( $(this).parents('tr') ).data();        
        removeTemplate(data[0]);
    } );
    $('#table-templates tbody').on( 'click', '.button-remove', function () {
        var data = templateInfoTable.row( $(this).parents('tr') ).data();
    } );
    $('#popup-button-save-template').click(function() {
    	submitTemplateChanges();
    });

	fillTable(templateInfoTable, 'templates');
}


function initTemplateItemsTable() {
	var templateItemsTable = $('#table-template-items').DataTable({
        "columnDefs": [ {
            "targets": -2,
            "data": null,
            "defaultContent": '<button class="button-edit btn btn-default glyphicon glyphicon-pencil" data-toggle="modal" data-target="#popup-edit-template-item"></button>'
        }, {
            "targets": -1,
            "data": null,
            "defaultContent": '<button class="button-remove btn btn-default glyphicon glyphicon-remove"></button>'
        }
    ]});

	selectedTemplateItemId = null;
    $('#table-template-items tbody').on( 'click', '.button-edit', function () {
        var data = templateItemsTable.row( $(this).parents('tr') ).data();
        $('#text-template').text(data[1]);
        $('#text-item_name').text(data[2]);
        $('#input-quantity').val(data[3]);
        $('#text-quantity_unit').text(data[4]);
        selectedTemplateItemId = data[0];
    });
    $('#table-template-items tbody').on( 'click', '.button-remove', function () {
        var data = templateItemsTable.row( $(this).parents('tr') ).data();        
        removeTemplateItem(data[0]);
    } );
    $('#table-template-items tbody').on( 'click', '.button-remove', function () {
        var data = templateItemsTable.row($(this).parents('tr')).data();
    } );
    $('#popup-button-save-template-item').click(function() {
    	submitTemplateItemChanges();
    });

	fillTable(templateItemsTable, 'template-items');
}

function removeTemplate(templateId) {
	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "deleteTemplate",
			data: templateId
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

function removeTemplateItem(templateItemId) {
	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "deleteTemplateItem",
			data: templateItemId
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


function submitTemplateChanges() {	
	var templateName = $('#input-name').val().trim();
	var templateNotes = $('#input-notes').val().trim();
		
	if(templateName == null || templateName == '') {
		$('#input-name').addClass("danger");
		alert("Name of the template has to be provided.");
		return;
	} else {
		$('#input-name').removeClass("danger");
	}
	
	var templateData = {};
	templateData.templateId = selectedTemplateId;
	templateData.name = templateName;
	templateData.notes = templateNotes;	

	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "updateStorageTemplate",
			data: templateData
		},
		success: function(result) {
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

function submitTemplateItemChanges() {	
	var itemQuantity = $('#input-quantity').val().trim();	
		
	if(!(itemQuantity > -1)) {
		$('#input-quantity').addClass("danger");
		alert("Quantity has to be 0 or a positive number.");
		return;
	} else {
		$('#input-quantity').removeClass("danger");
	}
	
	var itemData = {};
	itemData.templateItemId = selectedTemplateItemId;
	itemData.quantity = itemQuantity;	 

	$.ajax({
		type: "POST",
		url: "/sm/db/db_methods.php",
		data: {
			method: "updateStorageTemplateItem",
			data: itemData
		},
		success: function(result) {
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