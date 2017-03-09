<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<?php require_once (__DIR__ . "/../common/commonHeadInclude.php") ?>
	</head>
	<body>
		<?php 
			$draught = new Draught();
			
			include("getDraughtForm.php");
		?>
	</body>
</html>