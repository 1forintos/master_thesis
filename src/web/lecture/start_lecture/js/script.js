$(document).ready(function() {
  $('#button-stop-lecture').click(function() {
    stopLecture();
  });

	$('#button-start-lecture').click(function() {
    startLecture();
  });

	loadCoursesIntoSelect(showContent);
	init();
});

function showContent() {
	$('#content').show();
}

function stopLecture() {
  $.ajax({
		type: "POST",
		url: "/lecture/db/db_methods.php",
		data: {
      data: $('#select-course').val(),
			method: "stopLecture"
		},
		success: function(result) {
			if(result == "success") {
				var data = {
					action: "stopLecture",
					courseId: $('#select-course').val()
				};
				sendMsgViaSocket(data);
				alert("Success.");
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

function startLecture() {
  $.ajax({
		type: "POST",
		url: "/lecture/db/db_methods.php",
		data: {
      data: $('#select-course').val(),
			method: "startLecture"
		},
		success: function(result) {
      var resultObj = $.parseJSON(result);
			if(resultObj.status == "success") {
        var download = $('<a></a>')
          .attr('href','data:text/csv;charset=utf8,' + encodeURIComponent(resultObj.data))
          .attr('download','codes.csv')
          .appendTo('body');
        download.get(0).click();
        download.remove();

				var data = {
					action: "startLecture",
					courseId: $('#select-course').val()
				};
				sendMsgViaSocket(data);
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

function loadCoursesIntoSelect(_callback) {
	$.ajax({
		type: "POST",
		url: "/lecture/db/db_methods.php",
		data: {
			method: "loadCoursesOfLecturer"
		},
		success: function(result) {
			var result = $.parseJSON(result);
			if('status' in result) {
				if(result.status == "success") {
					var select = $('#select-course');
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

var socket;

function init() {
  var host = "ws://152.66.183.81:9000/echobot"; // SET THIS TO YOUR SERVER
  try {
    socket = new WebSocket(host);
    socket.onopen = function (msg) {
    };
    socket.onmessage = function (msg) {
    };
    socket.onclose = function (msg) {
    };
  }
  catch (ex) {
    log(ex);
  }
}

function sendMsgViaSocket(msg) {
  if(!socket) {
    alert("You are disconnected.");
    return;
  }

  try {  
    socket.send(JSON.stringify(msg));
    return true;
  } catch (ex) {
    console.log(ex);
    return false;
  }
}