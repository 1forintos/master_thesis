$(document).ready(function() {
	studentTable = $('#table-students').DataTable({
		"columnDefs": [ 
	]});
	$('#confirm-remove').on('show.bs.modal', function(e) {
		$(this).find('.btn-ok').on('click', function() {
			removeEnrollment();
		});		
	});	
	$('.button-view').on( 'click', function () {
		loadEnrollment();
	});
	$('.button-upload').on( 'click', function () {
		uploadEnrollment();
	});
	$('#select-course').selectpicker();
	loadCoursesIntoSelect(showContent);	
});

function showContent() {
	$('#content').show();
}

function loadCoursesIntoSelect(_callback) {
	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			data: "courses",
			method: "loadData"
		},
		success: function(result) {
			var result = $.parseJSON(result);
			if('status' in result) {
				if(result.status == "success") {
					var select = null;
					select = $('#select-course');
					for(var i in result.data) {
						var newOption = document.createElement("option");				
						newOption.innerHTML = result.data[i].title + " (" + result.data[i].course_code + ")"; 
						newOption.setAttribute("value", result.data[i].id);
						select.append(newOption);							
					}
					select.selectpicker('refresh');					
				}
			} 
			_callback();
		},
		error: function(result) {
			_callback();
			alert("Something went wrong.");
			console.log(result);
		}
	});
}

function loadEnrollment() {
	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			data: $('#select-course').val(),
			method: "loadEnrollment"
		},
		success: function(data) {
			var results = $.parseJSON(data);
			console.log(results);
			if('status' in results) {
				if(results.status == "success") {
					studentTable.clear();
					var rowNum = 1;
					for(var i in results.data) {
						var rowData = {};
						rowData.rowNum = rowNum;
						rowData.student_id = results.data[i].student_id
						addRowToTable(studentTable, rowData);
						rowNum++;
					}
				}
			}
		},
		error: function(result) {
			alert("Something went wrong.");
			console.log(result);
		}
	});
}

function removeEnrollment() {
	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			method: "removeEnrollment",
			data: $('#select-course').val()
		},
		success: function(result) {
			if(result.indexOf("success") > -1) {				
				alert("Success!");
				location.reload();
			} else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
					alert("Failed to remove enrollment: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			}
		}
	});
}

function addRowToTable(table, rowData) {
	console.log(rowData);
	var dataArray = [];
	for(var key in rowData) {
		dataArray.push(rowData[key]);
	}
	table.row.add(dataArray).draw().node();
}

function assignLecturers() {
	var selectL = null;
	if($('.visible-xs').is(':hidden')) {
		selectL = $('#select-lecturers');
	} else {
		selectL = $('#select-lecturers-xs');
	}
	var selectC = null;
	if($('.visible-xs').is(':hidden')) {
		selectC = $('#select-courses');
	} else {
		selectC = $('#select-courses-xs');
	}	
	if(selectL.val() == null || selectC.val() == null) {
		alert("Please select the Lecturer(s) and Course(s) to make the assignment(s).");
		return;
	} 
	var data = {};
	data.lecturerIds = selectL.val();
	data.courseIds = selectC.val();
	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			data: data,
			method: "assignLecturers"
		},
		success: function(result) {
			if(result == "success") {
				alert("Success.");
				location.reload();					
			} else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
					alert("Error: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			} 
		}
	});
}
