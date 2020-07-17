<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: FALSE');
$shop = $_REQUEST['shop'];
$xml = file_get_contents(APP_SERVER_URL."pull/$shop/feeds.xml");
print_r($xml);
?>