<?php

//
// From http://non-diligent.com/articles/yelp-apiv2-php-example/
//


// Enter the path that the oauth library is in relation to the php file
require_once ('lib/OAuth.php');

require_once ('../settings.php');
require_once ('../class/ImportedRestaurantBuilder.php');

// For example, request business with id 'the-waterboy-sacramento'
//$unsigned_url = "http://api.yelp.com/v2/business/the-waterboy-sacramento";


// For examaple, search for 'tacos' in 'sf'
//$unsigned_url = "http://api.yelp.com/v2/search?term=tacos&location=sf";




function yelpSearch($url)
{
	echo "<br>URL: $url<br>";
	// Set your keys here
	$consumer_key = "U2Cws_b_SlvPXPlOKrW0sw";
	$consumer_secret = "EPoQtTLfvyER5Bkt1KxdAZCUc5I";
	$token = "9C2clxP3IdPcmS4e_y1fLdFmkWaxEz2h";
	$token_secret = "L8Bt97dW52kY1O3zOx24rUnpHKM";

	// Token object built using the OAuth library
	$token = new OAuthToken($token, $token_secret);

	// Consumer object built using the OAuth library
	$consumer = new OAuthConsumer($consumer_key, $consumer_secret);

	// Yelp uses HMAC SHA1 encoding
	$signature_method = new OAuthSignatureMethod_HMAC_SHA1();

	// Build OAuth Request using the OAuth PHP library. Uses the consumer and token object created above.
	$oauthrequest = OAuthRequest::from_consumer_and_token($consumer, $token, 'GET', $url);

	// Sign the request
	$oauthrequest->sign_request($signature_method, $consumer, $token);

	// Get the signed URL
	$signed_url = $oauthrequest->to_url();

	// Send Yelp API Call
	$ch = curl_init($signed_url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$data = curl_exec($ch); // Yelp response
	curl_close($ch);

	return json_decode($data);

}

$url = "http://api.yelp.com/v2/search?cc=SE&location=stockholm&offset=1&category_filter=restaurants";
$response = yelpSearch($url);


if (!empty($response)) {
	ob_end_flush();

	if (!empty($response->error)) {
		die($response->error->text);
	}

	$totalObjects = $response->businesses;

	$loopNo = (is_int($totalObjects / 20)) ? ($totalObjects / 20) : (intval($totalObjects / 20) + 1);
	echo "<br>Total object: $totalObjects<br>";
	echo "<br>Total loop: $loopNo<br>";

    flush();

	$loopCounter = 1;
	for ($index = 0; $index < $totalObjects; $index += 20) {
		echo "<br>Starting loop: $loopCounter<br>";

		flush();
		$url = "http://api.yelp.com/v2/search?cc=SE&location=stockholm&offset={$index}&category_filter=restaurants";
		$response = yelpSearch($url);

		echo "<br>________________________________________________________________________________________________<br>";
		flush();
		if (!empty($response)) {
			$importer = new ImportedRestaurantBuilder($response);

			try {
				$importer->import();

			} catch (Exception $e) {
				echo $e->getTraceAsString();
			}


		}

		$loopCounter++;
		sleep(1);
	}

	ob_end_clean();

}

?>
