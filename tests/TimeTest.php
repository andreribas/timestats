<?php

namespace Tests\Unit;

use App\Exceptions\TimeException;
use App\Time;
use Tests\TestCase;

class TimeTest extends TestCase {

	/**
	 * @dataProvider getInvalidTimeCases
	 */
	public function testInvalidTime_shouldThrowAnException(string $time) {
		try {
			new Time($time);
			$this->fail("A `TimeException` should have been thrown");
		} catch (TimeException $e) {
			$this->assertEquals("Invalid time given: {$time}", $e->getMessage());
		}
	}

	public function getInvalidTimeCases() {
		return [
			['11:123'],
			['11:1'],
			['abc'],
			['ab:cd'],
			['24:00'],
			['00:60'],
		];
	}

}
