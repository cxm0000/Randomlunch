<?php

session_start();



include_once ("../settings.php");
include_once 'Address.php';
	include_once (SERVER_ROOT. "/class/DbConnection.php");
	include_once (SERVER_ROOT. "/class/Restaurant.php");
	include_once (SERVER_ROOT. "/class/RestaurantController.php");
	include_once (SERVER_ROOT. "/class/Location.php");


function getPage($url)
{
	$s = curl_init();

	// set search options
	curl_setopt($s, CURLOPT_URL, $url);
	curl_setopt($s, CURLOPT_RETURNTRANSFER, false);
	curl_setopt($s, CURLOPT_ENCODING, 'utf-8');
	curl_setopt($s, CURLOPT_HTTPHEADER, array (
        "Content-Type: text/json; charset=utf-8",
		"Accept: application/json",
		"Accept-Encoding: utf-8"
    ));

	curl_setopt($s,CURLOPT_USERAGENT, 'Mozilla/5.0 (X11& U& Linux i686& it-IT& rv:1.9.0.2) Gecko/2008092313 Ubuntu/9.25 (jaunty) Firefox/3.8'); // fake Firefox browser

	$ret = curl_exec($s);

	curl_close($s);
	return $ret;
}

function sendRequest($url) {
	$http = new HttpRequest($url);

	$http->setHeaders(
		array(
			'Expect' => 'application/json',
			"Accept-Encoding"=> "utf-8",
			'User-Agent' => 'Mozilla/5.0 (X11& U& Linux i686& it-IT& rv:1.9.0.2) Gecko/2008092313 Ubuntu/9.25 (jaunty) Firefox/3.8',
		)
	);

	$result = $http->send();
    return $result;
}



$imported = array();
$errors = array();
$defaultTotalNo = 1500;	//approximate number of restaurants in Göteborg

unset($_SESSION['hitta_restaurants']);
if(!isset($_SESSION['hitta_restaurants'])) {
//	$url = 'http://www.hitta.se/json/mapsearch/kartpriofirst/restaurang/g%C3%B6teborg';
	$url = 'http://www.hitta.se/json/mapsearch/company/all/restaurang/g%C3%B6teborg/1/' . $defaultTotalNo;
	$result = sendRequest($url);
echo $result;die;
	if(!empty($result)) {
		$jsonObj = json_decode($result, true);
		$totalCompanyNo = $jsonObj['companies']['total'];

		if($totalCompanyNo > $defaultTotalNo) {
			//resent the request using the accurate number of restaurants
			$url = 'http://www.hitta.se/json/mapsearch/company/all/restaurang/g%C3%B6teborg/1/' . $totalCompanyNo;
			$result = getPage($url);

			if(!empty($result)) {
				$jsonObj = json_decode($result, true);
			}
		}

		$_SESSION['hitta_restaurants'] = $jsonObj;
	}
}else {
	$jsonObj = $_SESSION['hitta_restaurants'];

	$totalCompanyNo = $jsonObj['companies']['total'];
	$counter = 0;

	$restController = new RestaurantController();


	foreach($jsonObj['companies']['company'] as $company) {
		try {
			$rest = new Restaurant();
			$rest->setName($company['displayName']);
			$rest->setHittaId($company['id']);
			$rest->setStreet($company['address'][0]['street'] . ' ' . $company['address'][0]['number']);
			$rest->setCity($company['address'][0]['city']);
			$rest->setMail_city($company['address'][0]['city']);
			$rest->setZip($company['address'][0]['zipcode']);

			$rest->setPhone($company['phone'][0]['displayAs']);
			$rest->setHittaURL(
				'http://hitta.se/' .
				urlencode($company['displayName']) . '/' .
				urlencode($company['address'][0]['city']) . '/' .
				$company['id']
			);

			//try import this restaurant if its not been imported before
			if($restController->hasImported($rest) === false) {
				$restController->saveNewRestaurant($rest);
				$restController->getCoordinates($rest);
				$imported[] = $rest;
				$counter++;
			}

		} catch(Exception $e) {
			//do something?
			$errors[] = 'Error: restId: ' . $rest->getId() . '. Msg: ' . $e->getMessage();
		}
var_Dump($rest);
		print_r($errors);
		//compose a mail to the admin
		$mailTo = ADMIN_EMAIL;
		$mailTitle = 'Import restaurant script';
		$mailBody = 'Import Result:
			Totle No from Hitta: ' . $totalCompanyNo . PHP_EOL .
			'Imported: '. $counter . PHP_EOL;


		if(!empty($imported)) {
			foreach($imported as $importedRest) {
				$mailBody .= '  Id: ' . $importedRest->getId();
				$mailBody .= '  Name: ' . $importedRest->getName();
				$mailBody .= '  Address: ' . $importedRest->getAddress();
				$mailBody .= '  City: ' . $importedRest->getCity() . PHP_EOL;
			}
			print_r($mailBody);
		}

		if(!empty($errors)) {
			foreach($errors as $errorMsg) {
				$mailBody .= $errorMsg . PHP_EOL;
			}
		}

		mail($mailTo, $mailTitle, $mailBody);
		die('first loop finished');
	}

}

//compose a mail to the admin
$mailTo = ADMIN_EMAIL;
$mailTitle = 'Import restaurant script';
$mailBody = 'Import Result:
	Totle No from Hitta: ' . $totalCompanyNo . PHP_EOL .
	'Imported: '. $counter . PHP_EOL;

if(!empty($imported)) {
	foreach($imported as $importedRest) {
		$mailBody .= '  Id: ' . $importedRest->getId();
		$mailBody .= '  Name: ' . $importedRest->getName();
		$mailBody .= '  Address: ' . $importedRest->getAddress();
		$mailBody .= '  City: ' . $importedRest->getCity() . PHP_EOL;
	}
}

if(!empty($errors)) {
	foreach($errors as $errorMsg) {
		$mailBody .= $errorMsg . PHP_EOL;
	}


}
print_r($mailBody);
mail($mailTo, $mailTitle, $mailBody);
print("End!");
?>
