$(document).ready(function() {
	var table = $('#table-user_accounts').DataTable({
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

	selectedUserAccountId = null;

	$('#table-user_accounts tbody').on( 'click', '.button-edit', function () {
		var data = table.row( $(this).parents('tr') ).data();
		$('#input-email').val(data[1]);
		$('#input-full_name').val(data[2]);
		$('#input-notes').val(data[3]);
		console.log(data[4]);
		$('#select-user_type').val(data[4]).change();
		selectedTemplateId = data[0];
	});

	$('#table-user_accounts tbody').on( 'click', '.button-remove', function () {
		var data = table.row($(this).parents('tr')).data();
		deleteUserAccount(data[1]);
	});

	fillTable(table, 'user_accounts');

	$('#popup-button-save-modifications').click(function() {
		alert("SUBMIT CHANGES");
		// submitChanges();
	});

	$('#popup-button-save-new').on( 'click', function () {
		createNewAccount();
	});

	//loadStoragesIntoSelect();
	//loadItemTypesIntoSelect();

});

function fillTable(tableToFill, dataType) {
	tableToFill.clear();
	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
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

function loadUserTypesForSelect() {
	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			method: "loadUserTypesForSelect"
		},
		success: function(results) {
			rows = jQuery.parseJSON(results);
			var userTypesSelect = $('#select-user_types');
			for(var i in rows) {
				newOption = document.createElement("option");
				newOption.innerHTML = rows[i].name;
				newOption.setAttribute("value", rows[i].user_type);
				storageSelect.append(newOption);
				storageSelect.selectpicker('refresh');
			};
		}
	});
}

function createNewAccount() {
	var email = $('#input-new-email').val().trim();
	var fullName = $('#input-new-full_name').val().trim();
	var notes = $('#input-new-notes').val().trim();
	var userType = $('#select-user_type').val();

	var invalidInput = false;
	if(email == "") {
		$('#input-new-email').addClass("danger");
		invalidInput = true;
	} else {
		$('#input-new-email').removeClass("danger");
	}

	if(fullName == "") {
		$('#input-new-full_name').addClass("danger");
		invalidInput = true;
	} else {
		$('#input-new-full_name').removeClass("danger");
	}

	if(invalidInput) {
		alert("\"E-mail\" and \"Full Name\" cannot be empty.");
		return;
	}

	var accountData = {};
	accountData.email = email;
	accountData.full_name = fullName;
	accountData.notes = notes;
	accountData.user_type = userType;

	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			method: "createUserAccount",
			data: accountData
		},
		success: function(result) {
			console.log(result);
			if(result.indexOf("success") > -1) {
				alert("Success!");
				location.reload();
			} else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
					alert("Failed to create account: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			}
		}
	});
}

function deleteUserAccount(email) {
	var accountData = {};
	accountData.email = email;

	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			method: "deleteUserAccount",
			data: accountData
		},
		success: function(result) {
			if(result.indexOf("success") > -1) {
				alert("Success!");
				location.reload();
			} else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
					alert("Failed to delete account: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			}
		}
	});
}
