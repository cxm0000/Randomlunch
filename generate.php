<?php

/*
	session_start();
	include_once ("settings.php");
	include_once (SERVER_ROOT. "/class/DbConnection.php");
	include_once (SERVER_ROOT. "/class/Randomizer.php");
	include_once (SERVER_ROOT. "/class/Restaurant.php");
	include_once (SERVER_ROOT. "/class/RestaurantController.php");
	include_once (SERVER_ROOT. "/class/Calculator.php");
	include_once (SERVER_ROOT. "/class/Location.php");
	
	
	
	if (isset($_GET['location'])) {
		
		$restController = new RestaurantController();
		
		##get all restaurants
		$all_rests = $restController->getAllResturant();
	
		$current_location = trim(urldecode($_GET['location']));
		
		$location = new Location($current_location);
		
		##get distance between the current place and all restaurants, store possible ones in an array
		$possible_rests = array();
		foreach ($all_rests as $restaurant){
			##calculate distance
			$from = array($location->getLatitude(), $location->getLongitude());
			$to = array($restaurant->getLatitude(), $restaurant->getLongitude());
			$distance = Calculator::distanceFromCoordinates($from, $to);
			
			if ($distance <= VALID_RANGE){
				$restaurant->setDistance($distance);
				$possible_rests[] = $restaurant;
			}
				
		}
		
		$range_max = sizeof($possible_rests) - 1;
		
		if ($range_max > 0) {
			$random_index = Randomizer::generateNo($range_max);
		
			$random_rest = $possible_rests[$random_index];
			
			//store the restuarant into session value
			$_SESSION['from'] = serialize($location);
			$_SESSION['rest'] = serialize($random_rest);
			
			
		}
		
		//redirect to the display page
		header("location: ".SERVER_ROOT."/test.php");
	}
	
*/
?>