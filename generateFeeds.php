<?php
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
$access_token = $_REQUEST['access_token'];
$collectionID = $_REQUEST['collection'];
$type = $_REQUEST['type'];
$shop = $_REQUEST['shop'];
$shopify = shopify\client($shop, SHOPIFY_APP_API_KEY, $access_token);
try
{
	if($collectionID) {
		
		$shopData = $shopify("GET /admin/shop.json");
		
		if($type == 'custom') {
			$collection = $shopify("GET /admin/custom_collections/$collectionID.json");
		}
		if($type == 'smart') {
			$collection = $shopify("GET /admin/smart_collections/$collectionID.json");
		}
		
		$xml = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
		$xml .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . "\n\n";
		// channel required properties
		$xml .= '<channel>' . "\n\n";
		$xml .= '<title>' . $shopData['name'] . '</title>' . "\n";
		$xml .= '<link>https://' . $shopData['domain'] . '</link>' . "\n\n\n";
		$xml .= '<!-- Collection: ' . $collection['title'] . ' -->' . "\n";
		$xml .= '<!-- Total product count: ' . $collection['products_count'] . ' -->' . "\n\n\n";
		
		//start Products Data
		$limit = 100;
		if($collection['products_count'] > $limit ) {
			$pages = ceil($collection['products_count'] / $limit);
		} else {
			$pages = 1;
		}
		for($i=1;$i<=$pages;$i++) {
			$productsData = $shopify("GET /admin/products.json?collection_id=$collectionID&limit=$limit&page=$i");
			// get RSS channel items
			$xml .= "<!-- Page: $i of $pages -->" . "\n\n";
			if($productsData) {
				foreach($productsData as $item) {
				  $xml .= '<item>' . "\n";
					  $xml .= '<g:id>' . $item['id'] . '</g:id>' . "\n";
					  $xml .= '<g:title>' . $item['title'] . '</g:title>' . "\n";
					  $xml .= '<g:description>' . $item['body_html'] . '</g:description>' . "\n";
					  $xml .= '<g:link>https://' . $shop . '/' . $item['handle'] . '</g:link>' . "\n";
					  $xml .= '<g:image_link>' . $item['image']['src'] . '</g:image_link>' . "\n";
					  $xml .= '<g:brand>' . $item['product_type'] . '</g:brand>' . "\n";
					  $xml .= '<g:price>' . $item['variants'][0]['price'] . '</g:price> ' . "\n";
					  $xml .= '<g:category>' . $collection['title'] . '</g:category>' . "\n";
				  $xml .= '</item>' . "\n\n\n";
				}
			}
		}
		//end Products Data
		$xml .= '</channel>' . "\n\n";
		$xml .= '</rss>';
		
		if(is_dir('pull')) {
			if (!is_dir('pull/'.$shop)) {
				mkdir('pull/'.$shop);
				$filepath = 'pull/'.$shop;
			} else {
				$filepath = 'pull/'.$shop;
			}
			$filename = $filepath.'/feeds.xml';
			$xmlfile = fopen($filename, "w");
			fwrite($xmlfile, $xml);
			fclose($xmlfile);
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