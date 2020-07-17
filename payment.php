<?php
// Required File Start.........
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/connect.php'; //Database
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
// Required File END...........
//get charge id
$charge_id = $_REQUEST['charge_id'];
if(isset($_COOKIE['fb_shop_url'])) {
	$shop = $_COOKIE['fb_shop_url'];
	$sql = "SELECT * FROM PixelInstaller WHERE shop_url='$shop'";
	$result = mysqli_query($con, $sql);
	if(mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_assoc($result);
		$timestamp = $row['timestamp'];
		$access_token = $row['access_token'];
	}
	$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $access_token );
	try
	{	
		$response = $shopify("GET /admin/recurring_application_charges/$charge_id.json");
		$status = $response['status'];
		//process shop only if they accepted the charge
		if( $status == 'accepted' ) {
			$jsonfields = json_encode(
				array(
					"application_charge" => array(
						"id" => $charge_id
					)
				)
			);	
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://$shop/admin/recurring_application_charges/$charge_id/activate.json",
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
				$shopUrl = "https://$shop/admin/apps";
				echo "<script> window.location.href='$shopUrl'</script>";
			} else {
				
				//activate in database
				$sql2 = "UPDATE PixelInstaller SET timestamp='".$timestamp."', charge_id= '".$charge_id."', active = 1 WHERE shop_url='$shop'";
				$result = mysqli_query($con, $sql2);
				if($result == 1) {
					if(isset($_COOKIE['timestamp'])) {
						setcookie('timestamp', '', time() - 86300);
					}
					//$shopUrl = "https://$shop/admin/apps/".SHOPIFY_APP_HANDLE;
					$shopUrl = APP_SERVER_URL."dashboard.php?shop=$shop";
					echo "<script> window.location.href='$shopUrl'</script>";
				}
			}
		} else {
			//remove the store
			setcookie('delineCharge', 'delineCharge', time() + 86300, "/");
			header("location:https://$shop/admin/apps/".SHOPIFY_APP_HANDLE);
		}
	}
	catch (shopify\ApiException $e)
	{
		# HTTP status code was >= 400 or response contained the key 'errors'
		echo $e;
		print_r($e->getRequest());
		print_r($e->getResponse());
	}
}