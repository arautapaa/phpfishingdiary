<?php

require_once 'Controller.php';

use Base\GearQuery;
use Base\PlaceQuery;
use Base\FisherQuery;
use Base\FishQuery;

class NewDraughtController extends ControllerImpl implements Controller {

	public function execute() {
		parent::execute();

		$gears = GearQuery::create()->orderByName()->find();
		$places = PlaceQuery::create()->orderByName()->find();
		$fishers = FisherQuery::create()->orderByName()->find();
		$fishes = FishQuery::create()->orderByName()->find();
		
		$this->data['gears'] = $gears;
		$this->data['places'] = $places;
		$this->data['fishers'] = $fishers;
		$this->data['fishes'] = $fishes;

		
	}
}


?>