<?php
use phpish\shopify;
$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $access_token );
try
{	

	$webhooks = $shopify('GET /admin/webhooks.json');
	$webhook_code = array(
	'webhook' =>
		array(
			"topic" => "app/uninstalled",
			"address" => APP_SERVER_URL."uninstall.php",
			"format" => "json"
		)
	);
	$jsonfields = json_encode($webhook_code);
	if(!empty($webhooks)){
		foreach($webhooks as $webhook) {
			if($webhook['topic'] == 'app/uninstalled') {
				//echo 'Already Created!';
			} else {
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://$shop/admin/webhooks.json",
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
				}
			}
		}
	} else {
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://$shop/admin/webhooks.json",
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
		}
	}
}
catch (shopify\ApiException $e)
{
	/* echo $e;
	print_r($e->getRequest());
	print_r($e->getResponse()); */
}
catch (shopify\CurlException $e)
{
	echo $e;
	print_r($e->getRequest());
	print_r($e->getResponse());
}
?>