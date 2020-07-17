<?php
//session_start();
// Required File Start.........
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/connect.php'; //Database
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
// Required File END...........

if((isset($_REQUEST['shop'])) && (isset($_REQUEST['code'])) && $_REQUEST['shop']!='' && $_REQUEST['code']!='' )
{
	$shop = $_REQUEST['shop'];
	$sql1 = "SELECT * FROM PixelInstaller WHERE shop_url='$shop' AND active = 1";
	$resData = mysqli_query($con, $sql1);
	if(mysqli_num_rows($resData) > 0) {
		//redirect to dashboard
		header('location:dashboard.php?shop='.$shop);
	} 
	else 
	{
		$testSql = "SELECT * FROM PixelInstaller WHERE shop_url='$shop'";
		$testResData = mysqli_query($con, $testSql);
		if(mysqli_num_rows($testResData) > 0) {
			$storedData = mysqli_fetch_assoc($testResData); 
			$timestamp = $storedData['timestamp'];
			if(isset($_COOKIE['delineCharge']) == 'delineCharge') {
				setcookie('delineCharge', '', time() - 86300);
				include_once('declineCharge.php');
			} else {
				//redirect to appSetup.php
				$access_token = shopify\access_token($shop, SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_REQUEST['code']);
				if($access_token) {
					if(SHOPIFY_APP_PRICE == '0.00') {
						$sql2 = "UPDATE PixelInstaller SET timestamp='".$timestamp."', access_token = '".$access_token."', active=1 WHERE shop_url='$shop' ";
						$insert = mysqli_query($con, $sql2);
						if($insert == 1 ) {
							setcookie('showVideo', 'showVideo', time() + 86300, "/");
							$shopUrl = "https://$shop/admin/apps/".SHOPIFY_APP_HANDLE;
							echo "<script> window.location.href='$shopUrl'</script>";
						} else {
							echo 'Unable to add shop to database.  The application has not been installed';
						}
					} else {
						setcookie('showVideo', 'showVideo', time() + 86300, "/");
						$updatesql2 = "UPDATE PixelInstaller SET timestamp='".$timestamp."' , access_token='".$access_token."' WHERE shop_url='$shop' ";
						$result = mysqli_query($con, $updatesql2);
						if($result == 1) {
							include_once('recurringPayment-noTrial.php');
						}
					}
				} else {
					echo 'Unable to get access token.  The application has not been installed.';
				}
			}
		} else {
			if(isset($_COOKIE['delineCharge']) == 'delineCharge') {
				setcookie('delineCharge', '', time() - 86300);
				include_once('declineCharge.php');
			} else {
				$access_token = shopify\access_token($shop, SHOPIFY_APP_API_KEY, SHOPIFY_APP_SHARED_SECRET, $_REQUEST['code']);
				if($access_token) {
					include_once('ShopOwnerData.php');
					if(SHOPIFY_APP_PRICE == '0.00') {
						$sql2 = "INSERT INTO PixelInstaller (access_token, shop_url, charge_id, active, timestamp) VALUES ('".$access_token."', '".$shop."', '0', '1', NOW())";
						$insert = mysqli_query($con, $sql2);
						if($insert == 1 ) {
							setcookie('showVideo', 'showVideo', time() + 86300, "/");
							$shopUrl = "https://$shop/admin/apps/".SHOPIFY_APP_HANDLE;
							echo "<script> window.location.href='$shopUrl'</script>";
						} else {
							echo 'Unable to add shop to database.  The application has not been installed';
						}
					} else {
						setcookie('showVideo', 'showVideo', time() + 86300, "/");
						include_once('recurringPayment.php');
					}
				} else {
					echo 'Unable to get access token.  The application has not been installed.';
				}
			}
		}
	}
} else {
	echo 'The application has not been installed.';
}
?>