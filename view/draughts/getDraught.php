<a href="/fishes/fish/<?php echo $draught->getDraughtId();?>/">
	<div class="row">
		<div class="col-sm-2 text-center col-xs-4">
			<?php echo $draught->getDate()->format("d.m.Y"); ?>
		</div>
		<div class="col-sm-2 text-center col-xs-4">
			<?php echo $draught->getFish()->getName(); ?>
		</div>
		<div class="col-sm-2 text-center hidden-xs">
			<?php echo $draught->getFisher()->getName(); ?>
		</div>
		<div class="col-sm-2 text-center hidden-xs">
			<?php echo $draught->getGear()->getName(); ?>
		</div>
		<div class="col-sm-2 text-center hidden-xs">
			<?php echo $draught->getPlace()->getName(); ?>
		</div>
		<div class="col-sm-2 text-center col-xs-4">
			<?php echo number_format($draught->getWeight() / 1000, 2); ?>
		</div>
	</div>
</a>