<?php
require __DIR__.'/connect.php'; //Database
$data = file_get_contents('php://input');
if($data) {
	$shopData = json_decode($data);	
	$sender = $shopData->email;	
	//mail($sender, 'App Uninstall Reason','Please let me know your reason to uninstall the App');
	$sql = "UPDATE PixelInstaller SET access_token='', active=0, charge_id='' WHERE shop_url='".$shopData->domain."'";
	$result = mysqli_query($con, $sql);
	setcookie('access_token', '', time() + 86300, "/");
	setcookie('shop_url', '', time() + 86300, "/");
	
	if (is_dir('pull/'.$shopData->myshopify_domain)) {
		$filepath = 'pull/'.$shopData->myshopify_domain;
		$filename = $filepath.'/hook.js';
		unlink($filename);
	}
	
	mail('veenit.3ginfo@gmail.com', 'Test App Uninstall '. $shopData->myshopify_domain ,'Please let me know your reason to uninstall the App');
}
?>