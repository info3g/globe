<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST['access_token'];
$shopify = shopify\client($_REQUEST['shop'], SHOPIFY_APP_API_KEY, $access_token );
try
{
	$response = $shopify("GET /admin/metafields.json?namespace=MultiFB_Adv_setting");
	if($response) {
		foreach($response as $options) {
			if($options['namespace'] == "MultiFB_Adv_setting" && $options['key'] == "MultiFB_Setting"){
				echo $options['value'];
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