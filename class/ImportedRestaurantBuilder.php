<?php

include 'Restaurant.php';
include 'RestaurantController.php';

/**
 * Description of ImportedRestuarantBuilder
 *
 * @author Xiaoming Cai <ming.cxm@gmail.com>
 */
class ImportedRestaurantBuilder
{

	private $restaurantsJson = '';
	private $totalImport = 0;

	function __construct($restaurantsJson)
	{
		$this->restaurantsJson = $restaurantsJson;
	}

	public function import()
	{
		if (!empty($this->restaurantsJson->total)) {
			echo "Got {$this->restaurantsJson->total} restaurants! Starting parsing json data ....<br/>";
			$this->totalImport = $this->restaurantsJson->total;

			$resController = new RestaurantController();

			if (!empty($this->restaurantsJson->businesses)) {
				$resController->startTransaction();

				foreach ($this->restaurantsJson->businesses as $restaurantInfo) {
					try {
						$restaurant = $this->buildRandomlunchRestaurantModule(
							$restaurantInfo->name,
							$restaurantInfo->url,
							$restaurantInfo->display_phone,
							$restaurantInfo->image_url,
							$restaurantInfo->location->coordinate->latitude,
							$restaurantInfo->location->coordinate->longitude,
							$restaurantInfo->categories[0][0],
							$restaurantInfo->location->city,
							$restaurantInfo->location->postal_code,
							$restaurantInfo->location->address[0]
						);

						echo "Starting saving restaurant:" . $restaurant->getName() . "<br/>";
						$resController->saveNewRestaurant($restaurant);
						echo "Done!<br/>";
					} catch (Exception $e) {
						echo "Roll back transaction....<br/>";
						$resController->rollBackTransaction();
						die($e->getMessage());
					}
				}
				$resController->commitTransaction();
			} else {
				echo "No restaurants found .....<br/>";
			}
		} else {
			echo "No result found .....<br/>";
		}


	}

	private function buildRandomlunchRestaurantModule($name, $siteUrl, $phone, $imageUrl,
		$latitude, $longitude, $type, $mailCity, $postCode, $address )
	{
//		var_dump($name, $siteUrl, $phone, $imageUrl, $latitude, $longitude, $type, $mailCity, $postCode, $address);
		$restaurant = new Restaurant();
		$restaurant->setName($name)
			->setWebsite($siteUrl)
			->setPhone($phone)
			->setImageUrl($imageUrl)
			->setLatitude($latitude)
			->setLongitude($longitude)
			->setType($type)
			->setCity($mailCity)
			->setZip($postCode)
			->setStreet($address);

		return $restaurant;

	}

}

?>
