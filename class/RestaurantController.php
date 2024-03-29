<?php

class RestaurantController {

	private $mysqli = null;

	function RestaurantController() {
		$this->mysqli = DbConnection::getInstance();
	}

	public function getAllResturant($limit = null){
		$restaurants = array();

		$query = "SELECT
						*,
						restaurant.name AS rest_name,
						restauranttype.name AS typename,
						city.name AS cityname
				FROM
						restaurant
				INNER JOIN
						restauranttype_join_restaurant
				ON (
						restaurant.id = restauranttype_join_restaurant.restaurant_id
					)
				INNER JOIN
						restauranttype
				ON (
						restauranttype_join_restaurant.restauranttype_id = restauranttype.id
					)
				INNER JOIN
					city
				ON (
					city.id = restaurant.city
				)
				WHERE
					restaurant.latitude <> 0
				AND
					restaurant.longitude <> 0
		";
		if($limit) {
			$query .= ' LIMIT ' . $limit;
		}

		$result = $this->mysqli->query($query);
		while ($row = $result->fetch_object()) {
			$restaurant = new Restaurant();
			$restaurant->setId($row->id);
			$restaurant->setName($row->rest_name);
			$restaurant->setLatitude($row->latitude);
			$restaurant->setLongitude($row->longitude);
			$restaurant->setWebsite($row->website);
			$restaurant->setCity($row->mailcity);
			$restaurant->setFax($row->fax);
			$restaurant->setHas_breakfast($row->hasbreakfast);
			$restaurant->setHas_dinner($row->hasdinner);
			$restaurant->setHas_lunch($row->haslunch);
			$restaurant->setHittaURL($row->hittaURI);
			$restaurant->setMail_city($row->mailcity);
			$restaurant->setPhone($row->phone);
			$restaurant->setStreet($row->street);
			$restaurant->setType($row->typename);
			$restaurant->setZip($row->zipcode);

			$restaurants[] = $restaurant;
		}
		unset($result);
		unset($row);

		return $restaurants;

	}

