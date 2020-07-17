<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST['access_token'];
$shop = $_REQUEST['shop'];
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

// Add Asset JS file
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

}
// product pages
else if(pageURL.indexOf('/products/') > -1) {
}
// start collection page
else if(pageURL.indexOf('/collections') > -1) {
}
// end collection page
// start Feed page
else if(pageURL.indexOf('/apps/multipixel/v1/feeds') > -1 ){
$('html').html('');
$.ajax({
url: server+'getFeeds.php?shop='+shop,
type: 'GET',
crossDomain : true,
success: function(response) {
$('html').html('<pre style=\"word-wrap: break-word; white-space: pre-wrap;\"></pre>');
$('html pre').text(response);
}
});  
}
else {
}
// end other pages
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
$snippet_code = '{%if shop.metafields.FacebookPixelCode != blank or shop.metafields.FacebookPixelTag != blank %}
{% if template contains "collection" or template contains "product" %}
{% if template contains "collection" %}
<div class="selCollection_fb" style="display:none;">
{%- for field in shop.metafields.FacebookPixelCode -%}
{%assign pixelCode = field | last %}
{%unless pixelCode contains "&with&" %}
{%assign pixelCode = pixelCode | append: "&with&all" %}
{%endunless%}
{%assign selCol = pixelCode | split:"&with&" %}
{%if selCol[1] == "all"%}
<p data-pixelId="{{selCol[0]}}">{{selCol[1]}}</p>
{%else%}
{%if collection.handle == selCol[1] %}
<p data-pixelId="{{selCol[0]}}">{{selCol[1]}}</p>
{%endif%}
{%endif%}
{%- endfor -%}
</div>
{%endif%}
{% if template contains "product" %}
<div class="productCol_fb" style="display:none;">
{%- for field in shop.metafields.FacebookPixelCode -%}
{%assign pixelCode = field | last %}
{%unless pixelCode contains "&with&" %}
{%assign pixelCode = pixelCode | append: "&with&all" %}
{%endunless%}
{%assign selCol = pixelCode | split:"&with&" %}
{%if selCol[1] == "all"%}
<p data-pixelId="{{selCol[0]}}">{{selCol[1]}}</p>
{%endif%}
{%for collection in product.collections %}
{%assign colURL = collection.url | remove:"/collections/"%}
{%if selCol[1] == colURL and selCol[1] != "all" %}
<p data-pixelId="{{selCol[0]}}">{{selCol[1]}}</p>
{%endif%}
{%endfor%}
{%- endfor -%}
{%- for field in shop.metafields.FacebookPixelTag -%}
{%assign pixelTag = field | last %}
{%assign pixel_tag = pixelTag | split:"&with&" %}
{%for tag in product.tags %}
{%if tag == pixel_tag[1] %}
<p data-pixelId="{{pixel_tag[0]}}">{{pixel_tag[1]}}</p>
{%endif%}
{%endfor%}
{%- endfor -%}
</div>
{%endif%}
{% elsif template contains "cart" %}
{%if cart.item_count > 0 %}
{% assign pixelArr = "" %}
{%- for field in shop.metafields.FacebookPixelCode -%}
{%assign pixelCode = field | last %}
{%unless pixelCode contains "&with&" %}
{%assign pixelCode = pixelCode | append: "&with&all" %}
{%endunless%}
{%assign selCol = pixelCode | split:"&with&" %}
{%if selCol[1] == "all"%}
{%assign temp = selCol[0] | append:"," %}
{%assign pixelArr = pixelArr | append:temp %}
{%endif%}
{% for item in cart.items %}
{%for collection in item.product.collections %}
{%if selCol[1] == collection.handle and selCol[1] != "all" %}
{%assign temp = selCol[0] | append:"," %}
{%assign pixelArr = pixelArr | append:temp %}
{%endif%}
{%endfor%}
{%endfor%}
{%endfor%}

{% assign pixelTagArr = "" %}
{%for field in shop.metafields.FacebookPixelTag %}
{%assign pixelTag = field | last %}
{%assign pixel_tag = pixelTag | split:"&with&" %}
{% for item in cart.items %}
{%for tag in item.product.tags %}
{%if tag == pixel_tag[1] %}
{%assign temp = pixel_tag[0] | append:"," %}
{%assign pixelTagArr = pixelTagArr | append:temp %}
{%endif%}
{%endfor%}
{%endfor%}
{%endfor%}

{%assign pixelArr = pixelArr | split:"," | uniq %}
{%assign pixelTagArr = pixelTagArr | split:"," | uniq %}
<div class="cartCol_fb" style="display:none;">
{% for pixel in pixelArr %}
<p>{{pixel}}</p>
{%endfor%}
{% for pixel in pixelTagArr %}
<p>{{pixel}}</p>
{%endfor%}
</div>
{%endif%}
{%else%}
<div class="shopMetafields" style="display:none;">
{%- for field in shop.metafields.FacebookPixelCode -%}
{%assign pixelCode = field | last %}
{%if pixelCode contains "&with&" %}
{%if pixelCode contains "&with&all" %}
{%assign pixel = pixelCode | split:"&with&" %}
<p>{{pixel[0]}}</p>
{%endif%}
{%else%}
<p>{{pixelCode}}</p>
{%endif%}
{%- endfor -%}
</div>
{%endif%}
<div class="shopCurrency" style="display:none;">{{shop.currency}}</div>
{%endif%}
<script src="'.$permanent_domain.'" defer="defer"></script>';


$snippet_fields = array( "asset" => array('key' => 'snippets/fbTrack.liquid', 'value' => $snippet_code));

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
if (strpos($themeFile['value'], "{%include 'fbTrack' %}") === false || strpos($themeFile['value'], "<!-- hnk portfolio proof -->") === false) {
$newHtml = $themeFile['value'];
if (strpos($themeFile['value'], "{%include 'fbTrack' %}") === false) {
$replacedCode = "{%include 'fbTrack' %}</head>";
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