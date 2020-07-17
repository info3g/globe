<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/connect.php'; //Database connectivity
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST["access_token"];
$shop = $_REQUEST["shop"];
$delPixelRow = $_REQUEST['delPixelRow'];
$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $access_token );
try
{
	$response = $shopify("GET /admin/metafields.json?namespace=FacebookPixelTag");
	if($response) {
		foreach($response as $options) {
			if($options['namespace'] == "FacebookPixelTag" && $options['key'] == $delPixelRow){
				$metafieldID = $options['id'];
				$deletePixel = $shopify("DELETE /admin/metafields/$metafieldID.json");				
				break;
			}
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