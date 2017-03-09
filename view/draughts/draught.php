<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<?php require_once (__DIR__ . "/../common/commonHeadInclude.php") ?>
	</head>
	<body>
		<?php 
			if($draught->getDraughtId() != null) {
				include("getDraughtForm.php");
			} else {
				include("noDraught.php");
			}
		?>
	</body>
</html>