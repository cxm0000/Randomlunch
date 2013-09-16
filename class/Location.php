<?php

class Location extends Restaurant {

	private static $requestHeaders = array(
		'Content-Type: application/json; charset=UTF-8'
	);

	function __construct($location){
		$this->getCurrentCoordinates($location);
	}



	private function getCurrentCoordinates($location){
		$url = MAP_BASE_URL . "/json?address=". urlencode($location) ."&region=SE&oe=utf8&sensor=false";

		$s = curl_init();

		// set search options
		curl_setopt($s,CURLOPT_URL, $url);
		curl_setopt($s, CURLOPT_HTTPHEADER, self::$requestHeaders);
		curl_setopt($s,CURLOPT_RETURNTRANSFER,true);

		$ret = curl_exec($s);

		$retJson = json_decode($ret);
//print_r($retJson->results[0]->geometry->location);die;
		if (!empty($retJson->results[0]->geometry->location)) {
			$this->setLatitude($retJson->results[0]->geometry->location->lat);
			$this->setLongitude($retJson->results[0]->geometry->location->lng);
		}

	}

}


?>