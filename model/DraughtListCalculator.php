<?php

class DraughtListCalculator {

	private $draughts;
	private $min;
	private $max;
	private $avg;
	private $count;
	private $total;

	public function calculate() {
		$this->count = count($this->draughts);

		$this->total = 0;
		$this->avg = 0;

		foreach ($this->draughts as $draught) {
			if($this->min == null || $this->min > $draught->getWeight()) {
				$this->min = $draught->getWeight();
			}			

			if($this->max == null || $this->max < $draught->getWeight()) {
				$this->max = $draught->getWeight();
			}

			$this->total += $draught->getWeight();
		}
		if($this->count != 0) {
			$this->avg = $this->total / $this->count;
		}
	}

	public function setDraughts($draughts) {
		$this->draughts = $draughts;
	}

	public function getDraughtCount() {
		return $this->count;
	}

	public function getDraughtMinWeight() {
		return $this->min;
	}

	public function getDraughtMaxWeight() {
		return $this->max;
	}

	public function getDraughtAverage() {
		return $this->avg;
	}
}

?>