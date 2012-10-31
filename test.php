<?php 
	session_start();
	include_once ("settings.php");
	include_once (SERVER_ROOT. "/class/DbConnection.php");
	include_once (SERVER_ROOT. "/class/Randomizer.php");
	include_once (SERVER_ROOT. "/class/Restaurant.php");
	include_once (SERVER_ROOT. "/class/RestaurantController.php");
	include_once (SERVER_ROOT. "/class/Calculator.php");
	include_once (SERVER_ROOT. "/class/Location.php");
	include_once (SERVER_ROOT."/include/header.php");
?>
<!-- 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Google Maps JavaScript API Example: 	Extraction of Geocoding Data</title>
    <script src="http://maps.google.com/maps?file=api&v=2&key=ABQIAAAAZ4js3UVwf9LegxeHjyaWzxRFY7V_zappoRjfJmHN1m_LIwFKTRSkqZWo8dPAs-Zszfc7lWpKY6Jp4w" type="text/javascript"></script>
--><!-- According to the Google Maps API Terms of Service you are required display a Google map when using the Google Maps API. see: http://code.google.com/apis/maps/terms.html -->
    <script type="text/javascript">
  
    var geocoder, location1, location2;

	function initialize() {
		//geocoder = new GClientGeocoder();
		<?php 
			if (isset($_SESSION['rest'])){
				//var_dump($_SESSION['from']);die;
				$restaurant = unserialize($_SESSION['rest']);
				$from = unserialize($_SESSION['from']);
				//var_dump($from->getLongitude());
				//var_dump($restaurant);die;
				echo "showResult({$from->getLatitude()}, {$from->getLongitude()}, {$restaurant->getLatitude()}, {$restaurant->getLongitude()});";
				//echo "showResult(11.9551420211792, 57.69321977978046, {$restaurant->getLatitude()}, {$restaurant->getLongitude()});";
			}
		?>
		getDirections();
	}
/*
	function showLocation() {
		geocoder.getLocations(document.forms[0].address1.value, function (response) {
			if (!response || response.Status.code != 200)
			{
				alert("Sorry, we were unable to geocode the first address");
			}
			else
			{
				location1 = {lat: response.Placemark[0].Point.coordinates[1], lon: response.Placemark[0].Point.coordinates[0], address: response.Placemark[0].address};
				geocoder.getLocations(document.forms[0].address2.value, function (response) {
					if (!response || response.Status.code != 200)
					{
						alert("Sorry, we were unable to geocode the second address");
					}
					else
					{
						location2 = {lat: response.Placemark[0].Point.coordinates[1], lon: response.Placemark[0].Point.coordinates[0], address: response.Placemark[0].address};
						calculateDistance();
					}
				});
			}
		});
	}
*/


	//===== request the directions =====
	function getDirections() {
 		// ==== Set up the walk and avoid highways options ====
	/*  var opts = {};
	  if (document.getElementById("walk").checked) {
	     opts.travelMode = G_TRAVEL_MODE_WALKING;
	  }
	  if (document.getElementById("highways").checked) {
	     opts.avoidHighways = true;
	  }
	  // ==== set the start and end locations ====
	*/
	  var saddr = document.getElementById("saddr").value
	  var daddr = document.getElementById("daddr").value
	  gdir.load("from: "+saddr+" to: "+daddr, opts);
	}



	function showResult(location1_lat, location1_lon, location2_lat, location2_lon)
	{
		try
		{
			
			//var glatlng1 = new GLatLng(location1_lat, location1_lon);
			//var glatlng2 = new GLatLng(location2_lat, location2_lon);
			var glatlng1 = new GLatLng(location1_lon, location1_lat);
			var glatlng2 = new GLatLng(location2_lon, location2_lat);
			
			var miledistance = glatlng1.distanceFrom(glatlng2, 3959).toFixed(1);
			var kmdistance = (miledistance * 1.609344).toFixed(1);

			 map = new GMap2(document.getElementById("map_canvas"));
			 map.setCenter(glatlng2, 13);
			 var point1 = glatlng1;
			 var point2 = glatlng2;
			 var marker1 = new GMarker(point1);
			 var marker2 = new GMarker(point2);

			 map.addOverlay(marker1);
			 map.addOverlay(marker2);

			 GEvent.addListener(marker1, "click", function() {
				    marker1.openInfoWindowHtml("Here is where you are.");
				});

			 GEvent.addListener(marker2, "click", function() {
				    marker2.openInfoWindowHtml("The restaurant is here.");
				});
			 map.setUIToDefault();
			//document.getElementById('results').innerHTML = '<strong>Address 1: </strong>' + location1.address + '<br /><strong>Address 2: </strong>' + location2.address + '<br /><strong>Distance: </strong>' + miledistance + ' miles (or ' + kmdistance + ' kilometers)';
		}
		catch (error)
		{
			alert(error);
		}
	}

    </script>

<body onload="initialize()">
		<div id="top">
		<div class="center">
		<div>
		<a href=""><img id="confused_smiley" src="image/site/smiley_blue.png" alt="confused" /></a>
		<div id="front">
		<h2>We think you should go here - </h2>
		<div>
		<?php
			echo "name: " . $restaurant->getName() . "<br>";
			echo "city: " . $restaurant->getCity() . "<br>";
			echo "Type: " . $restaurant->getType() . "<br>";
			echo "Phone: " . $restaurant->getPhone() . "<br>";
			echo "latitude: " . $restaurant->getLatitude() . "<br>";
			echo "longitude: " . $restaurant->getLongitude() . "<br>";
			echo "Distance: " . $restaurant->getDistance() . "<br>";
			
		?>
	</div>
		</div>
		</div>
		</div>
		</div>

<div id="bottom">
		<div class="center">
		<div id="map_canvas" style="width: 500px;height:400px;"></div>
		
		</div>
		
</div>
	</body>
</html>

