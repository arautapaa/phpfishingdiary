<!DOCTYPE html>
<html>
<head>
	<title>Galagirja v. 0.2</title>
	<?php require_once (__DIR__ . "/../common/commonHeadInclude.php") ?>
</head>
<body>
	<?php 
		if(isset($errorKey)) {
			echo $errorKey;
		}

		if(!isset($queryString)) {
			$queryString = "";
		} else {
			$queryString = "?" . $queryString;
		}
	?>
	<div class="facet-background">
		<div class="container">
			<div class="facets">
				<div class="col-xs-10 col-centered">
					<div class="row">
						<h1 class="text-center">Kalastuskirja vol. 2</h1>
					</div>
				</div>
				
				<?php 
					// include facet selections
					include "draughtFacets.php";
				?>

			</div>
		</div>
	</div>
	<div class="container">
		<div class="col-xs-12 results">
			<div class="row result-header">
				<div class="col-xs-4 text-center col-sm-2">
					<strong><a href="/fishes/orderby/date/<?php echo isset($order) && isset($orderBy) && $orderBy == 'date' && $order == 'desc' ? 'asc/' : 'desc/'; echo $queryString ?>">Aika</a><?php if(isset($order) && isset($orderBy) && $orderBy == 'date' && $order == 'desc') { ?><span class="glyphicon glyphicon-chevron-down"> <?php } else if(isset($order) && isset($orderBy) && $orderBy == 'date' && $order == 'asc') { ?><span class="glyphicon glyphicon-chevron-up"><?php } ?></strong>
				</div>
				<div class="col-xs-4 text-center col-sm-2">
					<strong><a href="/fishes/orderby/fish/<?php echo isset($order) && isset($orderBy) && $orderBy == 'fish' && $order == 'asc' ? 'desc/' : 'asc/'; echo $queryString ?>">Kala</a><?php if(isset($order) && isset($orderBy) && $orderBy == 'fish' && $order == 'desc') { ?><span class="glyphicon glyphicon-chevron-down"> <?php } else if(isset($order) && isset($orderBy) && $orderBy == 'fish' && $order == 'asc') { ?><span class="glyphicon glyphicon-chevron-up"><?php } ?></strong></strong>
				</div>
				<div class="col-sm-2 text-center hidden-xs">
					<strong><a href="/fishes/orderby/fisher/<?php echo isset($order) && isset($orderBy) && $orderBy == 'fisher' && $order == 'asc' ? 'desc/' : 'asc/'; echo $queryString ?>">Saaja</a><?php if(isset($order) && isset($orderBy) && $orderBy == 'fisher' && $order == 'desc') { ?><span class="glyphicon glyphicon-chevron-down"> <?php } else if(isset($order) && isset($orderBy) && $orderBy == 'fisher' && $order == 'asc') { ?><span class="glyphicon glyphicon-chevron-up"><?php } ?></strong></strong>
				</div>
				<div class="col-sm-2 text-center hidden-xs">
					<strong><a href="/fishes/orderby/gear/<?php echo isset($order) && isset($orderBy) && $orderBy == 'gear' && $order == 'asc' ? 'desc/' : 'asc/'; echo $queryString ?>">Väline</a><?php if(isset($order) && isset($orderBy) && $orderBy == 'gear' && $order == 'desc') { ?><span class="glyphicon glyphicon-chevron-down"> <?php } else if(isset($order) && isset($orderBy) && $orderBy == 'gear' && $order == 'asc') { ?><span class="glyphicon glyphicon-chevron-up"><?php } ?></strong></strong>
				</div>
				<div class="col-sm-2 text-center hidden-xs">
					<strong><a href="/fishes/orderby/place/<?php echo isset($order) && isset($orderBy) && $orderBy == 'place' && $order == 'asc' ? 'desc/' : 'asc/'; echo $queryString ?>">Paikka</a><?php if(isset($order) && isset($orderBy) && $orderBy == 'place' && $order == 'desc') { ?><span class="glyphicon glyphicon-chevron-down"> <?php } else if(isset($order) && isset($orderBy) && $orderBy == 'place' && $order == 'asc') { ?><span class="glyphicon glyphicon-chevron-up"><?php } ?></strong></strong>
				</div>
				<div class="col-xs-4 text-center col-sm-2">
					<strong><a href="/fishes/orderby/weight/<?php echo isset($order) && isset($orderBy) && $orderBy == 'weight' && $order == 'desc' ? 'asc/' : 'desc/'; echo $queryString ?>">Paino</a><?php if(isset($order) && isset($orderBy) && $orderBy == 'weight' && $order == 'desc') { ?><span class="glyphicon glyphicon-chevron-down"> <?php } else if(isset($order) && isset($orderBy) && $orderBy == 'weight' && $order == 'asc') { ?><span class="glyphicon glyphicon-chevron-up"><?php } ?></strong></strong>
				</div>
			</div>
			<?php
				$counter = 0;

				foreach ($draughts as $draught) {					
					if($counter++ < MAX_LIMIT) {
						include "getDraught.php";
					}

					// this is for formatting the JSON correctly
					$draught->getGear();
					$draught->getFisher();
					$draught->getPlace();
					$draught->getFish();
				}
			?>

			<?php 

				require_once (__DIR__ . "/../../model/DraughtListCalculator.php");

				$draughtReport = new DraughtListCalculator();
				$draughtReport->setDraughts($draughts);
				$draughtReport->calculate();


			?>
			<div id="result-footer" class="row">
				<div class="col-xs-6 col-sm-3 text-center">
					<strong><?php echo $draughtReport->getDraughtCount() ?> saalista</strong>
				</div>
				<div class="col-xs-6 col-sm-3 text-center">
					<strong>Min: <?php echo $draughtReport->getDraughtMinWeight() ?> g</strong>
				</div>
				<div class="col-xs-6 col-sm-3 text-center">
					<strong>Max: <?php echo $draughtReport->getDraughtMaxWeight() ?> g</strong>
				</div>
				<div class="col-xs-6 col-sm-3 text-center">
					<strong>Ka: <?php echo $draughtReport->getDraughtAverage() ?> g</strong>
				</div>
			</div>

			<script>
				// used for better analyzing, 
				FishData.draughts = <?php echo $draughts->toJson();?>;
				FishData.maxLimit = <?php echo MAX_LIMIT; ?>;
			</script>
		</div>
	</div>

	<?php include "reportModal.php"; ?>

	<script src="/view/js/FishBindings.js"></script>
</body>
</html>