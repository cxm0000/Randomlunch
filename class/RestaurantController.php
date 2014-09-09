<?php
include_once 'DbConnection.php';

class RestaurantController {

	private $pdoConnection = null;

	function RestaurantController() {
		$this->pdoConnection = DbConnection::getInstance();
	}

	public function startTransaction()
	{
		$this->pdoConnection->beginTransaction();
	}

	public function commitTransaction()
	{
		$this->pdoConnection->commit();
	}

	public function rollBackTransaction()
	{
		$this->pdoConnection->rollBack();
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

		$result = $this->pdoConnection->query($query);
		while ($row = $result->fetchObject()) {
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
			$restaurant->setMail_city($row->mailcity);
			$restaurant->setPhone($row->phone);
			$restaurant->setStreet($row->street);
			$restaurant->setType($row->typename);
			$restaurant->setZip($row->zipcode);
			$restaurant->setImageUrl($row->imageUrl);

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
	public function saveNewRestaurant(Restaurant $res)
	{

		$city = strtolower($res->getCity());
		if ($city == 'göteborg' || $city == 'goteborg' || $city == 'gÃ–teborg') {
			$city = 'gothenburg';
		}

		$query = "SELECT id FROM city WHERE name = '$city' LIMIT 1";
		$result = $this->pdoConnection->query($query);

		$row = $result->fetchObject();

		if(!$row) {
			echo "No city: " . $res->getCity() . " found. Adding it as a new city ...<br/>";
			$cityId = $this->saveNewRestaurantCity(strtolower($res->getCity()));
			echo "New city id: {$cityId}...<br/>";
			$res->setCity($cityId);
		} else {
			$res->setCity($row->id);
		}

		$restaurant = $this->getOneRestaurant($res->getName(), $res->getLatitude(), $res->getLongitude());
		if (!$restaurant instanceof Restaurant) {
			$query = "INSERT INTO restaurant(
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
					imageUrl
				) VALUES (
					:name,
					:city,
					:zipcode,
					:street,
					:phone,
					:fax,
					:website,
					:latitude,
					:longitude,
					:hasbreakfast,
					:haslunch,
					:hasdinner,
					:approved,
					:imageUrl
				)";


			/** @var PDOStatement **/
			$sth = $this->pdoConnection->prepare($query);

			$sth->bindValue(":name", $res->getName(), PDO::PARAM_STR);
			$sth->bindValue(":city", $res->getCity(), PDO::PARAM_STR);
			$sth->bindValue(":zipcode", $res->getZip(), PDO::PARAM_STR);
			$sth->bindValue(":street", $res->getStreet(), PDO::PARAM_STR);
			$sth->bindValue(":phone", $res->getPhone(), PDO::PARAM_STR);
			$sth->bindValue(":fax", $res->getFax(), PDO::PARAM_STR);
			$sth->bindValue(":website", $res->getWebsite(), PDO::PARAM_STR);
			$sth->bindValue(":latitude", $res->getLatitude());
			$sth->bindValue(":longitude", $res->getLongitude());
			$sth->bindValue(":hasbreakfast", $res->getHas_breakfast(), PDO::PARAM_INT);
			$sth->bindValue(":haslunch", $res->getHas_lunch(), PDO::PARAM_INT);
			$sth->bindValue(":hasdinner", $res->getHas_dinner(), PDO::PARAM_INT);
			$sth->bindValue(":approved", 1, PDO::PARAM_INT);
			$sth->bindValue(":imageUrl", $res->getImageUrl(), PDO::PARAM_STR);


			$result = $sth->execute();


			if (!$result) {
				print_r($sth->errorInfo());
				print_r($res->getImageUrl());
				throw new Exception("Can not save the restaurant.<br/>");
			} else {
				$rest_id = $this->pdoConnection->lastInsertId();
				$res->setId($rest_id);
				echo "Checking type:" . $res->getType() . "<br/>";
				$query = "SELECT id FROM restauranttype WHERE name = '{$res->getType()}' LIMIT 1";
				$result = $this->pdoConnection->query($query);

				$row = $result->fetchObject();

				if(!$row) {
					echo "No type:" . $res->getType() . " found. Adding it as a new type ...<br/>";
					$typeId = $this->saveNewRestaurantType(strtolower($res->getType()));
					echo "New type id {$typeId}...<br/>";
	//					throw new Exception("We do not support this type yet, plz come back later. Type name:" . $res->getType());
				} else {
					$typeId = $row->id;
				}

				$query = sprintf("INSERT INTO restauranttype_join_restaurant (
				restauranttype_id,
				restaurant_id

				) VALUES (
				'%d',
				'%d'
				)",
				$typeId,
				$rest_id
				);

				if (!$this->pdoConnection->query($query)) {
					var_dump($query);
					throw new Exception($this->pdoConnection->errorInfo());
				} else {
					return true;
				}

			}
		}


	}

