<?php
session_start();
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
require __DIR__.'/conf.php';
# Guard: http://docs.shopify.com/api/authentication/oauth#verification
shopify\is_valid_request_hmac($_GET, SHOPIFY_APP_SHARED_SECRET) or die('Invalid Request! Request or redirect did not come from Shopify');
# Step 2: http://docs.shopify.com/api/authentication/oauth#asking-for-permission
if (!isset($_GET['code']))
{
	$permission_url = shopify\authorization_url($_GET['shop'], SHOPIFY_APP_API_KEY, array('read_themes',  'write_themes', 'read_script_tags', 'write_script_tags', 'read_products','write_products','read_product_listings'),SHOPIFY_REDIRECT_URL);
	die("<script>window.location.href='$permission_url'</script>");
	//die("<script>top.location.href='$permission_url'</script>");
}
?>