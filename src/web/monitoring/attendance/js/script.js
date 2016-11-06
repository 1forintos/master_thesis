$(document).ready(function() {
    table = $('#table-attendance').DataTable();
    loadCoursesIntoSelect(showContent);
});

function showContent() {
	$('#content').show();
}

function loadCoursesIntoSelect(_callback) {
	$.ajax({
		type: "POST",
		url: "/monitoring/db/db_methods.php",
		data: {
			method: "loadCourses"
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
                    select.change(function () {
                        if($(this).val() != null) {
                            updateLectureSelect();
                        } else {
                            var selectL = $('#select-lectures');
                            selectL.val([]);
                        }						
                    });
					select.selectpicker('refresh');		
                    if(result.data.length > 0) {
                        updateLectureSelect();
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

function updateLectureSelect() {
	$.ajax({
		type: "POST",
		url: "/monitoring/db/db_methods.php",
		data: {
			method: "loadLectures",
            data: $('#select-course').val()
		},
		success: function(result) {
			var result = $.parseJSON(result);
			if('status' in result) {
				if(result.status == "success") {
					var select = null;
					select = $('#select-lecture');
                    $('#select-lecture').find('option').remove();
					for(var i in result.data) {
						var newOption = document.createElement("option");
                        var text = result.data[i].start_date;
                        text = text.substring(0, text.indexOf("."));
                        if(result.data[i].end_date != null && result.data[i].end_date != "") {
                            var date = result.data[i].end_date;
                            date = date.substring(0, date.indexOf("."));
                            text += " - " + date;
                        }
                        if(result.data[i].title != "") {
                            text = result.data[i].title + " / " + text;
                        }
						newOption.innerHTML = text; 
						newOption.setAttribute("value", result.data[i].id);
						select.append(newOption);							
					}
                    select.change(function () {
                        if($(this).val() != null) {
                            fillTable();
                        } else {
                            var selectL = $('#select-lectures');
                            selectL.val([]);
                        }						
                    });
					select.selectpicker('refresh');	
                    if(result.data.length > 0) {
                        fillTable();
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

function fillTable() {
	table.clear();
	$.ajax({
		type: "POST",
		url: "../db/db_methods.php",
		data: {
			method: "loadAttendance",
			data: $('#select-lecture').val()
		},
		success: function(data) {
			var results = $.parseJSON(data);
			if('status' in results) {
				if(results.status == "success") {
					for(var i in results.data) {
						addRowToTable(table, results.data[i]);
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

function addRowToTable(table, rowData) {
    var attended = rowData['attended'] == 't' ? true : false;
	var dataArray = [rowData['student_id']];
    var attendanceColor;
    if(attended) {
        attendanceColor = "#0F0";
        dataArray.push('Yes');
    } else {
        attendanceColor = "red";
        dataArray.push('No');
    }

   var newRow = table.row.add(dataArray).draw().node();
   newRow.cells[1].style.setProperty('background-color', attendanceColor)
	//table.row.add(dataArray).draw().node();
}
