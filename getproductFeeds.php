<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST['access_token'];
$shop = $_REQUEST['shop'];
$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $access_token );
try
{
	$response = $shopify("GET /admin/metafields.json?namespace=MultiFB_pro_Feeds");
	if($response) {
		foreach($response as $options) {
			if($options['namespace'] == "MultiFB_pro_Feeds" && $options['key'] == "MultiFB_feed_Setting"){
				$Arr['collection'] = $options['value'];
				$Arr['rssURL'] = "https://$shop/apps/multipixel/v1/feeds";
				echo json_encode($Arr);
			}
		}
	} else {
		echo 'test';
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