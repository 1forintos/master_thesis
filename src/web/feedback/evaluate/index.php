<?php
	chdir(substr(__DIR__, 0, strpos(__DIR__, "/feedback/") + 9));
	require_once "init.php";
	require_once "db/auth.php";
	require_once "db/db_methods.php";
	
	authenticate();
	toggleAttendance();

	$courseId = 0;
	$questions = Array();
	if(isset($_SESSION['course_id'])) {
		$courseId = $_SESSION['course_id'];
		$questions = loadQuestions($courseId);
	}

	$optionNum = 10;
	$questionNum = 1;
?>

<!DOCTYPE html>
<html>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Evaluate</title>
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
		crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp"
		crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="css/styles.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
		crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
	
	<script src="js/script.js"></script>
</head>

<body>
	<div hidden id="content" class="container">
		<?php foreach($questions as $question):?>
		<div class="row">
			<label for=<?php echo "\"select-q_" . $question['id'] . "\""; ?> class="col-sm-11"><?php 
				echo $questionNum . ". " . $question['question_text']; 
				$questionNum++;
			?></label>
			<select id=<?php echo "\"select-q_"  . $question['id'] . "\""; ?> class="selectpicker col-sm-1" >
			<?php for($i = 1; $i < $optionNum + 1; $i++):?>
				<option value=<?php echo "\"$i\""; ?> ><?php echo "$i"; ?></option>
			<?php endfor;?>
			</select>				
		</div>
		<hr>
		<?php endforeach;?>
		<div id="button-container">
			<div class="row">
				<a href="/feedback/comment/" ><button id="button-comment" type="button" 
					class="control-button col-xs-4 col-md-3 col-lg-3 btn btn-default"><span class="button-text">Comment</span></button></a>
				<div class="col-xs-4 col-md-6"></div>
				<button id="button-submit" type="button" class="control-button col-xs-4 col-md-3 col-lg-3 btn btn-default"><span class="button-text">Submit</span></button>
			</div>
		</div>
	</div>
</body>
</html>