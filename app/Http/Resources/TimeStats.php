<?php

namespace App\Http\Resources;

use App\Time;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeStats extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request)
	{
		$timeList = array_map(
			function (string $time) { return new Time($time); },
			$request->post('time_list')
		);

		return [
			'occurrencesPerHour' => $this->getOccurrencesPerHour($timeList),
			'topOccurrencesHour' => $this->getTopOccurrencesHour($timeList),
			'averageOccurrencesPerHour' => $this->getAverageOccurrencesPerHour($timeList),
			'biggestInterval' => $this->getBiggestInterval($timeList),
			'smallestInterval' => $this->getSmallestInterval($timeList),
		];
	}

	public function getOccurrencesPerHour(array $timeList): array
	{
		$occurrencesPerOur = array_reduce($timeList, function(array $occurrencesPerOur, Time $time) {
			$hourIndex = $time->getHour() . ":00";
			$occurrencesPerOur[$hourIndex] = isset($occurrencesPerOur[$hourIndex]) ?
				$occurrencesPerOur[$hourIndex] + 1 : 1;
			return $occurrencesPerOur;
		}, []);

		return array_map(function($hour, $count) {
			return ['hour' => $hour, 'count' => $count];
		}, array_keys($occurrencesPerOur), $occurrencesPerOur);
	}

	public function getTopOccurrencesHour(array $timeList) {
		$occurrencesPerHour = $this->getOccurrencesPerHour($timeList);
		return array_reduce($occurrencesPerHour, function($topOccurrencesHour, $hourStats) {
			return $topOccurrencesHour['count'] > $hourStats['count'] ? $topOccurrencesHour : $hourStats;
		}, ['count' => 0]);
	}

	public function getAverageOccurrencesPerHour(array $timeList) {
		$maxTime = $this->filterBy($timeList, function($a, $b) { return $a > $b; });
		$minTime = $this->filterBy($timeList, function($a, $b) { return $a < $b; });
		$count = count($timeList);

		return $count / $maxTime->geIntervalInHours($minTime);
	}

	private function filterBy(array $timeList, $comparisonCallback): Time {
		return array_reduce($timeList, function (?Time $result, Time $time) use ($comparisonCallback) {
			return is_null($result) || $comparisonCallback($result->getHour(), $time->getHour()) ? $time : $result;
		});
	}

	public function getBiggestInterval(array $timeList) {
		return $this->getInterval($timeList, function($a, $b) { return $a < $b; });
	}

	public function getSmallestInterval(array $timeList) {
		return $this->getInterval($timeList, function($a, $b) { return $a > $b; });
	}

	private function getInterval(array $timeList, $comparisonCallback) {
		$interval = null;
		foreach ($timeList as $time1) {
			foreach ($timeList as $time2) {
				if ($time1 == $time2) {
					continue;
				}
				$intervalInMinutes = $time1->getIntervalInMinutes($time2);
				if (is_null($interval) || $comparisonCallback($interval['intervalInMinutes'], $intervalInMinutes)) {
					$interval = [
						'intervalInMinutes' => $intervalInMinutes,
						'times' => [$time1->asString(), $time2->asString()]
					];
				}
			}
		}
		return $interval;
	}

}
