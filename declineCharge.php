<style>
.container {
    width: 85%;
    background: #fff;
    padding: 20px;
    margin: 0 auto;
    -webkit-box-shadow: 0 0 0 1px rgba(63, 63, 68, 0.05), 0 1px 3px 0 rgba(63, 63, 68, 0.15);
    box-shadow: 0 0 0 1px rgba(63, 63, 68, 0.05), 0 1px 3px 0 rgba(63, 63, 68, 0.15);
    border-radius: 3px;
}
.alert.notification {
    background: #fcf1cd;
    padding: 20px;
	margin-bottom: 20px;
	-webkit-box-shadow: inset 0 3px 0 0 #eec200, inset 0 0 0 0 transparent, 0 0 0 1px rgba(63, 63, 68, 0.05), 0 1px 3px 0 rgba(63, 63, 68, 0.15);
    box-shadow: inset 0 3px 0 0 #eec200, inset 0 0 0 0 transparent, 0 0 0 1px rgba(63, 63, 68, 0.05), 0 1px 3px 0 rgba(63, 63, 68, 0.15);
}
.notification dl {
    margin: 0;
}
.button {
    position: relative;
    display: inline-block;
    margin: 0;
    padding: 0.5rem 1rem;
	font-size: 14px;
    background: -webkit-gradient(linear, left top, left bottom, from(#6371c7), to(#5563c1));
    background: linear-gradient(180deg, #6371c7, #5563c1);
    color: #fff;
    fill: #fff;
    border-radius: 0.3rem;
    text-decoration: none;
    cursor: pointer;
    font-family: -apple-system, "BlinkMacSystemFont", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", sans-serif;
    font-weight: normal;
}
.alert dl dt {
    font-size: 15px;
    line-height: 10px;
    font-weight: 600;
    margin: 0;
    color: #212b36;
    text-align: left;
    font-family: -apple-system, "BlinkMacSystemFont", "San Francisco", "Roboto", "Segoe UI", "Helvetica Neue", sans-serif;
	padding-left: 31px;
    padding-bottom: 8px;
}
.alert dl dd {
    line-height: 1.4;
    color: #212b36;
    margin: 10px 0 0 0;
    font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    font-size: 15px;
    font-weight: 400;
    font-style: inherit;
    letter-spacing: 0.4px;
}
.alert dl dt::before {
    background-image: url(https://snapmultipixel.website/images/icon-notess.png);
    height: 36px;
    width: 37px;
    display: inline-block;
    background-repeat: no-repeat;
    position: absolute;
    top: -12px;
    left: -8px;
    content: "";
}
.alert dl{
	position:relative;
}
</style>
<?php
	if(isset($_COOKIE['timestamp'])) {
		setcookie('timestamp', '', time() - 86300);
	}
	$billing = "https://$shop/admin/apps/".SHOPIFY_APP_HANDLE;
	$apps = "https://$shop/admin/apps/";
?>
<div class="container">
	<article>
		<div class="alert notification">
			<dl>
			  <dt>Charge Declined</dt>
			  <dd>In order to access FBTrack - Multiple Facebook Pixel Manager and enjoy its features, you must approve the app's charge.
			  If you prefer to uninstall, please click the Back to Apps button, and uninstall the app in Apps screen.</dd>
			</dl>
		  </div>
		<a target="_parent" href="<?php echo $billing; ?>" class="button">Billing screen</a>
		<a target="_parent" href="<?php echo $apps; ?>" class="button secondary">Back to Apps</a>
	</article>
</div>