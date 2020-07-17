<?php
use phpish\shopify;
$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $access_token );
$shop_apps_Url = "https://$shop/admin/apps";
if(isset($timestamp)) {
	$installDate = date($timestamp);
	$today = date('Y-m-d H:i:s');
	
	$datetime1 = date_create($installDate);
	$datetime2 = date_create($today);
	$interval = date_diff($datetime1, $datetime2);
	$trial_days = $interval->format('%a');
	if($trial_days < SHOPIFY_TRIAL_DAYS) {
		$leftDays = SHOPIFY_TRIAL_DAYS - $trial_days;
	} else {
		$leftDays = 0;
	}
	
	try
	{
		$jsonfields = json_encode(
			array(
				"recurring_application_charge" => array(
					"name" => SHOPIFY_APP_NAME,
					"price" => SHOPIFY_APP_PRICE,
					"return_url" => SHOPIFY_PURCHASE_RETURN_URL,
					"test" => SHOPIFY_PURCHASE_MODE,
					"trial_days" => $leftDays
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
			//redirect to dashboard
			echo "<script> window.location.href='$shop_apps_Url'</script>";
		} else {
			$result = get_object_vars(json_decode($response));
			$resultData = $result['recurring_application_charge'];
			setcookie('fb_shop_url', $shop, time() + 86300, "/");
			echo '<script>window.location.location = "'.$resultData->confirmation_url.'";</script>';
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
} else {
	//redirect to dashboard
	echo "<script> window.location.href='$shop_apps_Url'</script>";
}