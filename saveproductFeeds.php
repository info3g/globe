<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$selfeedCol = $_REQUEST['selfeedCol'];
$selfeedCol_id = $_REQUEST['selfeedCol_id'];
$access_token = $_REQUEST['access_token'];
$shopify = shopify\client($_REQUEST['shop'], SHOPIFY_APP_API_KEY, $access_token );
$array = array("collection_id"=>$selfeedCol_id);
// echo json_encode(['response'=>'get url']);
// $custom_col_count = $shopify('GET admin/api/2020-07/collections/'.$array['collection_id'].'/products.json');
// echo json_encode(['response'=>$custom_col_count]);
$get_url = 'https://skillsharepractice.myshopify.com/admin/api/2020-07/collections/'.$array['collection_id'].'/products.json';
// echo json_encode(['response'=>curlConnection($get_url,$access_token)]);
$arr_data = json_decode(curlConnection($get_url,$access_token),true);

$html = '<ul>';
foreach($arr_data['products'] as $data){
$html.='<li class="p-index products">';
$html.='<div class="p-data">';
$html .= '<div class="p-title">';
$html.='<div class="pid">';
$html.= '<input type="checkbox" name="product" value="' . $data["id"] . '">';
$html.='</div>';	
$html .= '<span>'.$data["title"].'</span>';
$html .= '<span>'.$data["vendor"].'</span>';
$html .= '</div>';

$html.='</div>';
$html .= '<div class="pimage">'."<img src=".$data["images"][0]['src'].">".'</div>';
$html.='</li>';
}
$html .= '</ul>';
$html.=' <button type="submit">submit</button>';
echo $html;
function curlConnection($url,$token){
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			"content-type: application/json",
			"postman-token: 87f67d23-da3a-06c8-b488-f74e1934a869",
			"x-shopify-access-token: " . $token
		),
	));

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) {
		return "cURL Error #:" . $err;
	} else {
		return $response;
	}
}
// try
// {     
	// $custom_col_count = $shopify('GET admin/api/2020-07/collections/'+ $array['collection_id'] +'/products.json');
	// https://skillsharepractice.myshopify.com/admin/api/2020-07/collections/190079959177/products.json
	 // collections product
	// $productcount = $shopify("admin/collections/", $array, 'GET');
	 // print_r($custom_col_count);
	
	
// }
// catch (shopify\ApiException $e)
// {

	// echo $e;
	// print_r($e->getRequest());
	// print_r($e->getResponse());
// }




?>