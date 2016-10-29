$(document).ready(function(){
  retrieveEntryCode();
  init();
  $('#button-send').on('click', function() {
    sendComment();
  });
});

var entryCode;
var socket;

function init() {
  var host = "ws://152.66.183.81:9000/echobot"; // SET THIS TO YOUR SERVER
  try {
    socket = new WebSocket(host);
    //log('WebSocket - status ' + socket.readyState);
    socket.onopen = function (msg) {
      $.ajax({
      type: "POST",
      url: "/feedback/db/db_methods.php",
      data: {
        method: "getEntryCode"
      },
      success: function(result) {
        var resultObj = $.parseJSON(result);
        if(resultObj.status == "success") {
          var data = {
            action: "init",
            code: resultObj.code
          };
          sendMsgViaSocket(data);
        } else {
          if('error' in resultObj) {
            console.log(resultObj.error);
          } else {
            console.log("What the heck happened??");
          }
        }
      }
    });
     
    };
    socket.onmessage = function (msg) {
      //log("Received: " + msg.data);
    };
    socket.onclose = function (msg) {
      //log("Disconnected - status " + this.readyState);
    };
  }
  catch (ex) {
    //log(ex);
    console.log(ex);
  }
}

function setEntryCode(code) {
  entryCode = code;
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

function sendComment() {
  var commentInput = $("#input-comment");
  var commentText = commentInput.val();
  commentInput.focus();
  if (!commentText) {
    alert("That's not much of a comment now, is it? :)");
    return;
  }

  commentInput.val("");
  var commentData = {
    action: "comment",
    text: commentText
  };
  if(sendMsgViaSocket(commentData)) {
    saveComment(commentData);
  }
}

function retrieveEntryCode() {
  $.ajax({
		type: "POST",
		url: "/feedback/db/db_methods.php",
		data: {
			method: "getEntryCode"
		},
		success: function(result) {
      var resultObj = $.parseJSON(result);
      if(resultObj.status == "success") {
        setEntryCode(resultObj.code);
      } else {
				if('error' in resultObj) {
          console.log(resultObj.error);
				} else {
					console.log("What the heck happened??");
				}
			}
		}
	});
}

function saveComment(comment) {
  $.ajax({
		type: "POST",
		url: "/feedback/db/db_methods.php",
		data: {
			method: "submitComment",
			data: comment
		},
		success: function(result) {
      if(result == "success") {
        alert("Success");
      } else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
          console.log(resultObj.error);
					alert("Failed to save comment.");
				} else {
					console.log("What the heck happened??");
				}
			}
		}
	});
}

function quit() {
  if (socket != null) {
    socket.close();
    socket = null;
  }
}

function reconnect() {
  quit();
  init();
}

function onkey(event) {
  if (event.keyCode == 13) {
    sendComment();
  }
}

function getDate() {
  var now = new Date();

  var year = now.getFullYear();
  var month = now.getMonth() + 1; //January is 0!
  var day = now.getDate();
  var hour = now.getHours();
  var min = now.getMinutes();
  var sec = now.getSeconds();

  if (month < 10) {
    month = '0' + month;
  }

  if (day < 10) {
    day = '0' + day;
  }

  if (hour < 10) {
    hour = '0' + hour;
  }

  if (min < 10) {
    min = '0' + min;
  }

  if (sec < 10) {
    sec = '0' + sec;
  }

  now = /*year + '-' + month + '-' + day + ' ' + */hour + ':' + min + ':' + sec;
  return now;
}
