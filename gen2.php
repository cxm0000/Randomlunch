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
		$_SESSION['location'] = $_REQUEST['location'];
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
	function generateRestaurant($rest) {
		$restController = new RestaurantController();
		$location = null;
		##get all restaurants
		$all_rests = $restController->getAllResturant(10);
	
		$current_location = trim(urldecode($rest));
		
		$location = new Location($current_location);
		
		##check if there are coordinates of the location
		if(!($location->getLatitude() == 0 && $location->getLongitude() == 0)){
			##get distance between the current place and all restaurants, store possible ones in an array
			$possible_rests = array();
			

			foreach ($all_rests as $restaurant){
				
				
				##calculate distance
				$from = array($location->getLatitude(), $location->getLongitude());
				$to = array($restaurant->getLatitude(), $restaurant->getLongitude());
				
		
		//		$distance = Calculator::distanceFromCoordinates($from, $to);
				$distance = Calculator::routeDistance($from, $to);

//				echo '<br>'.$restaurant->getName() . ':'. $distance;
				if ($distance <= VALID_RANGE && $distance > 0){
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
				
				
			}else
				//no possible restaurant
				$_SESSION['result'] = 'Sorry, we can not find any restaurant within ' .VALID_RANGE . ' km, please try specify your address more detailed.';

		}else
			$_SESSION['result'] = 'Sorry, we can not locate the location that you provided, please try specify it more detailed.';
		//redirect to the display page
		header("location: result.php");
	}
?>