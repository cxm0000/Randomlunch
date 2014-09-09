<?php
include_once "RestaurantBuilder.php";

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
	
	public function getNearByPlaces() {
		// eg. json?location=51.503186,-0.126446&radius=5000&types=museum&key=AddYourOwnKeyHere
		// eg. json?location=-33.8670522,151.1957362&radius=500&types=food&name=cruise&key=AddYourOwnKeyHere
		$placeApiUrlPattern = '%s/json?location=%s,%s&radius=%s&types=restaurant&key=%s';
		$apiURL = sprintf(
			$placeApiUrlPattern,
			PLACE_BASE_URL,
			$this->getLatitude(),
			$this->getLongitude(),
			VALID_RANGE,
			MAP_KEY
		);
//		var_dump($apiURL);die;
		$s = curl_init();

		// set search options
		curl_setopt($s,CURLOPT_URL, $apiURL);
		curl_setopt($s, CURLOPT_HTTPHEADER, self::$requestHeaders);
		curl_setopt($s,CURLOPT_RETURNTRANSFER,true);

		$ret = curl_exec($s);

		$retJson = json_decode($ret);
		
		$results = $retJson->results;
		
		$ret = array();
		if (!empty($results)) {
			$builder = new RestaurantBuilder();
			
			foreach ($results as $stdResult) {
//				print_r($stdResult);die;
				$data = $this->parseGooglePlaceResult($stdResult);
				$ret[] = $builder->buildRestaurant($data);
			}
			
		}

		return $ret;
	}
	
	protected function parseGooglePlaceResult(stdClass $googleResult)
	{
		$ret = array();
		if (isset($googleResult->geometry->location->lat)) {
			$ret['lat'] = $googleResult->geometry->location->lat;
		}
		
		if (isset($googleResult->geometry->location->lng)) {
			$ret['lng'] = $googleResult->geometry->location->lng;
		}
		
		if (isset($googleResult->icon)) {
			$ret['icon'] = $googleResult->icon;
		}
		
		if (isset($googleResult->name)) {
			$ret['name'] = $googleResult->name;
		}
		
		if (isset($googleResult->place_id)) {
			$ret['place_id'] = $googleResult->place_id;
		}
		
		if (isset($googleResult->vicinity)) {
			$ret['vicinity'] = $googleResult->vicinity;
		}

		return $ret;
	}

}


?>