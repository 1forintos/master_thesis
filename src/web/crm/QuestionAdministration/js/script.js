$(document).ready(function() {
	var table = $('#table-questions').DataTable({
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

	$('#table-questions tbody').on( 'click', '.button-edit', function () {
		var data = table.row( $(this).parents('tr') ).data();
		$('#input-edit-question_text').val(data[1]);
		selectedQuestionId = data[0];
	});

	$('#table-questions tbody').on( 'click', '.button-remove', function () {
		var data = table.row($(this).parents('tr')).data();
		removeQuestion(data[0]);
	});

	$('#popup-button-save-modifications').click(function() {
		editQuestion();
	});

	$('#popup-button-save-new').on( 'click', function () {
		addNewQuestion();
	});

	$('#select-course').selectpicker();
	$('#select-course').change(function() {
		fillTable(table, 'questions');
	});
	loadCoursesIntoSelect(table);
});

function showContent() {
	$('#content').show();
}

function loadCoursesIntoSelect(table) {
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
					if(result.data.length > 0) {
						fillTable(table, 'questions');
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

function fillTable(tableToFill, dataType) {
	tableToFill.clear();
	$('#table-questions tbody').empty();
	$('#table-questions_info').text('Showing 0 to 0 of 0 entries.');
	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			method: "loadQuestionsForCourse",
			data: $('#select-course').val()
		},
		success: function(data) {
			var results = $.parseJSON(data);
			if('status' in results) {
				if(results.status == "success") {
					for(var i in results.data) {
						addRowToTable(tableToFill, results.data[i]);
					}
				} else if('error' in resultObj) {
					alert("Failed to load questions: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
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
	var dataArray = [];
	for(var key in rowData) {
		dataArray.push(rowData[key]);
	}
	table.row.add(dataArray).draw().node();
}

function addNewQuestion() {
	var question_text = $('#input-new-question_text').val().trim();
	if(question_text == "") {
		$('#input-new-question_text').addClass("danger");
		alert("\"Question Text\" cannot be empty.");
		return;
	}

	var questionData = {};
	questionData.question_text = question_text;
	questionData.course_id = $('#select-course').val();

	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			method: "addNewQuestion",
			data: questionData
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

function editQuestion() {
	var question_text = $('#input-edit-question_text').val().trim();
	if(question_text == "") {
		$('#input-new-question_text').addClass("danger");
		alert("\"Question Text\" cannot be empty.");
		return;
	} 

	var questionData = {};
	questionData.id = selectedQuestionId;
	questionData.course_id = $('#select-course').val();
	questionData.question_text = question_text;

	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			method: "editQuestion",
			data: questionData
		},
		success: function(result) {
			if(result.indexOf("success") > -1) {
				alert("Success!");
				location.reload();
			} else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
					alert("Failed to edit question: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			}
		}
	});
}

function removeQuestion(id) {
	var questionData = {};
	questionData.id = id;
	questionData.course_id = $('#select-course').val();

	$.ajax({
		type: "POST",
		url: "/crm/db/db_methods.php",
		data: {
			method: "removeQuestion",
			data: questionData
		},
		success: function(result) {
			if(result.indexOf("success") > -1) {
				alert("Success!");
				location.reload();
			} else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
					alert("Failed to delete question: " + resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			}
		}
	});
}
