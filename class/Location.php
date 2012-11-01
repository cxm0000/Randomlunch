<?php

class Location extends Restaurant {



	function __construct($location){
		$this->getCurrentCoordinates($location);
	}



	private function getCurrentCoordinates($location){
		$url = MAP_BASE_URL . "?q=". urlencode($location) ."&output=json&oe=utf8&sensor=false&key=" . MAP_KEY;
		$s = curl_init();

		// set search options
		curl_setopt($s,CURLOPT_URL, $url);
		curl_setopt($s,CURLOPT_RETURNTRANSFER,true);

		$ret = curl_exec($s);

//		var_dump($ret);die;
		$pattern = ' /\"coordinates\"\: \[(.*?), (.*?), (.*?)\]/';
		preg_match($pattern, $ret, $matches);
		if (empty($matches)){
			$pattern =  ' /\"coordinates\"\: \[(.*?), (.*?)\]/';
			preg_match($pattern, $ret, $matches);

		}
		//var_dump($ret);die;
		$this->setLatitude($matches[2]);
		$this->setLongitude($matches[1]);

	}

}


?>