$(document).ready(function() {
	var table = $('#table-courses').DataTable({
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

	$('#table-courses tbody').on( 'click', '.button-edit', function () {
		var data = table.row( $(this).parents('tr') ).data();
		$('#input-edit-course_code').val(data[1]);
		$('#input-edit-title').val(data[2]);
		$('#input-edit-notes').val(data[3]);
		selectedCourseId = data[0];
	});

	$('#table-courses tbody').on( 'click', '.button-remove', function () {
		var data = table.row($(this).parents('tr')).data();
		deleteCourse(data[0]);
	});

	fillTable(table, 'courses');

	$('#popup-button-save-modifications').click(function() {
		modifyCourse();
	});

	$('#popup-button-save-new').on( 'click', function () {
		createNewCourse();
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

function createNewCourse() {
	var course_code = $('#input-new-course_code').val().trim();
	var title = $('#input-new-title').val().trim();
	var notes = $('#input-new-notes').val().trim();

	var invalidInput = false;
	if(course_code == "") {
		$('#input-new-course_code').addClass("danger");
		invalidInput = true;
	} else {
		$('#input-new-course_code').removeClass("danger");
	}

	if(title == "") {
		$('#input-new-title').addClass("danger");
		invalidInput = true;
	} else {
		$('#input-new-title').removeClass("danger");
	}

	if(invalidInput) {
		alert("\"Course Code\" and \"Title\" cannot be empty.");
		return;
	}

	var courseData = {};
	courseData.course_code = course_code;
	courseData.title = title;
	courseData.notes = notes;

	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			method: "createCourse",
			data: courseData
		},
		success: function(result) {
			if(result.indexOf("success") > -1) {
				alert("Success!");
				location.reload();
			} else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
					alert("Failed to create course: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			}
		}
	});
}

function modifyCourse() {
	var course_code = $('#input-edit-course_code').val().trim();
	var title = $('#input-edit-title').val().trim();
	var notes = $('#input-edit-notes').val().trim();

	var invalidInput = false;
	if(course_code == "") {
		$('#input-new-course_code').addClass("danger");
		invalidInput = true;
	} else {
		$('#input-new-course_code').removeClass("danger");
	}

	if(title == "") {
		$('#input-new-title').addClass("danger");
		invalidInput = true;
	} else {
		$('#input-new-title').removeClass("danger");
	}

	if(invalidInput) {
		alert("\"Course Code\" and \"Title\" cannot be empty.");
		return;
	}

	var courseData = {};
	courseData.id = selectedCourseId;
	courseData.course_code = course_code;
	courseData.title = title;
	courseData.notes = notes;

	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			method: "modifyCourse",
			data: courseData
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

function deleteCourse(id) {
	var courseData = {};
	courseData.id = id;

	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			method: "deleteCourse",
			data: courseData
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
