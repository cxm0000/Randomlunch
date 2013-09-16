<?php

	session_start();
	include_once ("settings.php");
	include_once (SERVER_ROOT. "/class/DbConnection.php");
	include_once (SERVER_ROOT. "/class/Randomizer.php");
	include_once (SERVER_ROOT. "/class/Restaurant.php");
	include_once (SERVER_ROOT. "/class/RestaurantController.php");
	include_once (SERVER_ROOT. "/class/Calculator.php");
	include_once (SERVER_ROOT. "/class/Location.php");

	if (isset($_REQUEST['location'])) {
		$_SESSION['location'] = trim(urldecode($_REQUEST['location']));
		unset($_SESSION['from'] );
		unset($_SESSION['rest'] );
		generateRestaurant($_REQUEST['location']);
	}
/*
	if (isset($_GET['location'])) {
		$_SESSION['location'] = $_GET['location'];
		generateRestaurant($_GET['location']);
	}
*/
	function generateRestaurant($searchLocation) {
		$restController = new RestaurantController();
		$searchGeoLocation = null;
		##get all restaurants
		$all_rests = $restController->getAllResturant();

		$searchGeoLocation = new Location($searchLocation);

		##check if there are coordinates of the location
		if(!($searchGeoLocation->getLatitude() == 0 && $searchGeoLocation->getLongitude() == 0)){
			##get distance between the current place and all restaurants, store possible ones in an array
			$possible_rests = array();

			foreach ($all_rests as $restaurant){

				##calculate distance
				$fromGeoLocation = array($searchGeoLocation->getLatitude(), $searchGeoLocation->getLongitude());
				$toGeoLocation = array($restaurant->getLatitude(), $restaurant->getLongitude());


				$distance = Calculator::distanceFromCoordinates(
					$searchGeoLocation->getLatitude(),
					$searchGeoLocation->getLongitude(),
					$restaurant->getLatitude(),
					$restaurant->getLongitude()
				);

				if ($distance <= VALID_RANGE && $distance > 0){
					$restaurant->setDistance($distance);
					$possible_rests[] = $restaurant;
				}

			}

			//below comes to randomize all the possible results
			$range_max = sizeof($possible_rests) - 1;
			if ($range_max > 0) {
				$random_index = Randomizer::generateNo($range_max);

				$random_rest = $possible_rests[$random_index];
//var_dump($random_rest);die;

				$_SESSION['from'] = $searchGeoLocation->toJSON();
				$_SESSION['rest'] = $random_rest->toJSON();
			}else
				//no possible restaurant
				$_SESSION['result'] = 'Sorry, we can not find any restaurant within ' .VALID_RANGE . ' km, please try specify your address more detailed.';
		}else
			$_SESSION['result'] = 'Sorry, we can not locate the location that you provided, please try specify it more detailed.';

		//redirect to the display page
		header("location: result.php");
	}
?>