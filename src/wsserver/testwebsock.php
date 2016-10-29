#!/usr/bin/env php
<?php
require_once "db/db_methods.php";
require_once('./websockets.php');

class echoServer extends WebSocketServer {
  //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.  

  protected $lectures = Array();

  protected function process ($user, $message) {
    $data = json_decode($message);
    switch ($data->action) {
      case 'startLecture':
        $this->lectures[] = array(
          "courseId" => $data->courseId,
          "leaderSocketId" => $user->id, 
          "studentSocketIds" => Array() 
        );
        break;
      case 'stopLecture':
        $tmp = $this->lectures;
        foreach($tmp as $key => $lecture) {
          if($lecture['courseId'] == $data->courseId) {
            unset($this->lectures[$key]);
            break;
          }
        }
        break;
      case 'init':
        $this->addStudentToLecture($user->id, $data->code);
        break;
      case 'comment':
        echo "COMMENT YO\n";
        echo $data->text;
        break;
      default:
        # code...
        break;
    }
    echo print_r($this->lectures, true);
  }

  protected function unsetValue(array $array, $value, $strict = TRUE)
  {
    if(($key = array_search($value, $array, $strict)) !== FALSE) {
        unset($array[$key]);
    }
    return $array;
  }
  
  protected function connected ($user) {

    echo $user->id . "\n";
    // Do nothing: This is just an echo server, there's no need to track the user.
    // However, if we did care about the users, we would probably have a cookie to
    // parse at this step, would be looking them up in permanent storage, etc.
    $this->send($user, "Welcome to the 1707 chat client! It is so freaking unsafe that its hilarious!");
  }
  
  protected function closed ($user) {
    // Do nothing: This is where cleanup would go, in case the user had any sort of
    // open files or other objects associated with them.  This runs after the socket 
    // has been closed, so there is no need to clean up the socket itself here.      
  }


  protected function addStudentToLecture($socketId, $code) {
		$courseId = pg_execute($GLOBALS['db'], "get_course_id_for_code", array($code));

		if(!$courseId) { 
			error_log("Failed to get course ID for code: [" . $code . "]");
		}
    foreach($this->lectures as $key => $lecture) {
      if($lecture['courseId'] == $courseId) {
        $this->lectures[$key]['studentSocketIds'][$code] = $socketId;
        break;
      }
    }
	}

}

$echo = new echoServer("0.0.0.0","9000");

try {
  $echo->run();
}
catch (Exception $e) {
  $echo->stdout($e->getMessage());
}
