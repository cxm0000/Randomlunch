<?php

/**
 * Description of newPHPClass
 *
 * @author ming
 */
class RestaurantBuilder {
	
	
	function __construct() {
		
	}
	
	/**
	 * Builds a new restaurant intstance based on the given data
	 *
	 * @param array $restaurantData
	 *
	 * @return \Restaurant
	 */
	public function buildRestaurant(array $restaurantData) {
		$restaurant = new Restaurant();
		
		if (isset($restaurantData['lat'])) {
			$restaurant->setLatitude($restaurantData['lat']);
		}
		
		if (isset($restaurantData['lng'])) {
			$restaurant->setLongitude($restaurantData['lng']);
		}
		
		if (isset($restaurantData['icon'])) {
			$restaurant->setImageUrl($restaurantData['icon']);
		}
		
		if (isset($restaurantData['name'])) {
			$restaurant->setName($restaurantData['name']);
		}
		
		if (isset($restaurantData['place_id'])) {
			$restaurant->setGooglePlaceId($restaurantData['place_id']);
		}
		
		if (isset($restaurantData['vicinity'])) {
			$restaurant->setGoogleAddress($restaurantData['vicinity']);
		}

		return $restaurant;
	}

	
}
