<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/connect.php'; //Database connectivity
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST["access_token"];
$shop = $_REQUEST["shop"];

$enable_microdata = 0;
$include_shipping = 0;
$purchase_conversion = $_REQUEST['purchase_conversion'];
$delay_show = $_REQUEST['delay_show'];
if(isset($_REQUEST['enable_microdata'])) {
	$enable_microdata = $_REQUEST['enable_microdata'];
}
if(isset($_REQUEST['include_shipping'])) {
	$include_shipping = $_REQUEST['include_shipping'];
}
$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $access_token );
try
{	
	$settings = $purchase_conversion.'&&'.$delay_show.'&&'.$enable_microdata.'&&'.$include_shipping;
	$jsonfields = json_encode(
		array(
			"metafield" => array(
				"namespace" => "MultiFB_Adv_setting",
				"key" => "MultiFB_Setting",
				"value_type" => "string",
				"value" => $settings
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
		echo 'Save Metafield Code!';
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