<?php
require __DIR__.'/connect.php'; //Database
require __DIR__.'/conf.php';
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
if(isset($_REQUEST['shop'])) {
	setcookie('access_token', '', time() + 86300, "/");
	setcookie('shop_url', '', time() + 86300, "/");
	$sql = "UPDATE PixelInstaller SET access_token='', active=0, charge_id='' WHERE shop_url='".$_REQUEST['shop']."'";
	$result = mysqli_query($con, $sql);	echo $permission_url = shopify\authorization_url($_GET['shop'], SHOPIFY_APP_API_KEY, array('read_script_tags', 'write_script_tags','read_themes','write_themes','read_products'),SHOPIFY_REDIRECT_URL);
}
?>