<?php

class Calculator {
	
	static function distanceFromCoordinates(Array $from, Array $to){
		
		$lat1 = $from[0]; 
		$lon1 = $from[1]; 
		
		$lat2 = $to[0]; 
		$lon2 = $to[1]; 
		
		$lat1 = $from[1]; 
		$lon1 = $from[0]; 
		
		$lat2 = $to[1]; 
		$lon2 = $to[0]; 
		
		$distance = (3958*3.1415926*sqrt(($lat2-$lat1)*($lat2-$lat1) + cos($lat2/57.29578)*cos($lat1/57.29578)*($lon2-$lon1)*($lon2-$lon1))/180);
		
		return $distance;
	}
	
	
	static function routeDistance( $from,  $to){
		
		
		$lat1 = trim($from[0]); 
		$lon1 = trim($from[1]); 
		
		$lat2 = trim($to[0]); 
		$lon2 = trim($to[1]); 
			
		$from=$lon1 .',' . $lat1 ;
		$to=$lon2 .',' . $lat2;	
	
		$url = "http://maps.google.com/maps/api/directions/xml?origin={$from}&destination={$to}&sensor=false";

		$s = curl_init();
			
		// set search options
		curl_setopt($s,CURLOPT_URL, $url);
		curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
		
		$ret = curl_exec($s);

		try{
			$data = new SimpleXMLElement($ret);
	
			$timeString = $data->route->leg->duration->text;
	
			$distance = ($data->route->leg->distance->value)/1000;
	
		} catch (Exception $e) {
			//die($e->getMessage() . "RET: " . print_r($ret));
		}
		
		return $distance;
		
	}
	
	
}


?>