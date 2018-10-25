<?php

namespace Tests\App\Http\Resources;

use App\Http\Resources\TimeStats;
use App\Time;
use Tests\TestCase;

class TimeStatsTest extends TestCase {

	public function testGetOccurrencesPerHour() {
		$timeList = $this->getTimeList();
		$timeStats = new TimeStats($timeList);

		$occurrencesPerHour = $timeStats->getOccurrencesPerHour($timeList);
		$expected_result = [
			['hour' => '11:00', 'count' => 1],
			['hour' => '12:00', 'count' => 2],
			['hour' => '15:00', 'count' => 3],
			['hour' => '09:00', 'count' => 1],
		];

		$this->assertEquals($expected_result, $occurrencesPerHour);
	}

	public function testGetTopOccurrencesHour() {
		$timeList = $this->getTimeList();
		$timeStats = new TimeStats($timeList);

		$topOccurrencesHour = $timeStats->getTopOccurrencesHour($timeList);
		$expected_result = [
			'hour' => '15:00',
			'count' => 3,
		];

		$this->assertEquals($expected_result, $topOccurrencesHour);
	}

	public function testGetAverageOccurrencesPerHour() {
		$timeList = $this->getTimeList();
		$timeStats = new TimeStats($timeList);

		$topOccurrencesHour = $timeStats->getAverageOccurrencesPerHour($timeList);
		$expected_result = 1.1666666666667;

		$this->assertEquals($expected_result, $topOccurrencesHour);
	}

	public function testGetBiggestInterval() {
		$timeList = $this->getTimeList();
		$timeStats = new TimeStats($timeList);

		$topOccurrencesHour = $timeStats->getBiggestInterval($timeList);
		$expected_result = [
			"intervalInMinutes" => 368,
			"times" => ["15:23", "09:15"],
		];

		$this->assertEquals($expected_result, $topOccurrencesHour);
	}

	public function testGetSmallestInterval() {
		$timeList = $this->getTimeList();
		$timeStats = new TimeStats($timeList);

		$topOccurrencesHour = $timeStats->getSmallestInterval($timeList);
		$expected_result = [
			"intervalInMinutes" => 11,
			"times" => ["15:11", "15:00"],
		];

		$this->assertEquals($expected_result, $topOccurrencesHour);
	}


	private function getTimeList() {
		$timeList = ["11:23", "12:14", "15:23", "09:15", "12:44", "15:11", "15:00"];
		return array_map(function (string $time) { return new Time($time); }, $timeList);
	}
}
