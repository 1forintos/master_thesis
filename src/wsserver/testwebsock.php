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
      case 'start_lecture':
        $this->lectures[] = array(
          "courseId" => $data->courseId,
          "lecturerId" => $data->lecturerId,
          "lecturerSocketId" => $user->id, 
          "studentSocketIds" => Array()
        );
        break;
      case 'stop_lecture':
        $tmp = $this->lectures;
        foreach($tmp as $key => $lecture) {
          if($lecture['courseId'] == $data->courseId) {
            $this->kickStudents($key);
            unset($this->lectures[$key]);
            break;
          }
        }
        break;
      case 'update_lecturer_socket':
        $this->updateLecturerSocket($data->lecturerId, $user->id);
        break;
      case 'init':
        $this->addStudentToLecture($data->code, $user->id);
        break;
      case 'comment':
        $this->sendComment($data->code, $data->text);
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
    // Do nothing: This is just an echo server, there's no need to track the user.
    // However, if we did care about the users, we would probably have a cookie to
    // parse at this step, would be looking them up in permanent storage, etc.
    $this->send($user, "Connected.");
  }
  
  protected function closed ($user) {
    // Do nothing: This is where cleanup would go, in case the user had any sort of
    // open files or other objects associated with them.  This runs after the socket 
    // has been closed, so there is no need to clean up the socket itself here.      
  }

  protected function kickStudents($lectureKey) {
    foreach($this->users as $user) {
      if(in_array($user->id, $this->lectures[$lectureKey]['studentSocketIds'])) {
        $this->send($user, "close");
      }
    }
  }

  protected function sendComment($code, $text) {
    foreach ($this->lectures as $key => $lecture) {
      if(array_key_exists($code, $lecture['studentSocketIds'])) {
        $lecturer = $this->getUser($lecture['lecturerSocketId']);
        $this->send($lecturer, $text);
        break;
      }
    }
  }

  protected function getUser($socketId) {
    foreach($this->users as $user) {
      if($user->id == $socketId) {
        return $user;
      }
    }
  }

  protected function updateLecturerSocket($lecturerId, $socketId) {
    foreach ($this->lectures as $key => $lecture) {
      if($lecture['lecturerId'] == $lecturerId) {
        $this->lectures[$key]['lecturerSocketId'] = $socketId;
        break;
      }
    }
  }

  protected function addStudentToLecture($code, $socketId) {
		$result = pg_execute($GLOBALS['db'], "get_course_id_for_code", array($code));
		if(!$result) { 
			error_log("Failed to get course ID for code: [" . $code . "]");
		}
    if(count($result) < 1) {
      return;
    } 
    $result = pg_fetch_row($result);
    $courseId = reset($result);
    foreach($this->lectures as $key => $lecture) {
      echo print_r($lecture, true);
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
