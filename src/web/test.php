<?php
$email = "asdasSd@yolo\.*hu";
if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo "YO";
} else {
  echo "NOYO";
}

?>
