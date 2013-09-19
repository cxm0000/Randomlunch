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

		<!-- According to the Google Maps API Terms of Service you are required display a Google map when using the Google Maps API. see: http://code.google.com/apis/maps/terms.html -->
		<script type="text/javascript"
			src="https://maps.googleapis.com/maps/api/js?key=<?=MAP_KEY?>&sensor=false">
		</script>
    	<script type="text/javascript" src="<?=WEB_ROOT?>/js/script.js"></script>

<script type="text/javascript">

	var geocoder, location1, location2;

	function initialize() {

		<?php
		if (isset($_SESSION['rest'])) {

			$restaurant = json_decode($_SESSION['rest']);
			$from = json_decode($_SESSION['from']);

			echo "showResult({$from->latitude},{$from->longitude}, {$restaurant->latitude}, {$restaurant->longitude});";

		}
		?>
			   //getDirections();
	}

	function showResult(location1_lat, location1_lon, location2_lat, location2_lon) {
		try
		{
			var myLocation = new google.maps.LatLng(location1_lat, location1_lon);
			var toLocation = new google.maps.LatLng(location2_lat, location2_lon);

			var mapOptions = {
				center: new google.maps.LatLng(location1_lat, location1_lon),
				zoom: 13
			};

			var map = new google.maps.Map(
				document.getElementById("map_canvas"),
				mapOptions
			);

			var directionsService = new google.maps.DirectionsService();
			var directionsDisplay = new google.maps.DirectionsRenderer();

			var request = {
				origin: myLocation,
				destination: toLocation,
				travelMode: google.maps.TravelMode.WALKING,
				unitSystem: google.maps.UnitSystem.METRIC
			};

			directionsDisplay.setMap(map);
			directionsService.route(request, function(result, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					directionsDisplay.setDirections(result);
				}
			});

		} catch (error) {
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
					<a href="/"><img id="confused_smiley" src="image/site/smiley_blue_happy.png" alt="confused"></a>
					<div id="front">
						<h2>We think you should go here - </h2>
						<div id="result">
							<table>
								<tr>
									<td class="header">Name</td><td><?php echo $restaurant->name ?></td>
								</tr>
								<tr>
									<td class="header">City</td><td><?php echo $restaurant->mailCity ?></td>
								</tr>
								<tr>
									<td class="header">Type</td><td><?php echo $restaurant->type ?></td>
								</tr>
								<tr>
									<td class="header">Phone</td><td><?php echo $restaurant->phone ?></td>
								</tr>
								<? $site = $restaurant->website; ?>
								<? if (!empty($site)): ?>
									<tr>
										<td class="header">Web</td><td><a href="http://<?php echo $site ?>"><?php echo $site ?></a></td>
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
				<p class="map_result">From where you are now you have exactly<span class="distinguish"> <?php echo $restaurant->distanceInMeters ?> </span>m to this restaurant.</p>
				<div id="map_canvas" style="width: 800px;height:600px;"></div>
			</div>
		</div>
<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
	try {
		var pageTracker = _gat._getTracker("UA-3987274-4");
		pageTracker._trackPageview();
	} catch(err) {}
</script>
	</body>
	</html>
<?php else: ?>
	<?php include_once("include/bottom.php"); ?>
	<?php unset($_SESSION['result']); ?>
<?php endif; ?>