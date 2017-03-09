<?php

use Base\DraughtQuery;
use Propel\Runtime\ActiveQuery\Criteria;

define("DEFAULT_LIMIT", 48);

class DraughtDataAccessor {
	/**
	 * $userId = current user that we want to check
	 * $data = request data
	 */
	public function facetAllDraughtsByCurrentUser($userId, $data) {
		if($userId == null) {
			$userId = AuthenticationHelper::getAuthenticatedUser();
		}

		$order = null;
		$orderBy = null;

		$dateFilter = array();
		$dateSet = false;

		$draughts = DraughtQuery::create()->filterByUserId($userId)->filterByDeletable(0);

		if(array_key_exists('order', $data)) {
			$order = $data['order'];
		} else {
			$order = Criteria::ASC;
		}

		if(array_key_exists('orderBy', $data)) {
			$orderBy = $data['orderBy'];
		}

		if(array_key_exists('fish', $data)) {
			$draughts->filterByFishId($data['fish']);
		}

		if(array_key_exists('fisher', $data)) {
			$draughts->filterByFisherId($data['fisher']);
		}

		if(array_key_exists('gear', $data)) {
			$draughts->filterByGearId($data['gear']);
		}

		if(array_key_exists('place', $data)) {
			$draughts->filterByPlaceId($data['place']);
		}

		if(array_key_exists('min_date', $data) && $data['min_date'] != '') {
			$dateFilter['min'] = date("Y-m-d", strtotime($data['min_date']));
			$dateSet = true;
		}

		if(array_key_exists('max_date', $data) && $data['max_date'] != '') {
			$dateFilter['max'] = date("Y-m-d", strtotime($data['max_date']));
			$dateSet = true;
		}

		if($dateSet) {
			$draughts->filterByDate($dateFilter);
		}


		switch($orderBy) {
			case "fish":
				$draughts = $draughts->useFishQuery()->orderByName($order)->endUse();
				break;
			case "fisher":
				$draughts = $draughts->useFisherQuery()->orderByName($order)->endUse();
				break;
			case "gear":
				$draughts = $draughts->useGearQuery()->orderByName($order)->endUse();
				break;
			case "place":
				$draughts = $draughts->usePlaceQuery()->orderByName($order)->endUse();
				break;
			case "weight":
				$draughts = $draughts->orderByWeight($order);
				break;
			case "date":
				$draughts = $draughts->orderByDate($order);
				break;
			default:
				$draughts = $draughts->orderByDate(Criteria::DESC);
				break;
		}

		$draughts = $draughts->find();

		return $draughts;
	}

	public function findDraughtById($draughtId, $userId = null) {
		if($userId == null) {
			$userId = AuthenticationHelper::getAuthenticatedUser();
		}

		$draught = DraughtQuery::create()->findPk($draughtId);
		return $draught;
	}

	public function createDraught($data, $userId, $middlewaretoken) {
		if($userId == null) {
			$userId = AuthenticationHelper::getAuthenticatedUser();
		}

		$errorKey = null;
		
		if(!TokenHelper::checkToken('draught', $userId, $middlewaretoken)) {
			$errorKey = "CSRF_ERROR";
		} else {
	
			$draught = new Draught();
		
			$draught->setFisherId($data['fisher']);
			$draught->setFishId($data['fish']);
			$draught->setGearId($data['gear']);
			$draught->setPlaceId($data['place']);
			$draught->setWeight($data['weight']);
			$draught->setUserId($userId);
			$draught->setDate(date("Y-m-d", strtotime($data['date'])));

			$draught->save();
		}

		return $errorKey;
	}

	public function updateDraught($data, $userId, $middlewaretoken) {
		if($userId == null) {
			$userId = AuthenticationHelper::getAuthenticatedUser();
		}

		$errorKey = null;

		if(!TokenHelper::checkToken('draught', $userId, $middlewaretoken)) {
			$errorKey = "CSRF_ERROR";
		} else {
			// let's get this right, is current catch for use
			$existingDraught = DraughtQuery::create()->findPk($data['fishId']);
			// ok, there were only one row
			if($existingDraught->getDraughtId() != null) {

				// if the user wasn't the correct one, let's stop this shit right now
				if($existingDraught->getUserId() != $userId) {
					$errorKey = "ACCESS_DENIED";
				} else {
					try {
						$existingDraught->setFisherId($data['fisher']);
						$existingDraught->setFishId($data['fish']);
						$existingDraught->setGearId($data['gear']);
						$existingDraught->setPlaceId($data['place']);
						$existingDraught->setWeight($data['weight']);
						$existingDraught->setDate(date("Y-m-d", strtotime($data['date'])));
						$existingDraught->save();

					} catch(Exception $e) {
						
					}
				}
			} else {
				$errorKey = "NO_DRAUGHT_AVAILABLE";
			}
		}

		return $errorKey;


	}

	public function deleteDraught($draughtId, $userId, $middlewaretoken) {
		if($userId == null) {
			$userId = AuthenticationHelper::getAuthenticatedUser();
		}

		$errorKey = null;

		if(!TokenHelper::checkToken('draught', $userId, $middlewaretoken)) {
			$errorKey = "CSRF_ERROR";
		} else {
			// let's get this right, is current catch for use
			$existingDraught = DraughtQuery::create()->findPk($draughtId);
			// ok, there were only one row
			if($existingDraught->getDraughtId() != null) {

				// if the user wasn't the correct one, let's stop this shit right now
				if($existingDraught->getUserId() != $userId) {
					$errorKey = "ACCESS_DENIED";
				} else {
					try {
						$existingDraught->setDeletable(1);
						$existingDraught->save();

					} catch(Exception $e) {
						
					}
				}
			} else {
				$errorKey = "NO_DRAUGHT_AVAILABLE";
			}
		}

		return $errorKey;

	}

}

?>