<?php
use phpish\shopify;
$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $access_token );
$shop_apps_Url = "https://$shop/admin/apps";
try
{
	$jsonfields = json_encode(
		array(
			"recurring_application_charge" => array(
				"name" => SHOPIFY_APP_NAME,
				"price" => SHOPIFY_APP_PRICE,
				"return_url" => SHOPIFY_PURCHASE_RETURN_URL_WITH_TRIAL,
				"test" => SHOPIFY_PURCHASE_MODE,
				"trial_days" => SHOPIFY_TRIAL_DAYS
			)
		)
	);	
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => "https://$shop/admin/recurring_application_charges.json",
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
		echo $err;
		echo "<script> window.location.href='$shop_apps_Url'</script>";
	} else {
		$result = get_object_vars(json_decode($response));
		$resultData = $result['recurring_application_charge'];
		setcookie('access_token', $access_token, time() + 86300, "/");
		setcookie('shop_url', $shop, time() + 86300, "/");
		echo '<script>window.top.location = "'.$resultData->confirmation_url.'";</script>';
		exit();
	}
}
catch (shopify\ApiException $e)
{
	# HTTP status code was >= 400 or response contained the key 'errors'
	echo $e;
	print_r($e->getRequest());
	print_r($e->getResponse());
}