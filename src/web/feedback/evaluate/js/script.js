$(document).ready(function(){
  init();
  $('#button-submit').on('click', function() {
    submitEvaluation();
  });
  // iterate through questions

  $('#content').show();
});

var socket;

function init() {
  var host = "ws://152.66.183.81:9000/echobot"; // SET THIS TO YOUR SERVER
  try {
    socket = new WebSocket(host);
    //log('WebSocket - status ' + socket.readyState);
    socket.onopen = function (msg) {
      //log("Welcome - status " + this.readyState);
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
