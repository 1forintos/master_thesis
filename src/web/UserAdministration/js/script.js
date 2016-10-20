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

	userAccountId = null;

	$('#table-user_accounts tbody').on( 'click', '.button-edit', function () {
		var data = table.row( $(this).parents('tr') ).data();
		$('#input-edit-email').val(data[1]);
		$('#input-edit-full_name').val(data[2]);
		$('#input-edit-notes').val(data[3]);
		$('#select-edit-user_type').val(data[4]).change();
		selectedUserAccountId = data[0];
	});

	$('#table-user_accounts tbody').on( 'click', '.button-remove', function () {
		var data = table.row($(this).parents('tr')).data();
		deleteUserAccount(data[0]);
	});

	fillTable(table, 'user_accounts');

	$('#popup-button-save-new').on( 'click', function () {
		createNewAccount();
	});

	$('#popup-button-save-modifications').click(function() {
		modifyUserAccount();
	});

});

function fillTable(tableToFill, dataType) {
	tableToFill.clear();
	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			method: "loadData",
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

function modifyUserAccount() {
	var email = $('#input-edit-email').val().trim();
	var fullName = $('#input-edit-full_name').val().trim();
	var notes = $('#input-edit-notes').val().trim();
	var userType = $('#select-edit-user_type').val();

	var invalidInput = false;
	if(email == "") {
		$('#input-edit-email').addClass("danger");
		invalidInput = true;
	} else {
		$('#input-edit-email').removeClass("danger");
	}

	if(fullName == "") {
		$('#input-edit-full_name').addClass("danger");
		invalidInput = true;
	} else {
		$('#input-edit-full_name').removeClass("danger");
	}

	if(invalidInput) {
		alert("\"E-mail\" and \"Full Name\" cannot be empty.");
		return;
	}

	var accountData = {};
	accountData.id = selectedUserAccountId;
	accountData.full_name = fullName;
	accountData.notes = notes;
	accountData.user_type = userType;

	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			method: "modifyUserAccount",
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
					alert("Failed to modify account: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			}
		}
	});
}

function deleteUserAccount(id) {
	var accountData = {};
	accountData.id = id;

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
