<?php

include_once('Address.php');


class Company
{
	var $name;
	var $hittaURI;
	var $website;
	var $address;
	var $telephone;
	var $fax;
	
	
	public function Company($detailsPageURI)
	{
		// handy for debugging
		$this->hittaURI = $detailsPageURI;
		
		
		
		// get the page
		$s = curl_init();
		curl_setopt($s,CURLOPT_URL, $detailsPageURI);
		curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($s,CURLOPT_USERAGENT, 'Mozilla/5.0 (X11& U& Linux i686& it-IT& rv:1.9.0.2) Gecko/2008092313 Ubuntu/9.25 (jaunty) Firefox/3.8'); // fake Firefox browser
		$html = curl_exec($s);
		
		
		
		// name
		preg_match('/\<h1\>(.*?) \<span/', $html, $matches);
		if(!empty($matches)) $this->name = $matches[1];
		
		
		
		// telephone
		preg_match('/\<strong\>Telefon\:\<\/strong\>\&nbsp\;\<a style\=\'text-decoration\:none\;\' href\=\"callto\:(.*?)\"/', $html, $matches);
		if(!empty($matches)) $this->telephone = $matches[1];
		
		
		
		// address
		preg_match('/\<strong\>Adress\<\/strong\>\<br \/\>(.*?)\<br\>(([0-9]{3}) ([0-9]{2})) (.*?)\<\/span\>/', $html, $matches);
		$address = new Address();
		if(empty($matches))
		{
			preg_match('/\<strong\>Besöksadress\<\/strong\>\<br \/\>(.*?)\<br\>(([0-9]{3}) ([0-9]{2})) (.*?)\<\/span\>/', $html, $matches);
		}
		
		if(!empty($matches))
		{
			// address with zip code
			$address->setStreet($matches[1]);
			$address->setZipCode($matches[2]);
			$address->setMailCity($matches[5]);
			$this->address = $address;
		}
		
		// address without zip code
		if(empty($matches))
		{
			preg_match('/\<strong\>Adress\<\/strong\>\<br \/\>(.*?)\<br\> (.*?)\<\/span\>/', $html, $matches);
			if(empty($matches))
			{
				preg_match('/ksadress\<\/strong\>\<br \/\>(.*?)\<br\> (.*?)\<\/span\>/', $html, $matches);
				//var_dump($matches);
				//var_dump($html);
			}
			
			if(!empty($matches))
			{
				// address without zip code
				$address->setStreet($matches[1]);
				$address->setMailCity($matches[2]);
				$this->address = $address;
			}
		}
		
		
		
		
		// website
		preg_match('/id\=\"companyurl0\"\>(.*?)\<\/a\>/', $html, $matches);
		if(!empty($matches)) $this->website = $matches[1];
	}
	
	
	public function getName()
	{
		return $this->name;
	}
	
	
	public function getAddress()
	{
		return $this->address;
	}
	
	// for convenience...
	public function getStreet() { return $address->getStreet(); }
	public function getZipCode() { return $address->getZipCode(); }
	public function getMailCity() { return $address->getMailCity(); }
	
	
	public function getTelephone()
	{
		return $this->telephone;
	}
}

?>