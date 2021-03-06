<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/connect.php'; //Database connectivity
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST["access_token"];
$pixelCode = $_REQUEST["pixelEditor"];$select_collection = $_REQUEST["select_collection"];
$count = $_REQUEST["pixelCount"];$pixel_with_Col = $pixelCode.'&with&'.$select_collection;
$shop = $_REQUEST["shop"];
$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $access_token );
try
{	
	$jsonfields = json_encode(
		array(
			"metafield" => array(
				"namespace" => "FacebookPixelCode",
				"key" => "pixelCode$count",
				"value_type" => "string",
				"value" => $pixel_with_Col
			)
		)
	);	
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://$shop/admin/metafields.json",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => $jsonfields,
		CURLOPT_HTTPHEADER => array(
		"cache-control: no-cache",
		"content-type: application/json",
		"x-shopify-access-token: $access_token"
		),
	));
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if ($err) {
		echo 'Try again Later!';
	} else {
		//echo $response;
		echo 'Save Pixel Code!';
	}
}
catch (shopify\ApiException $e)
{
	# HTTP status code was >= 400 or response contained the key 'errors'
	echo $e;
	print_r($e->getRequest());
	print_r($e->getResponse());
}
?>
