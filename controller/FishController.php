<?php 

include "Controller.php";
require_once(__DIR__ . "/../model/DraughtDataAccessor.php");

use Base\FishQuery;
use Base\FisherQuery;
use Base\GearQuery;
use Base\PlaceQuery;


class FishController extends ControllerImpl implements Controller {

	public function execute() {

		parent::execute();

		$draughts = null;
		$draught = null;

		$redirect = false;

		$draughtDataAccessor = new DraughtDataAccessor();

		if(array_key_exists('fishId', $this->data) && array_key_exists('delete', $this->data)) {

			$deleteError = $draughtDataAccessor->deleteDraught($this->data['fishId'], $this->getUserId(), $this->data['middlewaretoken']);
			// if the error was caught, let's inform the user on error
			if($deleteError != null) {
				$this->data['errorKey'] = $deleteError;
			}

			$redirect = true;
		}else if(array_key_exists('fishId', $this->data) && array_key_exists('save', $this->data)) {
			$updateError = $draughtDataAccessor->updateDraught($this->data, $this->getUserId(), $this->data['middlewaretoken']);
			// if the error was caught, let's inform the user on error
			if($updateError != null) {
				$this->data['errorKey'] = $updateError;
			}

			$redirect = true;
		} else if(array_key_exists('save', $this->data)) {
			$createError = $draughtDataAccessor->createDraught($this->data, $this->getUserId(), $this->data['middlewaretoken']);
			// and if the error was caught during the creation, let's inform again
			if($createError != null) {
				$this->data['errorKey'] = $createError;
			}

			$redirect = true;
		} 

		if(!$redirect) {
			if(!array_key_exists('fishId', $this->data)) {
				$draughts = $draughtDataAccessor->facetAllDraughtsByCurrentUser($this->getUserId(), $this->data);
				$this->data['draughts'] = $draughts;
			} else {
				$draught = $draughtDataAccessor->findDraughtById($this->data['fishId'], $this->getUserId());
				$this->data['draught'] = $draught;
			}
			
			$gears = GearQuery::create()->orderByName()->find();
			$places = PlaceQuery::create()->orderByName()->find();
			$fishers = FisherQuery::create()->orderByName()->find();
			$fishes = FishQuery::create()->orderByName()->find();


			$this->data['gears'] = $gears;
			$this->data['places'] = $places;
			$this->data['fishers'] = $fishers;
			$this->data['fishes'] = $fishes;
		} else {
			$this->setRedirect("/fishes/");
		}
	}

}


?>