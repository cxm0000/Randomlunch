<?php

class Calculator
{

	static function distanceFromCoordinates($lat1, $lng1, $lat2, $lng2)
	{
//		echo PHP_EOL . "lat1: " . $lat1 . ' -- lng1: ' . $lng1;
//		echo PHP_EOL . "lat2: " . $lat2 . ' -- lng2: ' . $lng2;
		$pi80 = M_PI / 180;
		$lat1 *= $pi80;
		$lng1 *= $pi80;
		$lat2 *= $pi80;
		$lng2 *= $pi80;

		$r = 6372.797; // mean radius of Earth in km
		$dlat = $lat2 - $lat1;
		$dlng = $lng2 - $lng1;
		$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		$km = $r * $c;

		return $km;
	}

	public static function vincentyGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
	{
		// convert from degrees to radians
		$latFrom = deg2rad($latitudeFrom);
		$lonFrom = deg2rad($longitudeFrom);
		$latTo = deg2rad($latitudeTo);
		$lonTo = deg2rad($longitudeTo);

		$lonDelta = $lonTo - $lonFrom;
		$a = pow(cos($latTo) * sin($lonDelta), 2) +
			pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
		$b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

		$angle = atan2(sqrt($a), $b);
		return $angle * $earthRadius;
	}

	static function routeDistance($from, $to)
	{


		$lat1 = trim($from[0]);
		$lon1 = trim($from[1]);

		$lat2 = trim($to[0]);
		$lon2 = trim($to[1]);

		$from = $lon1 . ',' . $lat1;
		$to = $lon2 . ',' . $lat2;

		$url = "http://maps.google.com/maps/api/directions/xml?origin={$from}&destination={$to}&sensor=false";

		$s = curl_init();

		// set search options
		curl_setopt($s, CURLOPT_URL, $url);
		curl_setopt($s, CURLOPT_RETURNTRANSFER, true);

		$ret = curl_exec($s);

		try {
			$data = new SimpleXMLElement($ret);

			$timeString = $data->route->leg->duration->text;

			$distance = ($data->route->leg->distance->value) / 1000;
		} catch (Exception $e) {
			//die($e->getMessage() . "RET: " . print_r($ret));
		}

		return $distance;
	}

}

?>