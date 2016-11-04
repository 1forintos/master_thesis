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
      var resultObj = $.parseJSON(result);
			if(resultObj.status == "success") {
				var data = {
					action: "stop_lecture",
					lectureId: resultObj.lectureId
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
      var lectureId = null;
			if(resultObj.status == "success") {
        lectureId = resultObj.lectureId;
        var download = $('<a></a>')
          .attr('href','data:text/csv;charset=utf8,' + encodeURIComponent(resultObj.data))
          .attr('download','codes.csv')
          .appendTo('body');
        download.get(0).click();
        download.remove();

				$.ajax({
        type: "POST",
        url: "/lecture/db/db_methods.php",
        data: {
          method: "getUserId"
        },
        success: function(result) {
          var resultObj = $.parseJSON(result);
          if(resultObj.status == "success") {
            var data = {
							action: "start_lecture",
              lecturerId: resultObj.userId,
							lectureId: lectureId
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


function updateLecturerSocket() {
  $.ajax({
    type: "POST",
    url: "/lecture/db/db_methods.php",
    data: {
      method: "getUserId"
    },
    success: function(result) {
      var resultObj = $.parseJSON(result);
      if(resultObj.status == "success") {
        var data = {
          action: "update_lecturer_socket",
          lecturerId: resultObj.userId
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
			updateLecturerSocket();
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