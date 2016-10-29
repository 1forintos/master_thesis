$(document).ready(function(){
  init();
  $('#button-submit').on('click', function() {
    submitEvaluation();
  });
  // iterate through questions

  $('#content').show();
});

function init() {
  var host = "ws://152.66.183.81:9000/echobot"; // SET THIS TO YOUR SERVER
  try {
    socket = new WebSocket(host);
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
      if(msg.data == "close") {
        $.ajax({
          type: "POST",
          url: "/feedback/db/db_methods.php",
          data: {
            method: "logout"
          }, 
          succes: location.reload()
        });
      }
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

function submitEvaluation() {
  var questions = $('.selectpicker');
  var evaluation = [];
  for(var i = 0; i < questions.length; i++) {
    var answer = {};
    var elementId = questions[i].getAttribute('id');
    var id = elementId.substring(elementId.indexOf("_") + 1);
    answer.question_id = id;
    answer.value = questions[i].value;
    evaluation.push(answer); 
  }

  if(evaluation.length < 1) {
    return;
  }

  $.ajax({
		type: "POST",
		url: "/feedback/db/db_methods.php",
		data: {
			method: "submitEvaluation",
			data: evaluation
		},
		success: function(result) {
      if(result == "success") {
        alert("Success");
      } else {
				var resultObj = $.parseJSON(result);
				if('error' in resultObj) {
          console.log(resultObj.error);
					alert("Failed to submit evaluation.");
				} else {
					console.log("What the heck happened??");
				}
			}
		}
	});
}

function reconnect() {
  init();
}
