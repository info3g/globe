<?php
use phpish\shopify;
$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $access_token );
try
{
	$shop_response = $shopify('GET /admin/shop.json');
	$filename = 'StoreOwnerDetails.csv';
	if(file_exists($filename)){
		$search = $shop_response['domain'];
		$readfile = file($filename);
		$line_number = false;
		while (list($key, $line) = each($readfile) and !$line_number) {
			$line_number = (stripos($line, $search) !== FALSE);
		}
		if($line_number){
			//echo "Results found for " .$search;
		} else{
			$file = fopen($filename, 'a');
			$row = array($shop_response['shop_owner'],$shop_response['email'],$shop_response['domain']);
			fputcsv($file, $row);
			fclose($file);
		}
	} else {
		$file = fopen($filename, 'w');
		fputcsv($file, array('Name','Email','Store URL'));
		$row = array($shop_response['shop_owner'],$shop_response['email'],$shop_response['domain']);
		fputcsv($file, $row);
		fclose($file);
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