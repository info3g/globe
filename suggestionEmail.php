<?php
header("Access-Control-Allow-Origin: *");
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST['access_token'];
$name = $_REQUEST['firstname'].' '.$_REQUEST['lastname'];
$email = $_REQUEST['email'];
$issue = $_REQUEST['issue'];
$storeurl = $_REQUEST['store_url'];
$query = $_REQUEST['query'];
$shopify = shopify\client($_REQUEST['shop'], SHOPIFY_APP_API_KEY, $access_token );
try
{
	$headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\b";
	$headers .= "From: " . EMAIL_TO . "\r\n";
    $headers .= "Reply-To: $email" . "\r\n";
	$message = '<html><body>';	
	$message .= "<p>Name: $name</p>";	
	$message .= "<p>Email: $email</p>";	
	$message .= "<p>Issue: $issue</p>";	
	$message .= "<p>Store Url: $storeurl</p>";
	$message .= "<p>$query</p>";
	$message .= '</body></html>';
	if(mail(EMAIL_TO,EMAIL_SUBJECT,$message,$headers)) {
		echo 'Sent';
	} else {
		echo 'Not';
	}
}
catch (shopify\ApiException $e)
{
	echo $e;
	print_r($e->getRequest());
	print_r($e->getResponse());
}
?>