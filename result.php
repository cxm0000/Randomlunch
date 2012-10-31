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
 <script type="text/javascript">
  
    var geocoder, location1, location2;

	function initialize() {
		
		<?php 
			if (isset($_SESSION['rest'])){
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


	function showResult(location1_lat, location1_lon, location2_lat, location2_lon)
	{
		try
		{
			
			var glatlng1 = new GLatLng(location1_lat, location1_lon);
			var glatlng2 = new GLatLng(location2_lat, location2_lon);
			//var glatlng1 = new GLatLng(location1_lon, location1_lat);
			//var glatlng2 = new GLatLng(location2_lon, location2_lat);
			
			var miledistance = glatlng1.distanceFrom(glatlng2, 3959).toFixed(1);
			var kmdistance = (miledistance * 1.609344).toFixed(1);

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
			//var gString = 'from: ' + location1_lon +',' + location1_lat + ' to:' + location2_lon +',' + location2_lat;
			 
			 var gString = 'from: <?=$_SESSION['location']?>, sweden to:<?=(isset($restaurant))? $restaurant->getStreet(): ''?>, sweden';
			
			directions.load(gString);

			 		 
			
	/*		 var point1 = glatlng1;
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
	*/
			 map.setUIToDefault();
			
		}
		catch (error)
		{
			alert(error);
		}
	}

    </script>
<?php 
	function getDistance($dist) {
var_dump($dist);
		$newDist = explode('.',$dist);
		$newDist = preg_match("/^([0-9]{3})/",$newDist[1], $saveDist);
die(var_dump($saveDist[0]));
		return $saveDist[0];
	}
	
?>
	
	<body onload="initialize()">
	
		<div id="top">
			<div class="center">
				<?php
					if(isset($restaurant)) {
				?>
				
				<div>
					<a href="prototype.php"><img id="confused_smiley" src="image/site/smiley_blue_happy.png" alt="confused" /></a>
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
							<? $site=$restaurant->getWebsite();?>
							<? if(!empty($site)):?>
							<tr>
								<td class="header">Web</td><td><?php echo $site ?></td>
							</tr>
							<? endif;?>							
							</table>
							<br />
							<br />
							<p>____________________________</p>
							<br />
							<p class="header">I don't like it, give me a new one -</p>
							<br />
							<form action="gen2.php" method="get">
								<input id="input_query" type="text" style="display:none" name="location" value="<?php echo $_SESSION['location']; ?>" />
								<input id="btn_getLunchPlace" type="submit" value="" />
							</form>
						</div>
					</div>
				</div>
			<?php }
				else{
						print '<p id="result">' . $_SESSION['result'] . '</p>';
					}
			?>
			</div>
		</div>
	<?php if(!isset($_SESSION['result']) && isset($restaurant)):?>
		<div id="bottom">
			<div class="center">
				<p class="map_result">The map shows you how to drive to the restaurant.</p>
				<p class="map_result">From where you are now you have exactly<span class="distinguish"> <?php echo $restaurant->getDistance() ?> </span>Km to this restaurant.</p>
				<div id="map_canvas" style="width: 500px;height:400px;"></div>
			</div>
		</div>

	</body>
</html>
	<?php else:?>
	<?php include_once("include/bottom.php");?>
	<?php unset($_SESSION['result']);?>
	<?php endif;?>