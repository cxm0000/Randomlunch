<?php

	include_once ("settings.php");
	include_once ("class/DbConnection.php");
	include_once ("class/Resturant.php");
	include_once ("class/ResturantController.php");

	if ($_GET['location']) {
		$location = urlencode($_GET['location']);
		$url = MAP_BASE_URL . "?q=$location&output=json&oe=utf8&sensor=false&key=" . MAP_KEY;
		$ret = file($url);
		
		//$tmp = explode("\"", $ret[1]);
		//$name = $tmp[3];
		
		$tmp = explode("\"", $ret[8]);
		$address = $tmp[3];
		
		$pattern = '/([0-9]*.[0-9]*), ([0-9]*.[0-9]*)/';
		preg_match($pattern, $ret[19], $matches);
		
		$restArr = array();
		$restController = new ResturantController();
		$current_location = new Resturant();
		
		$current_location->setLat($matches[1]);
		$current_location->setLong($matches[2]);
		
		$restArr = $restController->getAllResturant();
		
		foreach ($restArr as $resturant){
			$r_lat = $resturant->getLat();
			$r_long = $resturant->getLong();
			$c_lat = $current_location->getLat();
			$c_long = $current_location->getLong();
			
			$distance = sqrt(($r_lat - $c_lat) * ($r_lat - $c_lat) + ($r_long - $c_long) * ($r_long - $c_long));
	//		echo $distance . '<br>';
		}
		
		//$resturant->setName($name);
	/*	$resturant->setLat($matches[1]);
		$resturant->setLong($matches[2]);
		$resturant->setAddress($address);
		
		if($restController->isNewResturant($resturant));
			$restController->saveNewResturant($resturant);
	*/	
	}else 
		echo 'No get data';
	
	


?>