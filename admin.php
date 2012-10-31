<?php 
	include_once ("settings.php");
	include_once (SERVER_ROOT."/include/header.php");
	include_once (SERVER_ROOT."/class/Restaurant.php");
	include_once (SERVER_ROOT."/class/RestaurantController.php");
	include_once (SERVER_ROOT."/class/DbConnection.php");
	include_once (SERVER_ROOT."/class/Location.php");
	
	
	$restaurantC = new RestaurantController();
	var_dump($restaurantC); die();
	//$rest_list = $restaurantC->getNotApprovedRestaurants();
	

	//var_dump($rest_list);

?>