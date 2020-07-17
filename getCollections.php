<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST['access_token'];
$shopify = shopify\client($_REQUEST['shop'], SHOPIFY_APP_API_KEY, $access_token );
try
{     
	$html = '';
	$pagelimit = 250;
	
	// Custom collections
	$custom_col_count = $shopify('GET /admin/custom_collections/count.json');
	if($custom_col_count <= $pagelimit) {
		$custom_page = 1;
	} else {
		$custom_page = ceil($custom_col_count/$pagelimit);
	}
	for($i=1;$i<=$custom_page;$i++) {
		$custom_col = $shopify("GET /admin/custom_collections.json?limit=$pagelimit&page=$i");
		foreach($custom_col as $custom) {
			$html .= '<option value="'.$custom['handle'].'" data-id="'.$custom['id'].'" data-type="custom" >'.$custom['title'].'</option>';
		}
	}
	
	// Smart collections
	$smart_col_count = $shopify('GET /admin/smart_collections/count.json');
	if($smart_col_count <= $pagelimit) {
		$smart_page = 1;
	} else {
		$smart_page = ceil($smart_col_count/$pagelimit);
	}
	
	for($i=1;$i<=$smart_page;$i++) {
		$smart_col = $shopify("GET /admin/smart_collections.json?limit=$pagelimit&page=$i");
		foreach($smart_col as $smart) {
			$html .= '<option value="'.$smart['handle'].'" data-id="'.$smart['id'].'" data-type="smart" >'.$smart['title'].'</option>';
		}
	}
	echo $html;
}
catch (shopify\ApiException $e)
{

echo $e;
print_r($e->getRequest());
print_r($e->getResponse());
}
?>
