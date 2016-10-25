
var socket;

function init() {
  var host = "ws://152.66.183.81:9000/echobot"; // SET THIS TO YOUR SERVER
  try {
    socket = new WebSocket(host);
    log('WebSocket - status ' + socket.readyState);
    socket.onopen = function (msg) {
      log("Welcome - status " + this.readyState);
    };
    socket.onmessage = function (msg) {
      log("Received: " + msg.data);
    };
    socket.onclose = function (msg) {
      log("Disconnected - status " + this.readyState);
    };
  }
  catch (ex) {
    log(ex);
  }
  $("#input-comment").focus();
}

function quit() {
  if (socket != null) {
    log("Goodbye!");
    socket.close();
    socket = null;
  }
}

function reconnect() {
  quit();
  init();
}

function log(msg) {
  var comments = $("#comments");
  comments.append("<br>" + "[" + getDate() + "] " + msg);
  comments.scrollTop(comments.prop("scrollHeight"));
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
