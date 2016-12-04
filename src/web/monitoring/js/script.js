$(document).ready(function() {
    loadCoursesIntoSelect(showContent);

    $('#button-view').click(function() {
        loadCharts();
    });
});

function showContent() {
	$('#content').show();
}

function loadCharts() {
    var lectureId = $('#select-lecture').val();
    getFeedback();

    if(document.getElementById('checkbox-temperature').checked) {
        var type = "temperature";
        var id = "chart-temperature";
        getMeasurements(id, lectureId, type);
    } else {
        $('#temperature-container').hide();
    }
    if(document.getElementById('checkbox-brightness').checked) {
        var type = "brightness";
        var id = "chart-brightness";
        getMeasurements(id, lectureId, type);
    }  else {
        $('#brightness-container').hide();
    }
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
                            updateQuestionSelect();
                        } else {
                            var selectL = $('#select-lectures');
                            selectL.val([]);
                        }						
                    });
					select.selectpicker('refresh');		
                    if(result.data.length > 0) {
                        updateLectureSelect();
                        updateQuestionSelect();
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
                            text += " (" + result.data[i].title + ")";
                        }
						newOption.innerHTML = text; 
						newOption.setAttribute("value", result.data[i].id);
						select.append(newOption);							
					}
					select.selectpicker('refresh');					
				}
			} 
		},
		error: function(result) {
			alert("Something went wrong.");
			console.log(result);
		}
	});
}

function updateQuestionSelect() {
	$.ajax({
		type: "POST",
		url: "/monitoring/db/db_methods.php",
		data: {
			method: "loadQuestions",
            data: $('#select-course').val()
		},
		success: function(result) {
			var result = $.parseJSON(result);
			if('status' in result) {
				if(result.status == "success") {
					var select = null;
					select = $('#select-question');
                    $('#select-question').find('option').remove();
					for(var i in result.data) {
						var newOption = document.createElement("option");
						newOption.innerHTML = result.data[i].question_text; 
						newOption.setAttribute("value", result.data[i].id);
						select.append(newOption);							
					}
					select.selectpicker('refresh');					
				}
			} 
		},
		error: function(result) {
			alert("Something went wrong.");
			console.log(result);
		}
	});
}

function getMeasurements(chartId, lectureId, type) {
    var data = {};
    data.lecture_id = lectureId;
    data.type = type;

    $.ajax({
        type: "POST",
        url: "/monitoring/db/db_methods.php",
        data: {
            data: data,
            method: "getMeasurements"
        },
        success: function(result) {
            var resultObj = $.parseJSON(result);
            if(resultObj.status == "success") {
                var dataArray = [];
                for(var i in resultObj.data) {
                    var parts = resultObj.data[i].timestamp.split(" ");
                    var date = parts[0].split("-");
                    var time = parts[1].split(":");
                    dataArray.push([
                        Date.UTC(date[0], date[1], date[2], time[0], time[1], time[2]), 
                        parseInt(resultObj.data[i].value)
                    ]);
                }

                var title;
                var textX;
                var textY;

                if(type == "temperature") {
                    title = "Temperature - Lecture 1 (Course 1)";
                    textX = "Time";
                    textY = "Temperature (°C)";
                } else if(type == "brightness") {
                    title = "Brightness - Lecture 1 (Course 1)";
                    textX = "Time";
                    textY = "Brightness (lux)";
                }
                if(dataArray.length == 0) {
                    alert("No " + type + " data for this lecture.");
                    if(type == "temperature") {
                        $('#temperature-container').hide();
                    } else if(type == "brightness") {
                        $('#brightness-container').hide();
                    }
                } else {
                    drawChart(chartId, title, textX, textY, dataArray, type);
                    if(type == "temperature") {
                        $('#temperature-container').show();
                    } else if(type == "brightness") {
                        $('#brightness-container').show();
                    }
                }
            } else {
                if('error' in resultObj) {
                    alert("Error: " + resultObj.error);
                } else {
                    console.log("What the heck happened??");
                }
            } 
        }    
	});
}

function getFeedback() {
    var lectureId = $('#select-lecture').val();
    var questionId = $('#select-question').val();
    var data = {};
    data.lecture_id = lectureId;
    data.question_id = questionId;

    $.ajax({
        type: "POST",
        url: "/monitoring/db/db_methods.php",
        data: {
            data: data,
            method: "getFeedback"
        },
        success: function(result) {
            var resultObj = $.parseJSON(result);
            if(resultObj.status == "success") {
                console.log(resultObj.data);
                var dataArray = [];
                for(var i in resultObj.data) {
                    var parts = resultObj.data[i].timestamp.split(" ");
                    var date = parts[0].split("-");
                    var time = parts[1].split(":");
                    dataArray.push([
                        Date.UTC(date[0], date[1], date[2], time[0], time[1], time[2]), 
                        parseInt(resultObj.data[i].feedback)
                    ]);
                }

                if(dataArray.length == 0) {
                    alert("No feedback data for this lecture.");
                    $('#feedback-container').hide();
                } else {
                    $('#feedback-container').show();
                }

                var title = "First Lecture - " + $('#select-question option:selected').text();
                var textX = "Time";
                var textY = "Value";
                var chartId = "chart-feedback";
                drawChart(chartId, title, textX, textY, dataArray, 'feedback');
            } else {
                if('error' in resultObj) {
                    alert("Error: " + resultObj.error);
                } else {
                    console.log("What the heck happened??");
                }
            } 
        }    
	});
}

function drawChart(id, title, textX, textY, data, type){
    var unit = null;
    if(type == 'feedback') {
        unit = 'Answer';
    } else if(type == 'temperature') {
        unit = '°C';
    } else if(type == 'brightness') {
        unit = 'lux';
    }

    Highcharts.chart(id, {
        chart: {
            zoomType: 'x'
        },
        title: {
            text: title
        },
        subtitle: {
            text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
        },
        xAxis: {
            title: {
                text: textX
            },
            type: 'datetime'
        },
        yAxis: {
            title: {
                text: textY
            }
        },
        legend: {
            enabled: false
        },
        plotOptions: {
            area: {
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                marker: {
                    radius: 1
                },
                lineWidth: 0.3,
                states: {
                    hover: {
                        lineWidth: 0.3
                    }
                },
                threshold: null
            }
        },

        series: [{
            type: 'area',
            name: unit,
            data: data
        }]
    });
}