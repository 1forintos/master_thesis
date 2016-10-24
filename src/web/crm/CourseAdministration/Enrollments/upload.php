<?php
	
chdir(getRootFolder());

require_once "db/db_methods.php";

if(!$_POST['courseId']) {
	throwError("No course selected.");
}

header('Content-Type: application/json');

$fileContent = json_decode(file_get_contents($_FILES['file-input']['tmp_name'][0]));
if(!isset($fileContent->studentIds)) {
	throwError("No student IDs found in the uploaded file");
} else if(!$fileContent->studentIds) {
	throwError("No student IDs found in the uploaded file");
}

if(!isset($_POST['courseId'])) {
	throwError("Course not found.");
} else if(!$_POST['courseId']) {
	throwError("No course selected.");
}

insertEnrollments($_POST['courseId'], $fileContent->studentIds);

$output = [];
$output['result'] = "success"; 
echo json_encode($output);

function getRootFolder() {
	return substr(__DIR__, 0, strpos(__DIR__, "/crm/") + 4);
}

?>