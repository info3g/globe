<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST['access_token'];
$shop = $_REQUEST['shop'];
$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $access_token );
try
{	
	$js_file = APP_SERVER_URL."addPixelCode.js?access_token=$access_token&server=".APP_SERVER_URL;
	$data = $shopify("GET /admin/script_tags.json");
	if(!$data) {
		/* $fields = array("script_tag" => array('event' => 'onload', 'src' => $js_file));
		$jsonfields = json_encode($fields);
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://$shop/admin/script_tags.json",
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
			echo 'Add JS file';
		} */
	} else {
		$shopify("DELETE /admin/script_tags/" . $data[0]['id'] . ".json");
		echo 'Already exist JS file';
	}
}
catch (shopify\ApiException $e)
{
	echo $e;
	print_r($e->getRequest());
	print_r($e->getResponse());
}
?>