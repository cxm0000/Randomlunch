<?php if(!isset($_GET['start_importing'])):?>
<form action="" method="get">
	<input type="submit" name="start_importing" value="Start importing">

</form>
<?php else:?>


<?php 
	include_once ("settings.php");
	include_once (SERVER_ROOT. "/class/DbConnection.php");
	include_once (SERVER_ROOT. "/class/Randomizer.php");
	include_once (SERVER_ROOT. "/class/Restaurant.php");
	include_once (SERVER_ROOT. "/class/RestaurantController.php");
	include_once (SERVER_ROOT. "/class/Calculator.php");
	include_once (SERVER_ROOT. "/class/Location.php");
	
	
	function updateCoordinates($id, $lat, $longi){
		$query ="UPDATE restaurant SET latitude = '$lat', longitude = '$longi' WHERE id = $id LIMIT 1";
		$mysqli = DbConnection::getInstance();
		
		if (!$mysqli->query($query))
			die($mysqli->error);
			
		return true;
	}
	
	
		
	$restController = new RestaurantController();
	$country = 'Sweden';
	
	##get all restaurants
	$all_rest = $restController->getAllResturant();
	
	$i=1;
	foreach ($all_rest as $restaurant){
		##form address
		$street = $restaurant->getStreet();
		$mail_city = $restaurant->getMail_city();
		$address = urlencode($street . ' , ' . $mail_city . ' , ' . $country);
		
		$url = MAP_BASE_URL . "?q=$address&output=json&oe=utf8&sensor=false&key=" . MAP_KEY;
			
		$s = curl_init();
	
		// set search options
		curl_setopt($s,CURLOPT_URL, $url);
		curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
		
		$ret = curl_exec($s);	
			
		
	
		$pattern = ' /\"coordinates\"\: \[(.*?), (.*?), (.*?)\]/';
		preg_match($pattern, $ret, $matches);
		if (empty($matches)){
			$pattern =  ' /\"coordinates\"\: \[(.*?), (.*?)\]/';
			preg_match($pattern, $ret, $matches);
			
		}
			
		
		
		$restaurant->setLatitude($matches[1]);
		$restaurant->setLongitude($matches[2]);
			
		updateCoordinates($restaurant->getId(), $restaurant->getLatitude(), $restaurant->getLongitude());
		
		echo "done :$i<br>";
		$i++;
		sleep(0.005);
		//print 'L: ' . $restaurant->getLatitude() . '; Long: ' . $restaurant->getLongitude(); die;
	}
	
	echo 'all done!';

	





?>
<?php endif;?>