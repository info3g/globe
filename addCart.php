<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST['access_token'];
$shop = $_REQUEST['shop'];
$data_id = $_REQUEST['data_id'];
$shopify = shopify\client($_REQUEST['shop'], SHOPIFY_APP_API_KEY, $access_token );
try
{
// Advanced Settings
$settingOption = array(); $microdata = 1; $delay_show = 0;
$MultiFB_Adv_setting = $shopify("GET /admin/metafields.json?namespace=MultiFB_Adv_setting");
if($MultiFB_Adv_setting) {
foreach($MultiFB_Adv_setting as $options) {
if($options['namespace'] == "MultiFB_Adv_setting" && $options['key'] == "MultiFB_Setting"){
$settingOption = explode('&&', $options['value']);
}
}
}
if(count($settingOption) == 4) {
$delay_show = $settingOption[1];
$microdata = $settingOption[2];
}
// Advanced Settings

$themes = $shopify("GET /admin/themes.json");
foreach($themes as $theme) {
if($theme['role'] == 'main') {
$themeId = $theme['id'];
$jsData = "var dg$;
var script = document.createElement('script');
script.setAttribute('src', '//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');
script.addEventListener('load', function() {
dg$ = $.noConflict(true);
mainScript(dg$);
});
document.head.appendChild(script);

function mainScript($) {
var showPixel = showImgPixel = '';
var pageURL = window.location.href;
var currency = $('.shopCurrency').text();
var microdata = $microdata;
var delay_show = $delay_show;
var shop = '$shop';
var server = '".APP_SERVER_URL."';

// cart page
if(pageURL.indexOf('cart') > -1) {
console.log('ddddd');
}
// product pages
else if(pageURL.indexOf('/products/') > -1) {
}
// start collection page
else if(pageURL.indexOf('/collections') > -1) {
}

else {
}

}";

if(is_dir('pull')) {
if (!is_dir('pull/'.$shop)) {
mkdir('pull/'.$shop);
$filepath = 'pull/'.$shop;
} else {
$filepath = 'pull/'.$shop;
}
$filename = $filepath.'/hook.js';
$jsfile = fopen($filename, "w");
fwrite($jsfile, $jsData);
fclose($jsfile);
}
$permanent_domain = APP_SERVER_URL."pull/{{shop.permanent_domain}}/hook.js";
// Add Snippet file
$snippet_code = '<span class="id_data">'.$data_id.'</span><p class="shipping-savings-sage">
  {% assign shipping_value = 60000 %}
  {% assign cart_total = cart.total_price %}
  {% assign shipping_value_left = shipping_value | minus: cart_total %}
  <progress id="versandfortschritt" class="full-progress-bar"  value="{{ cart_total }}" max="60000"> {{ shipping_value }} </progress>
 <span class="shippingleft">
    You are <span class="leftprice">{{ shipping_value_left | money }} </span> away from free shipping!
  </span>
<span class="freeshipping">You have got free shipping!</span>{% if shipping_value_left > 0 %}{% else %}{% endif %}</p>';
$snippet_fields = array( "asset" => array('key' => 'snippets/cartAdons.liquid', 'value' => $snippet_code));

$json_snippet = json_encode($snippet_fields);

$curl = curl_init(); 
curl_setopt_array($curl, array(
CURLOPT_URL => "https://$shop/admin/themes/$themeId/assets.json",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_CUSTOMREQUEST => "PUT",
CURLOPT_POSTFIELDS => $json_snippet,
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
//echo "cURL Error #:" . $err;
echo 'Try again Later!';
} else {
echo $response;
}
// GET and Edit Theme.liquid
$themeFile = $shopify("GET /admin/themes/$themeId/assets.json?asset[key]=layout/theme.liquid&theme_id=$themeId");

if (strpos($themeFile['value'], "{%include 'cartAdons' %}") === false || strpos($themeFile['value'], "<!-- hnk portfolio proof -->") === false) {
$newHtml = $themeFile['value'];
if (strpos($themeFile['value'], "{%include 'cartAdons' %}") === false) {
$replacedCode = "{%include 'cartAdons' %}</head>";
$newHtml = str_replace("</head>",$replacedCode,$newHtml);
}
$themeFields = array( "asset" => array('key' => 'layout/theme.liquid', 'value' => $newHtml));
$jsonTheme = json_encode($themeFields);

$curl = curl_init(); 
curl_setopt_array($curl, array(
CURLOPT_URL => "https://$shop/admin/themes/$themeId/assets.json",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_CUSTOMREQUEST => "PUT",
CURLOPT_POSTFIELDS => $jsonTheme,
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
//echo "cURL Error #:" . $err;
echo 'Try again Later!';
} else {
//echo $response;
}
}
// end Code
}
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