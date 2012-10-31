<?php

class Restaurant {
	
	protected $id= 0;
	protected $name = '';
	protected $city = '';
	protected $street = '';
	protected $zip = '';
	protected $mail_city = '';
	protected $phone = '';
	protected $fax = '';
	protected $website = '';
	protected $longitude = 0;
	protected $latitude = 0;
	protected $has_breakfast = 0;
	protected $has_lunch = 0;
	protected $has_dinner = 0;
	protected $type = 0;
	protected $hittaURL = '';
	protected $approved = false;
	protected $distance = 0;
	
	
	function __construct(){
		
	}
	
/**
	 * @return unknown
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * @return unknown
	 */
	public function getApproved() {
		return $this->approved;
	}
	
	/**
	 * @return unknown
	 */
	public function getCity() {
		return $this->city;
	}
	
	/**
	 * @return unknown
	 */
	public function getFax() {
		return $this->fax;
	}
	
	/**
	 * @return unknown
	 */
	public function getHas_breakfast() {
		return $this->has_breakfast;
	}
	
	/**
	 * @return unknown
	 */
	public function getHas_dinner() {
		return $this->has_dinner;
	}
	
	/**
	 * @return unknown
	 */
	public function getHas_lunch() {
		return $this->has_lunch;
	}
	
	/**
	 * @return unknown
	 */
	public function getHittaURL() {
		return $this->hittaURL;
	}
	
	/**
	 * @return unknown
	 */
	public function getLatitude() {
		return $this->latitude;
	}
	
	/**
	 * @return unknown
	 */
	public function getLongitude() {
		return $this->longitude;
	}
	
	/**
	 * @return unknown
	 */
	public function getMail_city() {
		return $this->mail_city;
	}
	
	/**
	 * @return unknown
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * @return unknown
	 */
	public function getPhone() {
		return $this->phone;
	}
	
	/**
	 * @return unknown
	 */
	public function getStreet() {
		return $this->street;
	}
	
	/**
	 * @return unknown
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * @return unknown
	 */
	public function getWebsite() {
		return $this->website;
	}
	
	/**
	 * @return unknown
	 */
	public function getZip() {
		return $this->zip;
	}
	
	public function getDistance(){
		return $this->distance;
	}
	
	public function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * @param unknown_type $approved
	 */
	public function setApproved($approved) {
		$this->approved = $approved;
	}
	
	/**
	 * @param unknown_type $city
	 */
	public function setCity($city) {
		$this->city = $city;
	}
	
	/**
	 * @param unknown_type $fax
	 */
	public function setFax($fax) {
		$this->fax = $fax;
	}
	
	/**
	 * @param unknown_type $has_breakfast
	 */
	public function setHas_breakfast($has_breakfast) {
		$this->has_breakfast = $has_breakfast;
	}
	
	/**
	 * @param unknown_type $has_dinner
	 */
	public function setHas_dinner($has_dinner) {
		$this->has_dinner = $has_dinner;
	}
	
	/**
	 * @param unknown_type $has_lunch
	 */
	public function setHas_lunch($has_lunch) {
		$this->has_lunch = $has_lunch;
	}
	
	/**
	 * @param unknown_type $hittaURL
	 */
	public function setHittaURL($hittaURL) {
		$this->hittaURL = $hittaURL;
	}
	
	/**
	 * @param unknown_type $latitude
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;
	}
	
	/**
	 * @param unknown_type $longitude
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;
	}
	
	/**
	 * @param unknown_type $mail_city
	 */
	public function setMail_city($mail_city) {
		$this->mail_city = $mail_city;
	}
	
	/**
	 * @param unknown_type $name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * @param unknown_type $phone
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}
	
	/**
	 * @param unknown_type $street
	 */
	public function setStreet($street) {
		$this->street = $street;
	}
	
	/**
	 * @param unknown_type $type
	 */
	public function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * @param unknown_type $website
	 */
	public function setWebsite($website) {
		$this->website = $website;
	}
	
	/**
	 * @param unknown_type $zip
	 */
	public function setZip($zip) {
		$this->zip = $zip;
	}

	public function setDistance($distance){
		$this->distance = $distance;
	}

}


?>