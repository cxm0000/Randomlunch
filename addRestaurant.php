
<?php 
	include_once ("settings.php");
	include_once (SERVER_ROOT."/include/header.php");
	include_once (SERVER_ROOT."/class/Restaurant.php");
	include_once (SERVER_ROOT."/class/RestaurantController.php");
	include_once (SERVER_ROOT."/class/DbConnection.php");
	include_once (SERVER_ROOT."/class/Location.php");
?>
	<body>
	<div id="top"> 
		<div class="center">
		<a href="prototype.se"><img id="confused_smiley" src="image/site/smiley_blue_happy.png" alt="confused" /></a>
		<div id="front">
		<p>So you wanted to add a restaurant, here you go.</p>
		<form id="addForm" action="addRestaurant.php?add=1" method="post">
		<p>__________________________________</p>
		<p>Give us some data on the restaurant.</p><br>
		<table id="table_addRestaurant">

		<tr>
			<td class="header">Name:</td>
			<td><input name="rest_name" type="text" value="<?PHP echo"{$_POST['rest_name']}"; ?>"/></td>
			<td class="tdspace"></td><td class="header">Provides -</td><td></td>
		</tr>
		<tr>
			<td class="header">Street adress:</td>
			<td><input name="rest_street" type="text" value="<?PHP echo"{$_POST['rest_street']}"; ?>"/></td>
			<td class="tdspace"></td><td>Breakfast</td><td><input name="rest_breakfast" type="checkbox" value=1/></td>
		</tr>
		<tr>
			<td class="header">City:</td>
			<td><input name="rest_city" type="text" value="<?PHP echo"{$_POST['rest_city']}"; ?>"/></td>
			<td class="tdspace"></td><td>Lunch</td><td><input name="rest_lunch" type="checkbox" value=1/></td>
		</tr>
		<tr>
			<td class="header">Zipcode:</td>
			<td><input name="rest_zip" type="text" value="<?PHP echo"{$_POST['rest_zip']}"; ?>"/></td>
			<td class="tdspace"></td><td>Dinner</td><td><input name="rest_dinner" type="checkbox" value=1 /></td>
		</tr>
		<tr>
			<td class="header">Phone:</td>
			<td><input name="rest_phone" type="text" value="<?PHP echo"{$_POST['rest_phone']}"; ?>"/></td>
			
		</tr>
		<tr>
			<td class="header">Fax:</td>
			<td><input name="rest_fax" type="text" value="<?PHP echo"{$_POST['rest_fax']}"; ?>"/></td>
			<td class="tdspace"></td><td class="header">Type of restaurant:</td>
		</tr>
		<tr>
			<td class="header">Homepage:</td>
			<td><input name="rest_website" type="text" value="<?PHP echo"{$_POST['rest_website']}"; ?>"/></td>
			<td class="tdspace"><td>
		    	<select name="rest_type">
		    	  <option value="other">other</option>
				  <option value="sushibar">sushibar</option>
				  <option value="pizzeria">pizzeria</option>
				  <option value="italian">italian</option>
				  <option value="french">french</option>
				  <option value="spanish">spanish</option>
				  <option value="chinese">chinese</option>
				  <option value="japanese">japanese</option>
				  <option value="indian">indian</option>
				  <option value="thai">thai</option>
				  <option value="continental">continental</option>
				  <option value="bar_or_pub">bar or pub</option>
				  <option value="hamburgerbar_or_gatukok">street food</option>
				  <option value="swedish">swedish</option>
				  <option value="greek">greek</option>
				</select>
		    </td>
		    
		</tr>
		<tr></tr>
		<tr>
			<td><input name="btn_form" type="submit" value="Submit" /></td>
		</tr>
		<!-- 
		<tr>
			<td>__________________________________</td>
		</tr>
		<tr>
			<td>Give us some data on contactperson.
		</tr>
		<tr>
			<td>Name:</td>
			<td><input name="contact_name" type="text" value="<?PHP //echo"{$_POST['contact_name']}"; ?>"/></td>
		</tr>
		<tr>
			<td>Phone:</td>
			<td><input name="contact_phone" type="text" value="<?PHP //echo"{$_POST['contact_phone']}"; ?>"/></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><input name="contact_email" type="text" value="<?PHP //echo"{$_POST['contact_email']}"; ?>" /></td>
			<td></td><td><input name="btn_form" type="submit" value="Submit" /></td>
		</tr> -->

		</table>
		<p><?php if (isset($_POST['btn_form'])) { sendInfo(); } ?></p>
		</form>
		</div>
		</div>
		
		
		</div>

<?php 
	
		
	function sendInfo() {
		
		if($_GET['add'] == '1') {
			$restaurantC = new RestaurantController();
			$rest = new Restaurant();
	
			$rest_name = $_POST['rest_name'];
			$rest_street = $_POST['rest_street'];
			$rest_city = $_POST['rest_city'];
			$rest_zip = $_POST['rest_zip'];
			$rest_phone = $_POST['rest_phone'];
			$rest_fax = $_POST['rest_fax'];
			$rest_website = $_POST['rest_website'];
			
			$rest_breakfast = $_POST['rest_breakfast'];
			$rest_lunch = $_POST['rest_lunch'];
			$rest_dinner = $_POST['rest_dinner'];
			
			$rest_type = $_POST['rest_type'];
			
			//$contact_name = $_POST['contact_name'];
			//$contact_phone = $_POST['contact_phone'];
			//$contact_email = $_POST['contact_email'];
			
			if (($rest_name == null) || ($rest_street == null) || ($rest_city == null))	{
					echo"<div class=\"error\">Please fill in at least name, street adress and city of the restaurant.</div>";
			} else {
				
				$current_location = trim(urldecode($rest_street));	
				$location = new Location($current_location);
				
				$rest->setCity($rest_city);
				$rest->setFax($rest_fax);
				$rest->setHas_breakfast($rest_breakfast);
				$rest->setHas_lunch($rest_lunch);
				$rest->setHas_dinner($rest_dinner);
				$rest->setWebsite($rest_website);
				$rest->setName($rest_name);
				$rest->setPhone($rest_phone);
				$rest->setStreet($rest_street);
				$rest->setType($rest_type);
				$rest->setWebsite($rest_website);
				$rest->setZip($rest_zip);
			
				if($restaurantC->saveNewRestaurant($rest) == true) {
					//header("Location: addRestaurant.php?add=2");
					echo"Thank you, the restaurant is now added into our database.";
					$to = "givemelunch@randomlunch.se";
					//$to = "josefine.ottosson@gmail.com";
					$subject = "New restaurant!";
					$body = "Hi,\n\na new restaurant has showed up in the database. It has id nr ".$rest->getId();
					if (mail($to, $subject, $body)) {
					  //echo("<p>Message successfully sent!</p>");
					 } else {
					  //echo("<p>Message delivery failed...</p>");
					 }
 
				} else {
					echo"Something went wrong, please try again later.";
				}
				
			} 
		} 
		
	}
?>


<?php include_once ("include/bottom.php"); ?>


