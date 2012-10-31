<?php

class Address
{
	var $street;
	var $zipCode;
	var $mailCity;
	
	
	
	
	public function __construct()
	{
	
	}
	
	
	/* SETTERS */
	public function setStreet($street)
	{
		$this->street = $street;
	}
	
	
	public function setZipCode($zipCode)
	{
		$this->zipCode = $zipCode;
	}
	
	
	public function setMailCity($mailCity)
	{
		$this->mailCity = $mailCity;
	}
	
	
	
	/* GETTERS */
	public function getStreet()
	{
		return $this->street;
	}
	
	
	public function getZipCode()
	{
		return $this->zipCode;
	}
	
	
	public function getMailCity()
	{
		return $this->mailCity;
	}
}

?>