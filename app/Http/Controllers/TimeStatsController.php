<?php

namespace App\Http\Controllers;

use App\Exceptions\TimeException;
use App\Http\Resources\TimeStats;
use App\Time;
use Illuminate\Http\Request;

class TimeStatsController extends Controller
{
	public function getStats(Request $request) {
		try {
			$timeStats = new TimeStats($request);
			return response()->json($timeStats, 200, [], JSON_PRETTY_PRINT);
		} catch (TimeException $e) {
			return response()->json(["error" => $e->getMessage()], 400, [], JSON_PRETTY_PRINT);
		}
    }
}
