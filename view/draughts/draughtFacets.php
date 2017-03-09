
<div class="col-xs-12 col-centered">
	<div class="row">
		<form action="" method="GET">
			<div class="col-sm-6 col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<div  class="btn-group bootstrap-select col-xs-12 facet">
							<label class="col-xs-4 col-sm-3 col-md-2" for="fish" >Kala</label>
							<select multiple class="col-xs-8 col-sm-9 col-md-10 selectpicker" name="fish[]">
								<?php foreach ($fishes as $selectFish) { ?>
									<option <?php if(isset($fish) && in_array($selectFish->getFishId(), $fish)) { echo "selected"; } ?> value="<?php echo $selectFish->getFishId() ?>"><?php echo $selectFish->getName() ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="btn-group bootstrap-select col-xs-12 facet">
							<label class="col-xs-4 col-sm-3 col-md-2" for="fisher" >Väline</label>
							<select multiple class="col-xs-8 col-sm-9 col-md-10 selectpicker" name="gear[]">
								<?php foreach ($gears as $selectGear) { ?>
									<option <?php if(isset($gear) && in_array($selectGear->getGearId(), $gear)) { echo "selected"; } ?> value="<?php echo $selectGear->getGearId() ?>"><?php echo $selectGear->getName() ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-xs-12">
				<div class="row">
					<div class="col-xs-12">
						<div class="btn-group bootstrap-select col-xs-12 facet">
							<label class="col-xs-4 col-sm-3 col-md-2" for="fisher" >Kalastaja</label>
							<select multiple class="col-xs-8 col-sm-9 col-md-10 selectpicker" name="fisher[]">
								<?php foreach ($fishers as $selectFisher) { ?>
									<option <?php if(isset($fisher) && in_array($selectFisher->getFisherId(), $fisher)) { echo "selected"; } ?> value="<?php echo $selectFisher->getFisherId() ?>"><?php echo $selectFisher->getName() ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="btn-group bootstrap-select col-xs-12 facet">
							<label class="col-xs-4 col-sm-3 col-md-2" for="place" >Paikka</label>
							<select multiple class="col-xs-8 col-sm-9 col-md-10 selectpicker" name="place[]">
								<?php foreach ($places as $selectPlace) { ?>
									<option <?php if(isset($place) && in_array($selectPlace->getPlaceId(), $place)) { echo "selected"; } ?> value="<?php echo $selectPlace->getPlaceId() ?>"><?php echo $selectPlace->getName() ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<div class="row">
					<div class="col-xs-12">
						<div class="btn-group bootstrap-select col-xs-12">
							<label class="col-xs-12" >Aikaväliltä</label>
							<div class="col-xs-12">
								<input class="form-control facet" name="min_date" value="<?php if(isset($min_date)) { echo $min_date; } ?>" placeholder="dd.mm.yyyy" />
							</div>
							<div class="col-xs-12">
								<input class="form-control facet" name="max_date" value="<?php if(isset($max_date)) { echo $max_date; } ?>" placeholder="dd.mm.yyyy" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6">
				<div class="row">
					<div class="col-xs-12">
						<label>Toiminnot</label>
					</div>
						<?php /**if($queryString != "") { ?>
						<div class="col-xs-5 col-centered">
							<a href="<?php echo $url ?>" class="col-xs-12 btn btn-primary">Poista rajaukset</a>						
						</div> 
						<?php } else { echo "&nbsp;"; } */?>
					</div>
					
					<div class="row">
						<a href="javascript:document.forms[0].submit()" class="btn btn-primary col-xs-6 facet">
							<strong>Rajaa tulokset</strong>
						</a>
						<a href="/new/" class="btn btn-primary col-xs-6 facet">
							<strong>Uusi rivi</strong>
						</a>
						<a href="#" class="btn btn-primary col-xs-6 facet">
							<strong>Lisää arvo</strong>
						</a>
						<a href="#" class="btn btn-primary col-xs-6 facet">
							<strong>Muokkaa arvoa</strong>
						</a>
						<a href="javascript:void(0)" id="make-specific-report" class="btn btn-primary col-xs-6 facet">
							<strong>Tee tarkempi raportti</strong>
						</a>
						<?php if($queryString != "") { ?>
							<a href="<?php echo $url ?>" class="btn btn-primary col-xs-6 facet">
								<strong>Poista rajaukset</strong>
							</a>
						<?php } ?>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>