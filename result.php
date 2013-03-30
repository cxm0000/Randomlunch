<?php
session_start();
include_once ("settings.php");
include_once (SERVER_ROOT . "/class/DbConnection.php");
include_once (SERVER_ROOT . "/class/Randomizer.php");
include_once (SERVER_ROOT . "/class/Restaurant.php");
include_once (SERVER_ROOT . "/class/RestaurantController.php");
include_once (SERVER_ROOT . "/class/Calculator.php");
include_once (SERVER_ROOT . "/class/Location.php");
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Random Lunch</title>
	 	<meta charset="UTF-8" />
		<meta name="author" content="randomlunch.se">
		<meta name="description" content="Randomlunch is a place where a random lunch restaurant suggestion will be made depending on your current location. Now the service is only available in Sweden.">
		<meta name="keywords" content="random lunch, lunch confused, where to eat, what for lunch, random restaurant, lunch suggestion">
		<meta name="publisher" content="randomlunch.se">
		<meta name="revisit-after" content="2 days">
		<meta name="robots" content="index, follow">
		<link rel="stylesheet" type="text/css" href="<?=WEB_ROOT?>/css/style.css" media="all">
		<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAZ4js3UVwf9LegxeHjyaWzxRFY7V_zappoRjfJmHN1m_LIwFKTRSkqZWo8dPAs-Zszfc7lWpKY6Jp4w" type="text/javascript"></script>
		<!-- According to the Google Maps API Terms of Service you are required display a Google map when using the Google Maps API. see: http://code.google.com/apis/maps/terms.html -->
    	<script type="text/javascript" src="<?=WEB_ROOT?>/js/script.js"></script>

<script type="text/javascript">

	var geocoder, location1, location2;

	function initialize() {

		<?php
		if (isset($_SESSION['rest'])) {
			//var_dump($_SESSION['from']);die;
			$restaurant = unserialize($_SESSION['rest']);
			$from = unserialize($_SESSION['from']);
			//var_dump($from);
			//var_dump($from->getLongitude());
			//var_dump($restaurant);die;
			echo "showResult({$from->getLongitude()}, {$from->getLatitude()}, {$restaurant->getLongitude()}, {$restaurant->getLatitude()});";
			//echo "showResult(11.9551420211792, 57.69321977978046, {$restaurant->getLatitude()}, {$restaurant->getLongitude()});";
		}
		?>
			   //getDirections();
	}

	function showResult(location1_lat, location1_lon, location2_lat, location2_lon) {
		try
		{

			var glatlng1 = new GLatLng(location1_lat, location1_lon);
			var glatlng2 = new GLatLng(location2_lat, location2_lon);
			//var glatlng1 = new GLatLng(location1_lon, location1_lat);
			//var glatlng2 = new GLatLng(location2_lon, location2_lat);

			var miledistance = glatlng1.distanceFrom(glatlng2, 3959).toFixed(1);
			var kmdistance = (miledistance * 1.609344).toFixed(1);

			var defaultTravelMode = "G_TRAVEL_MODE_WALKING";
			var defaultLocale = "sv_SE";

			map = new GMap2(document.getElementById("map_canvas"));
			map.setCenter(glatlng2, 13);

			directions = new GDirections(map, null);
			/*	 GEventListener listener = GEvent.addListener(directions, 'load',new
			 GEventHandler(){
		//using prototype, u can use any other to attach event
		alert(directions.getDistance().meters);
		//display results
		});
			 */
			var gString = 'from: ' + location1_lon +',' + location1_lat + ' to:' + location2_lon +',' + location2_lat;

			//			 var gString = 'from: <?= $_SESSION['location'] ?>, sweden to:<?= (isset($restaurant)) ? $restaurant->getStreet() : '' ?>, sweden';

			var queryOptions = {"locale": defaultLocale, "travelMode": defaultTravelMode};

			directions.load(gString, queryOptions);

			map.setUIToDefault();

		}
		catch (error)
		{
			alert(error);
		}
	}

</script>

</head>
<body onload="initialize()">

	<div id="top">
		<div class="center">
<?php
if (isset($restaurant)) {
	?>

				<div>
					<a href="prototype.php"><img id="confused_smiley" src="image/site/smiley_blue_happy.png" alt="confused"></a>
					<div id="front">
						<h2>We think you should go here - </h2>
						<div id="result">
							<table>
								<tr>
									<td class="header">Name</td><td><?php echo $restaurant->getName() ?></td>
								</tr>
								<tr>
									<td class="header">City</td><td><?php echo $restaurant->getMail_City() ?></td>
								</tr>
								<tr>
									<td class="header">Type</td><td><?php echo $restaurant->getType() ?></td>
								</tr>
								<tr>
									<td class="header">Phone</td><td><?php echo $restaurant->getPhone() ?></td>
								</tr>
								<? $site = $restaurant->getWebsite(); ?>
								<? if (!empty($site)): ?>
									<tr>
										<td class="header">Web</td><td><?php echo $site ?></td>
									</tr>
								<? endif; ?>
							</table>
							<br>
							<br>
							<p>____________________________</p>
							<br>
							<p class="header">I don't like it, give me a new one -</p>
							<br>
							<form action="gen2.php" method="get">
								<input id="input_query" type="text" style="display:none" name="location" value="<?php echo $_SESSION['location']; ?>">
								<input id="btn_getLunchPlace" type="submit" value="">
							</form>
						</div>
					</div>
				</div>
<?php
}
else {
	print '<p id="result">' . $_SESSION['result'] . '</p>';
}
?>
		</div>
	</div>
	<?php if (!isset($_SESSION['result']) && isset($restaurant)): ?>
		<div id="bottom">
			<div class="center">
				<p class="map_result">The map shows you how to drive to the restaurant.</p>
				<p class="map_result">From where you are now you have exactly<span class="distinguish"> <?php echo $restaurant->getDistanceInMeters() ?> </span>m to this restaurant.</p>
				<div id="map_canvas" style="width: 800px;height:600px;"></div>
			</div>
		</div>

	</body>
	</html>
<?php else: ?>
	<?php include_once("include/bottom.php"); ?>
	<?php unset($_SESSION['result']); ?>
<?php endif; ?>