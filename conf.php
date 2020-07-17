<?php   
	define('SHOPIFY_APP_API_KEY', '9639bb792785f2cec413e8bdcc54b783');
	define('SHOPIFY_APP_SHARED_SECRET', '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');
	define('APP_SERVER_URL','https://3ginfo.in/shopify/globe/');
	define('SHOPIFY_REDIRECT_URL', APP_SERVER_URL.'appSetup.php');
    define('SHOPIFY_PURCHASE_RETURN_URL_WITH_TRIAL', APP_SERVER_URL.'paymentNew.php');
    define('SHOPIFY_PURCHASE_RETURN_URL', APP_SERVER_URL.'payment.php');
	define('SHOPIFY_APP_NAME', 'Globein Checkout - globe checkout');
	define('SHOPIFY_APP_PRICE', '0');
	define('SHOPIFY_PURCHASE_MODE', true); //This is for Testing mode, If you don't want to use the testing mode, then false this Option
	define('SHOPIFY_TRIAL_DAYS', 365);
	//define('SHOPIFY_APP_HANDLE', 'multiple-facebook-pixels-installer');
	define('SHOPIFY_APP_HANDLE', 'facedev');
	define('EMAIL_TO', 'veenit.3ginfo@gmail.com');
	define('EMAIL_SUBJECT', 'Globein Checkout App Query');
?>