	/*
	 * This method is used when a user has added a restaurant
	 *
	 *	Dont forget to add a query for type!
	 */
	public function saveNewRestaurant(Restaurant $res){
		$city = strtolower($res->getCity());
		if ($city == 'göteborg' || $city == 'goteborg' || $city == 'gÃ–teborg'){
			$city = 'gothenburg';
		}
		$res->setCity($city);
		/*
		 * This is if we're going to add a search before the insert to check if the rest exists
		$query = "SELECT id FROM restaurant WHERE name ='{$res->getName()}' LIMIT 1";

		var_dump($this->mysqli->query($query)); die();
		if (null == $this->mysqli->query($query)){
			var_dump($this->mysqli->query($query)); die();

				die($this->mysqli->error);
				return false;}
			else {
				if ($this->mysqli->affected_rows == 0) {
					$row = $result->fetch_object();
					$city = $row->city;
				}
			}
		*/

		$query = "SELECT id FROM city WHERE name = '$city' LIMIT 1";
		$result = $this->mysqli->query($query);
		$row = $result->fetch_object();

		if(null == $row->id) {
			echo "We do not support this city yet, plz come back later. City name:" . $city;
			return false;
		} else {
			$res->setCity($row->id);
			$query = sprintf("INSERT INTO restaurant(

				name,
				city,
				zipcode,
				street,
				phone,
				fax,
				website,
				latitude,
				longitude,
				hasbreakfast,
				haslunch,
				hasdinner,
				approved,
				hittaId
				)VALUES (
				'%s',
				'%d',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%d',
				'%s'

				)",
				$res->getName(),
				$res->getCity(),
				$res->getZip(),
				$res->getStreet(),
				$res->getPhone(),
				$res->getFax(),
				$res->getWebsite(),
				$res->getLatitude(),
				$res->getLongitude(),
				$res->getHas_breakfast(),
				$res->getHas_lunch(),
				$res->getHas_dinner(),
				1,
				$res->getHittaId()
				);

				if (!$this->mysqli->query($query)) {
					die($this->mysqli->error);
				} else {
					$rest_id = $this->mysqli->insert_id;
					$res->setId($rest_id);
					$query = "SELECT id FROM restauranttype WHERE name = '{$res->getType()}' LIMIT 1";
					$result = $this->mysqli->query($query);
					$row = $result->fetch_object();

					if(null == $row->id) {
						echo "We do not support this type yet, plz come back later. Type name:" . $res->getType();
						return false;
					} else {
						$query = sprintf("INSERT INTO restauranttype_join_restaurant (
						restauranttype_id,
						restaurant_id

						) VALUES (
						'%d',
						'%d'
						)",
						$row->id,
						$rest_id
						);

						if (!$this->mysqli->query($query)) {
							die($this->mysqli->error);
						} else {
							return true;
						}

					}

				}
		}
	}

	/*
	 * This method is used to get ONE restaurant by name
	 * now its used together with adding a restaurant - noot anymore
	 * could be nice if we in the future would have a search funtion
	 * before they add a new one they could search for it if it exists
	 */
	public function getOneRestaurant($name){
		$query = "SELECT r.*, c.name as city_name FROM restaurant r JOIN city c ON r.city = c.id WHERE r.name ='{$name}' ";

		$result = $this->mysqli->query($query);
		while ($row = $result->fetch_object()) {
			$restaurant = new Restaurant();
			$restaurant->setId($row->id);
			$restaurant->setName($row->name);
			$restaurant->setLatitude($row->latitude);
			$restaurant->setLongitude($row->longitude);
			$restaurant->setWebsite($row->website);
			$restaurant->setCity($row->city);
			$restaurant->setFax($row->fax);
			$restaurant->setHas_breakfast($row->hasbreakfast);
			$restaurant->setHas_dinner($row->hasdinner);
			$restaurant->setHas_lunch($row->haslunch);
			$restaurant->setHittaURL($row->hittaURL);
			$restaurant->setMail_city($row->mailcity);
			$restaurant->setPhone($row->phone);
			$restaurant->setStreet($row->street);
			$restaurant->setType($row->type_name);
			$restaurant->setZip($row->zipcode);

		}
		unset($result);
		unset($row);

		return $restaurant;
	}

	/*
	 * This method could be used to approve a restaurant
	 * not sure yet..
	 *
	 */
	public function approveNewRestaurant(restaurant $res){

		$query = sprintf("INSERT INTO restaurant(

											latitude,
											longitude,
											image,
											link,
											approved
										)VALUES (

											'%s',
											'%s',
											'%s',
											'%s',
											'%d'
										)",
											$res->getLat(),
											$res->getLong(),
											$res->getImage(),
											$res->getLink(),
											$res->getApproved()
					);

		if (!$this->mysqli->query($query))
			die($this->mysqli->error);

	}

	/*
	 * This method is used to get a list of all new restaurants in db
	 *
	 */
	public function getNotApprovedRestaurants() {
		$query = "SELECT * FROM restaurant WHERE approved = 1";
		$rest_list;
		$result = $this->mysqli->query($query);
		$i = 0;
		while ($row = $result->fetch_object()) {
			$restaurant = new Restaurant();
			$restaurant->setId($row->id);
			$restaurant->setName($row->name);
			$restaurant->setLatitude($row->latitude);
			$restaurant->setLongitude($row->longitude);
			$restaurant->setWebsite($row->website);
			$restaurant->setCity($row->city);
			$restaurant->setFax($row->fax);
			$restaurant->setHas_breakfast($row->hasbreakfast);
			$restaurant->setHas_dinner($row->hasdinner);
			$restaurant->setHas_lunch($row->haslunch);
			$restaurant->setHittaURL($row->hittaURL);
			$restaurant->setMail_city($row->mailcity);
			$restaurant->setPhone($row->phone);
			$restaurant->setStreet($row->street);
			$restaurant->setType($row->type_name);
			$restaurant->setZip($row->zipcode);
			$rest_list[$i];
			$i++;
		}
		return $rest_list;
	}


	public function isNewrestaurant(Restaurant $res){
		$query = "SELECT id FROM restaurant WHERE latitude ='{$res->getLat()}' AND longitude = '{$res->getLong()}' LIMIT 1";
		if (!$result = $this->mysqli->query($query))
			die($this->mysqli->error);
		else {
			if ($this->mysqli->affected_rows == 0) {
				return true;
			}

		}
		return false;

	}

	/**
	 * when running the import restaurants script, this checks if a restaurant has already been importted before or not
	 *
	 * @param Restaurant $res
	 * @return boolean
	 */
	public function hasImported(Restaurant $res){
		$query = "SELECT id FROM restaurant WHERE name = '{$res->getName()}' OR hittaId ='{$res->getHittaId()}' LIMIT 1";
		if (!$result = $this->mysqli->query($query))
			die($this->mysqli->error);
		else {
			if ($this->mysqli->affected_rows == 1) {
				return true;
			}

		}
		return false;

	}

	/**
	 * update the coordinates
	 *
	 * @param int $id
	 * @param float $lat
	 * @param float $longi
	 * @return boolean
	 */
	public function updateCoordinates($id, $lat, $longi){
		$query ="UPDATE restaurant SET latitude = '$lat', longitude = '$longi' WHERE id = $id LIMIT 1";

		if (!$this->mysqli->query($query))
			die($this->mysqli->error);

		return true;
	}

	public function getCoordinates(Restaurant $restaurant) {
		##form address
		$street = $restaurant->getStreet();
		$mail_city = $restaurant->getMail_city();
		$country = 'Sweden';
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

		$this->updateCoordinates($restaurant->getId(), $restaurant->getLatitude(), $restaurant->getLongitude());

	}
}


?>