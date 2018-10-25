<?php

namespace App;

use App\Exceptions\TimeException;

class Time {

	private $hour;
	private $minute;

	public function __construct(string $time) {
		$this->validateTime($time);

		[$hour, $minute] = explode(":", $time);

		$this->hour = $hour;
		$this->minute = $minute;
	}

	private function validateTime(string $time): void {
		$isValidTime = preg_match("/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/", $time);
		if (!$isValidTime) {
			throw new TimeException("Invalid time given: {$time}");
		}
	}

	public function getHour() {
		return $this->hour;
	}

	public function getIntervalInMinutes(Time $time) {
		return abs($this->getTimeInMinutes() - $time->getTimeInMinutes());
	}

	public function geIntervalInHours(Time $time) {
		return abs($this->getHour() - $time->getHour());
	}

	public function getTimeInMinutes() {
		return $this->hour * 60 + $this->minute;
	}

	public function asString() {
		return "{$this->hour}:{$this->minute}";
	}
}
