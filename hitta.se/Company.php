<?php

include_once('Address.php');


class Company
{
	private $name;
	private $hittaURI;
	private $website;
	private $address;
	private $telephone;
	private $fax;
	private $hittaId;

	public function Company()
	{


	}
	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getHittaURI()
	{
		return $this->hittaURI;
	}

	public function setHittaURI($hittaURI)
	{
		$this->hittaURI = $hittaURI;
	}

	public function getWebsite()
	{
		return $this->website;
	}

	public function setWebsite($website)
	{
		$this->website = $website;
	}

	public function getAddress()
	{
		return $this->address;
	}

	public function setAddress($address)
	{
		$this->address = $address;
	}

	public function getTelephone()
	{
		return $this->telephone;
	}

	public function setTelephone($telephone)
	{
		$this->telephone = $telephone;
	}

	public function getFax()
	{
		return $this->fax;
	}

	public function setFax($fax)
	{
		$this->fax = $fax;
	}

	public function getHittaId()
	{
		return $this->hittaId;
	}

	public function setHittaId($hittaId)
	{
		$this->hittaId = $hittaId;
	}

}

?>