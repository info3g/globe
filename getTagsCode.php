<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST['access_token'];
$shopify = shopify\client($_REQUEST['shop'], SHOPIFY_APP_API_KEY, $access_token );
try
{
	$response = $shopify("GET /admin/metafields.json?namespace=FacebookPixelTag");
	$totalPixel = $shopify("GET /admin/metafields/count.json?namespace=FacebookPixelTag");

	if($response) {
		foreach($response as $key => $options) {
			if($totalPixel > 20) {
				if($options['namespace'] == "FacebookPixelTag"){
					$metafieldID = $options['id'];
					$deletePixel = $shopify("DELETE /admin/metafields/$metafieldID.json");
				}
			} else {
				if($options['namespace'] == "FacebookPixelTag") {
					//pixelTagCode
					$j = explode('pixelTagCode', $options['key']);
					$j = $j[1];
					$getArray[$j] = $options['value'];
				}
			}
		}
		if(isset($getArray)) {
			echo json_encode($getArray);
		}
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
