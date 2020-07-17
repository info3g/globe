var pageURL = window.location.href;
var server = 'https://3ginfo.in/shopify/development/';
var file_name = '3ginfo.in/shopify/development/pixelCodes.js';
if(pageURL.indexOf('/thank_you') > -1) {
	var dg$;
	var script = document.createElement('script');
	script.setAttribute('src', '//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');
	script.addEventListener('load', function() {
	  dg$ = $.noConflict(true);
	  mainScript(dg$,pageURL,server,file_name);
	});
	document.head.appendChild(script);
}

function mainScript($,pageURL,server) {
	
	$('head').append('<style> .loader_outer { width: 100%; } .loader { font-size: 20px; font-weight: bold; color: #ea0419; } .loader_icon { vertical-align: middle; } </style>');
	
	$('body .os-step__title:first').before('<div class="loader_outer"><div class="loader">Please stay on this page <img src="'+server+'images/lgloader.gif" alt="loader_icon" class="loader_icon" width="50" /></div></div>');
	
	
	var selected_script = document.querySelector('script[src*="'+file_name+'"]');
	if(selected_script) {
		var getData = selected_script.getAttribute("src");
		var data = getData.split('?')[1];

		var content_ids = [] , product_ids = [];
		if(document.head.innerHTML.indexOf('Shopify.checkout')) {
			var CheckoutData = document.head.innerHTML.split('Shopify.checkout')[1].split('</script>')[0].replace('=','');
			CheckoutData = JSON.parse(CheckoutData.replace(';',''));
			var line_itemsLen = CheckoutData.line_items.length;
			for(var i=0; i < line_itemsLen; i++) {
				content_ids.push(CheckoutData.line_items[i].variant_id);
				product_ids.push(CheckoutData.line_items[i].product_id);
			}
		}
		
		var request = createCORSRequest("GET", server+"getPixelCodejson.php?"+data+"&product_ids="+product_ids);
		if (request) {
			request.onload = function(){
				var price = document.getElementsByClassName('payment-due__price')[0].innerText;
				if(request.responseText) {
					var response = JSON.parse(request.responseText);
					if(response['pixelCode']) {
						var pixelCode = response['pixelCode'];
						var pixelTag = response['pixelTag'];
						var Collections = response['Collections'];									
						var currency = response['Currency'];
						var showPixel = '';
						var showImgPixel = '';
						var contentIDs = product_ids.join();
						var total_price = CheckoutData.total_price;
									
						var microdata = 1; var delay_show = 0; var purchase_conversion = 100; var shipping = 1;
						if(response['Adv_setting']) {
							var Adv_setting = response['Adv_setting'].split('&&');
							purchase_conversion = Adv_setting[0];
							delay_show = Adv_setting[1];
							microdata = Adv_setting[2];
							shipping = Adv_setting[3];
							// Advance calculation
							total_price = total_price * (purchase_conversion / 100);
							if(shipping == 0) {
								var shipping_rate = CheckoutData.shipping_rate.price;
								total_price = total_price - shipping_rate;
							}
						}

						$.each(pixelCode, function(index,value) {
							$.each(Collections, function(colIndex,Col) {
								if(value == Col) {
									if(microdata == 0) {
										showPixel += "fbq('set', 'autoConfig', false, '"+index+"');";
									}
									showPixel += "fbq('init', '"+index+"');";
									showImgPixel += "<img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id="+index+"&ev=PageView&noscript=1'/>";
								}
							});
						});
						
						$.each(pixelTag, function(index,tag) {
							showPixel += "fbq('init', '"+index+"');";
							showImgPixel += "<img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id="+index+"&ev=PageView&noscript=1'/>";
						});
						
						if(showPixel != '') {
							var script = document.createElement("script");
							var noscript = document.createElement("noscript");
							script.type = "text/javascript";
							script.innerHTML = "!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');"+showPixel+"fbq('track', 'PageView');fbq('track', 'Purchase',{ content_type: 'product_group', content_ids: ["+contentIDs+"], num_items: "+CheckoutData.line_items.length+", currency: '"+currency+"',value: "+total_price+"});";
							noscript.innerHTML = showImgPixel;
							// Delay
							if(delay_show > 0) {
								delay_show = delay_show + '000';
								setTimeout(function() {
									document.head.appendChild(script);
									document.head.appendChild(noscript);
									$('body .loader_outer').remove();
								}, delay_show);
							} else {
								document.head.appendChild(script);
								document.head.appendChild(noscript);
								$('body .loader_outer').remove();
							}
							// Delay
						}
					} else {
					    $('body .loader_outer').remove();
					}
				} else {
				    $('body .loader_outer').remove();
				}
			};
			request.send();
		}

	}
}

// Call API function
function createCORSRequest(method, url){
	var xhr = new XMLHttpRequest();
	if ("withCredentials" in xhr){
		xhr.open(method, url, true);
	} else if (typeof XDomainRequest != "undefined"){
		xhr = new XDomainRequest();
		xhr.open(method, url);
	} else {
		xhr = null;
	}
	return xhr;
}
/************************Smriti Bakshi******************************/