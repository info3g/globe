<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: FALSE');
use phpish\shopify;
$access_token = $_REQUEST['access_token'];
$product_ids = explode(',',$_REQUEST['product_ids']);
$shopify = shopify\client($_REQUEST['shop'], SHOPIFY_APP_API_KEY, $access_token );
try
{	
	$Array = array(); $collection_Arr = array(); $getPixelTag = array();
	$shop_response = $shopify('GET /admin/shop.json');
	foreach($product_ids as $product_id) {
		$smart_collections = $shopify("GET /admin/smart_collections.json?product_id=$product_id");
		$custom_collections = $shopify("GET /admin/custom_collections.json?product_id=$product_id");
		foreach($smart_collections as $collection) {
			if($collection['handle'] != 'all') {
				$collection_Arr[] = $collection['handle'];
			}
		}
		foreach($custom_collections as $collection) {
			if($collection['handle'] != 'all') {
				$collection_Arr[] = $collection['handle'];
			}
		}
		$collection_Arr[] = 'all';
	}
	$collection_Arr = array_unique($collection_Arr);
	
	$PixelTag = $shopify("GET /admin/metafields.json?namespace=FacebookPixelTag");
	if($PixelTag) {
		foreach($PixelTag as $key => $options) {
			if($options['namespace'] == "FacebookPixelTag") {
				$option_value = explode('&with&',$options['value']);
				foreach($product_ids as $product_id) {
					$pro_data = $shopify("GET /admin/products/$product_id.json");
					$tagArr = explode(',',$pro_data['tags']);
					if (in_array($option_value[1], $tagArr)) {
						$getPixelTag[$option_value[0]] = $option_value[1];
					}
				}
			}
		}
		$getPixelTag = array_unique($getPixelTag);
	}
	
	$meta_response = $shopify("GET /admin/metafields.json?namespace=FacebookPixelCode");
	if($meta_response) {
		foreach($meta_response as $options) {
			if($options['namespace'] == "FacebookPixelCode") {		
				$option_value = explode('&with&',$options['value']);
				if($option_value[1] == null) {
				    $option_value[1] = 'all';
				}
				$getArray[$option_value[0]] = $option_value[1];
			}
		}
		// JSON Data
		if(isset($getArray)) {	
			$Array['pixelCode'] = $getArray;
			$Array['Collections'] = $collection_Arr;
			$Array['pixelTag'] = $getPixelTag;
			$Array['Currency'] = $shop_response['currency'];
			echo json_encode($Array);
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