<?php
header("Access-Control-Allow-Origin: *");
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
require __DIR__.'/connect.php'; //Database
use phpish\shopify;

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0'."\r\n";
$headers = 'Content-type: text/html;'."\r\n";
// Compose a simple HTML email message
$message = '<html><body>';
$message .= "<p>Please add review for Facebook Track app</p>";
$message .= '</body></html>';


$sql = "SELECT * FROM $tableName WHERE active = 1 ";
$result = mysqli_query($con, $sql);
if(mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$access_token = $row['access_token'];
		$shop = $row['shop_url'];
		$timestamp = date("Y-m-d", strtotime($row['timestamp']));
		$timestamp = strtotime($timestamp);
		$date = strtotime(date("Y-m-d", strtotime("-14 day")));
		//$date = strtotime(date("Y-m-d"));
		if($date == $timestamp) {
			$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $access_token );
			try
			{	
				$shop_response = $shopify("GET /admin/shop.json");
				if($shop_response['email']) {
					$sender = $shop_response['email'];
					//mail($sender,'Ask for Review',$message,$headers);
				}
			}
			catch (shopify\ApiException $e)
			{
				# HTTP status code was >= 400 or response contained the key 'errors'
				//echo $e;
				//print_r($e->getRequest());
				//print_r($e->getResponse());
			}
		}
		
	}
}
?>