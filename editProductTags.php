<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/connect.php'; //Database connectivity
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST["access_token"];
$shop = $_REQUEST["shop"];
$allPixels = $_REQUEST['allPixels'];
$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $access_token );
try
{
	foreach($allPixels as $key => $pixel) {
			$tag_count = explode('pixelTagCode', $key);
			$tag_count = $tag_count[1];
			$jsonfields = json_encode(
				array(
					"metafield" => array(
						"namespace" => "FacebookPixelTag",
						"key" => $key,
						"value_type" => "string",
						"value" => $pixel.'&with&'.$_REQUEST["selTag$tag_count"]
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
				echo 'Edit Pixel Tag!';
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