	/**
	 *
	 * @param string $name
	 * @return integer
	 */
	public function saveNewRestaurantType($name)
	{
		$query = "INSERT into restauranttype (name) VALUES (:name)";

		$sth = $this->pdoConnection->prepare($query);
		$sth->bindValue(":name", $name, PDO::PARAM_STR);
		$sth->execute();

		return $this->pdoConnection->lastInsertId();
	}

	/**
	 *
	 * @param string $name
	 * @return integer
	 */
	public function saveNewRestaurantCity($name)
	{
		$query = "INSERT into city (name) VALUES (:name)";

		$sth = $this->pdoConnection->prepare($query);
		$sth->bindValue(":name", $name, PDO::PARAM_STR);
		$sth->execute();

		return $this->pdoConnection->lastInsertId();
	}

	/*
	 * This method is used to get ONE restaurant by name
	 * now its used together with adding a restaurant - noot anymore
	 * could be nice if we in the future would have a search funtion
	 * before they add a new one they could search for it if it exists
	 */
	public function getOneRestaurant($name, $lat, $long){

			$query = "SELECT
						r.*,
						rt.name AS typename,
						c.name AS cityname
				FROM
						restaurant r
				INNER JOIN
						restauranttype_join_restaurant rr
				ON (
						r.id = rr.restaurant_id
					)
				INNER JOIN
						restauranttype rt
				ON (
						rr.restauranttype_id = rt.id
					)
				INNER JOIN
					city c
				ON (
					c.id = r.city
				)
				WHERE
					r.name = '{$name}'
				AND
					r.latitude = {$lat}
				AND
					r.longitude = {$long}
				LIMIT 1";

		$result = $this->pdoConnection->query($query);
		if ($result) {
			while ($row = $result->fetchObject()) {
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
				$restaurant->setMail_city($row->mailcity);
				$restaurant->setPhone($row->phone);
				$restaurant->setStreet($row->street);
				$restaurant->setType($row->typename);
				$restaurant->setZip($row->zipcode);
				$restaurant->setImageUrl($row->imageUrl);

			}
			unset($result);
			unset($row);

			return $restaurant;
		}

		return false;
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

		if (!$this->pdoConnection->query($query))
			die($this->pdoConnection->error);

	}

	/*
	 * This method is used to get a list of all new restaurants in db
	 *
	 */
	public function getNotApprovedRestaurants() {
		$query = "SELECT * FROM restaurant WHERE approved = 1";
		$rest_list;
		$result = $this->pdoConnection->query($query);
		$i = 0;
		while ($row = $result->fetchObject()) {
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
		if (!$result = $this->pdoConnection->query($query))
			die($this->pdoConnection->error);
		else {
			if ($this->pdoConnection->affected_rows == 0) {
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
		if (!$result = $this->pdoConnection->query($query))
			die($this->pdoConnection->error);
		else {
			if ($this->pdoConnection->affected_rows == 1) {
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

		if (!$this->pdoConnection->query($query))
			die($this->pdoConnection->error);

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