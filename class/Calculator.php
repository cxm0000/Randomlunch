<?php

class Calculator
{

//	static function distanceFromCoordinates(Array $from, Array $to){
//
//		$lat1 = $from[0];
//		$lon1 = $from[1];
//
//		$lat2 = $to[0];
//		$lon2 = $to[1];
//
////		$lat1 = $from[1];
////		$lon1 = $from[0];
////
////		$lat2 = $to[1];
////		$lon2 = $to[0];
//
//		$distance = (3958*3.1415926*sqrt(($lat2-$lat1)*($lat2-$lat1) + cos($lat2/57.29578)*cos($lat1/57.29578)*($lon2-$lon1)*($lon2-$lon1))/180);
//echo PHP_EOL . $distance;
//		return $distance;
//	}

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