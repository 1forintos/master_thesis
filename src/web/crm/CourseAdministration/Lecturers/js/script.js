$(document).ready(function() {
	$('#button-assign').click(function() {
    	assignLecturers();
    });	
	$('#button-unassign').click(function() {
    	unassignLecturers();
    });

	loadLecturersIntoSelect();
	loadCoursesIntoSelect(showContent);
});

function showContent() {
	$('#content').show();
}

function loadLecturersIntoSelect() {
	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			data: "lecturers",
			method: "loadData"
		},
		success: function(result) {
			var result = $.parseJSON(result);
			if('status' in result) {
				if(result.status == "success") {
					var select = $('#select-lecturers');
					
					for(var i in result.data) {
						var newOption = document.createElement("option");				
						newOption.innerHTML = result.data[i].full_name + " (" + result.data[i].email + ")"; 
						newOption.setAttribute("value", result.data[i].id);
						select.append(newOption);	
					}
					select.change(function () {
						if($(this).val() != null) {
							updateCoursesSelect();
						} else {
							var selectC = $('#select-courses');
							selectC.val([]);
						}						
					});
				}
			} 
		}
	});
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
					var select = $('#select-courses');
					for(var i in result.data) {
						var newOption = document.createElement("option");				
						newOption.innerHTML = result.data[i].title + " (" + result.data[i].course_code + ")"; 
						newOption.setAttribute("value", result.data[i].id);
						select.append(newOption);	
					}
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

function updateCoursesSelect() {
	var selectL = $('#select-lecturers');
	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			data: selectL.val(),
			method: "loadCoursesForLecturers"
		},
		success: function(result) {
			var result = $.parseJSON(result);			
			if('status' in result) {
				if(result.status == "success") {
					var select = $('#select-courses');
					select.val(result.data);					
				} 
			} 
		}
	});
}

function assignLecturers() {
	var selectL = $('#select-lecturers');
	var selectC = $('#select-courses');
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

function unassignLecturers() {
	var selectL = $('#select-lecturers');
	if(selectL.val() == null) {
		alert("Please select the Lecturer(s) remove assignments.");
		return;
	} 
	var data = {};
	data.lecturerIds = selectL.val();
	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			data: data,
			method: "unassignLecturers"
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