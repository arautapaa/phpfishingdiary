<html>
<head>
	<?php include "/view/common/commonHeadInclude.php" ?>
</head>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="col-xs-4">
				<strong>Pattern</strong>
			</div>
			<div class="col-xs-4">
				<strong>Controller</strong>
			</div>
			<div class="col-xs-4">
				<strong>View</strong>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
		
		<?php
			foreach ($routes->patterns as $pattern) { ?>
				<div class="row">
					<div class="col-xs-4">
						<?php echo $pattern->pattern ?>
					</div>
					<div class="col-xs-4">
						<?php echo $pattern->controller->name ?>
					</div>
					<div class="col-xs-4">
						<?php echo $pattern->view ?>
					</div>
				</div>

		<?php
			}
		?>

		</div>
	</div>
</div>

</html>