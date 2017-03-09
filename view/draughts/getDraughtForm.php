
<div class="container">
	<div class="col-md-12">
		<div class="row">
			<a href="javascript:window.history.back();">Takaisin</a>
			<form action="/draughts/save/" method="POST">
				<input class="form-control" type="hidden" value="<?php echo TokenHelper::getToken('draught') ?>" name="middlewaretoken" />
				<?php if($draught->getDraughtId() != null) { ?>
					<input type="hidden" name="fishId" value="<?php echo $draught->getDraughtId() ?>" />
				<?php } ?>
				<div class="input-group">
					<label for="fish" >Kala</label>
					<select class="styled-select form-control" name="fish">
						<?php foreach ($fishes as $fish) { ?>

							<option <?php if($draught->getFishId() == $fish->getFishId()) { echo " selected"; } ?> value="<?php echo $fish->getFishId() ?>"><?php echo $fish->getName() ?></option>

						<?php } ?>
					</select>
				</div>
				<div class="input-group">
					<label for="fisher" >Kalastaja</label>
					<select class="styled-select form-control" name="fisher">
						<?php foreach ($fishers as $fisher) { ?>

							<option <?php if($draught->getFisherId() == $fisher->getFisherId()) { echo " selected"; } ?> value="<?php echo $fisher->getFisherId() ?>"><?php echo $fisher->getName() ?></option>

						<?php } ?>
					</select>
				</div>
				<div class="input-group">
					<label for="place" >Väline</label>
					<select class="styled-select form-control" name="gear">
						<?php foreach ($gears as $gear) { ?>

							<option <?php if($draught->getGearId() == $gear->getGearId()) { echo " selected"; } ?> value="<?php echo $gear->getGearId() ?>"><?php echo $gear->getName() ?></option>

						<?php } ?>
					</select>
				</div>
				<div class="input-group">
					<label for="place" >Paikka</label>
						<select class="styled-select form-control" name="place">
						<?php foreach ($places as $place) {
							?>

							<option <?php if($draught->getPlaceId() == $place->getPlaceId()) { echo " selected"; } ?> value="<?php echo $place->getPlaceId() ?>"><?php echo $place->getName() ?></option>

							<?php
						} ?>
					</select>
				</div>
				<div class="input-group">
					<label for="weight" >Paino</label>
					<input class="form-control" value="<?php echo $draught->getWeight() ?>" name="weight" />
				</div>
				<div class="input-group">
					<label for="date" >Päiväys</label>
					<input class="form-control" value="<?php echo $draught->getDate('d.m.Y') ?>" name="date" />
				</div>
				<div class="input group">
					<input type="submit" value="Tallenna" name="save" class="btn btn-primary"/>
					<?php if($draught->getDraughtId() != null) { ?> 
					<input type="submit" value="Poista" name="delete" class="btn btn-primary"/>
					<?php } ?>
				</div>
			</form>
		</div>
	</div>
</div>