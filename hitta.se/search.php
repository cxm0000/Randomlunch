<?php


include_once('Company.php');
//include_once('Address.php');


function searchCompanies($what, $where)
{
	set_time_limit(600);
	
	$s = curl_init();
	
	
	$i = 1;
	
	// the result will eventually end up in this array
	$companyArray = array();
	
	// get list of URLs for company detail pages
	$detailPageURLArray = array();
	$searchPage = '">Nästa</a>'; // fake, so that it will be true first time :)
	while(true)
	{
		$searchPage = getPage('http://www.hitta.se/SearchPink.aspx?vad=' . $what . '&var=' . $where . '&Rows=20&PageNo=' . $i);
		preg_match_all('/\/ViewDetailsPink.aspx\?(.*?)\"/', $searchPage, $matches);
		
		foreach($matches[0] as $entry)
		{
			$urlString = 'http://www.hitta.se'. substr($entry, 0, -1); // concatenate, and remove last " character
			$urlString = strtr($urlString, ';', '&'); // replace ; with &
			array_push($detailPageURLArray, $urlString);
		}
		
		$i++;
		
		// if(preg_match('/Nästa/', $searchPage))
		if(substr_count(utf8_encode($searchPage), utf8_encode('sta</a>')) == 0)
		{
			//echo "#: ".substr_count(utf8_encode($searchPage), utf8_encode('sta</a>'));
			//echo $searchPage;
			break;
		}
		
		if($i>1) break;
	}
	
	foreach($detailPageURLArray as $detailPageURL)
	{
		array_push($companyArray, new Company($detailPageURL));
	}
	
	return $companyArray;
	
	
	
	
	//$searchPage = file_get_contents('http://www.hitta.se/SearchMixed.aspx?vad='.$what.'&var='.$where)&
	//echo 'http://www.hitta.se/SearchMixed.aspx?vad='.$what.'&var='.$where&
	//return $searchPage&
}


function getPage($url)
{
	$s = curl_init();
	
	// set search options
	curl_setopt($s,CURLOPT_URL, $url);
	curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($s,CURLOPT_USERAGENT, 'Mozilla/5.0 (X11& U& Linux i686& it-IT& rv:1.9.0.2) Gecko/2008092313 Ubuntu/9.25 (jaunty) Firefox/3.8'); // fake Firefox browser
	
	return curl_exec($s);
}

?>