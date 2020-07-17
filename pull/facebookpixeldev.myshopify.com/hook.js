var dg$;
var script = document.createElement('script');
script.setAttribute('src', '//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');
script.addEventListener('load', function() {
  dg$ = $.noConflict(true);
  mainScript(dg$);
});
document.head.appendChild(script);

function mainScript($) {
  var showPixel = showImgPixel = '';
  var pageURL = window.location.href;
  var currency = $('.shopCurrency').text();
  var microdata = 1;
  var delay_show = 0;
  var shop = 'facebookpixeldev.myshopify.com';
  var server = 'https://3ginfo.in/shopify/development/';
  
  // cart page
  if(pageURL.indexOf('cart') > -1) {
    if($('.cartCol_fb p').length) {
      $('.cartCol_fb p').each(function() {
		var fbPixel = $(this).text();
		if(microdata == 0) {
			showPixel += "fbq('set', 'autoConfig', false, '"+fbPixel+"');";
		}
        showPixel += "fbq('init', '"+fbPixel+"');";
        showImgPixel += "<img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id="+fbPixel+"&ev=PageView&noscript=1'/>";
      });
      if(showPixel != '') {
        var fbTrackCode = "!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');";
		
		if(delay_show > 0) {
			delay_show = delay_show + '000';
			setTimeout(function() {
				$('head').append("<script>"+fbTrackCode+""+showPixel+"fbq('track', 'PageView');</script><noscript>"+showImgPixel+"</noscript>");
			}, delay_show);
		} else {
			$('head').append("<script>"+fbTrackCode+""+showPixel+"fbq('track', 'PageView');</script><noscript>"+showImgPixel+"</noscript>");
		}

        // Start On Checkout button click
        $('body').on('click', '[name="checkout"]', function(e) {
          //e.preventDefault();
          $('head').append("<script>"+fbTrackCode+"fbq('track', 'InitiateCheckout');</script>");
          /*setTimeout(function(){
            window.location.href = '/checkout';
          }, 1000);*/
        });

        if($('[name="checkout"]').length == 0) {
          $('body').on('click', 'form[action="/checkout"] [type="submit"]', function() {
            $('head').append("<script>"+fbTrackCode+"fbq('track', 'InitiateCheckout');</script>");
          });
        }
        // End On Checkout button click
      }
    }
  }
  // product pages
  else if(pageURL.indexOf('/products/') > -1) {
    if($('.productCol_fb p').length) {
      var showAddtoCartPixel = '';
      if (pageURL.indexOf('?variant=') > -1) {
        var product_url = pageURL.split('?variant=');
        product_url = product_url[0] + '.json';
      } else {
        var product_url = pageURL + '.json';
      }

      $('.productCol_fb p').each(function() {
        var handle = $(this).text();
        var fbPixel = $(this).attr('data-pixelid');
		if(microdata == 0) {
			showPixel += "fbq('set', 'autoConfig', false, '"+fbPixel+"');";
		}
        showPixel += "fbq('init', '"+fbPixel+"');";
        showImgPixel += "<img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id="+fbPixel+"&ev=PageView&noscript=1'/>";
      });

      $.ajax({
        url: product_url,
        dataType: 'jsonp',
        header: {
          'Access-Control-Allow-Origin': '*'
        },
        success: function(responseData) {
          var product = responseData.product;
          if(product.title.indexOf("'") > -1) {
			product.title = product.title.replace(/'/g, '');
		  }
          showPixel += "fbq('track', 'ViewContent', {content_ids: ["+product.id+"],content_type:'product_group',value: "+product.variants[0].price+", content_name: '"+product.title+"', currency: '"+currency+"', content_category: ''});";
          if(showPixel != '') {
            var fbTrackCode = "!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');";
            // On Add to cart click
            $('form[action="/cart/add"] [type="submit"], form[action="/cart/add"] [type="button"]').click(function(){
              var variantid = $('[name="id"]').val();
              $.each(product.variants, function(index) {
                if(product.variants[index].id == variantid){
                  var price = product.variants[index].price;
                  //showAddtoCartPixel += "fbq('track', 'AddToCart', {value: "+price+",currency: '"+currency+"'});";
				  showAddtoCartPixel += "fbq('track', 'AddToCart', {content_ids: ["+product.id+"],content_type:'product_group',value: "+price+", content_name: '"+product.title+"', currency: '"+currency+"', content_category: ''});";
                }
              });
              if(showAddtoCartPixel != '' ) {
                $('head').append("<script>"+fbTrackCode+""+showAddtoCartPixel+"</script>");
              }
            });
			
			if(delay_show > 0) {
				delay_show = delay_show + '000';
				setTimeout(function(){
					$('head').append("<script>"+fbTrackCode+""+showPixel+"fbq('track', 'PageView');</script><noscript>"+showImgPixel+"</noscript>");
				}, delay_show);
			} else {
				$('head').append("<script>"+fbTrackCode+""+showPixel+"fbq('track', 'PageView');</script><noscript>"+showImgPixel+"</noscript>");
			}

            // Start On Checkout button click
            $('body').on('click', '[name="checkout"]', function() {
              $('head').append("<script>"+fbTrackCode+"fbq('track', 'InitiateCheckout');</script>");
            });
            if($('[name="checkout"]').length == 0) {
              $('body').on('click', 'form[action="/checkout"] [type="submit"], [href="/checkout"]', function(){
                $('head').append("<script>"+fbTrackCode+"fbq('track', 'InitiateCheckout');</script>");
              });
            }
            // End On Checkout button click
          }
        }

      });
    }
  }
  // start collection page
  else if(pageURL.indexOf('/collections') > -1) {
    if($('.selCollection_fb p').length) {
      var fbTrackCode = "!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');";
      $('.selCollection_fb p').each(function() {
        var fbPixel = $(this).attr('data-pixelid');
		if(microdata == 0) {
			showPixel += "fbq('set', 'autoConfig', false, '"+fbPixel+"');";
		}
        showPixel += "fbq('init', '"+fbPixel+"');";
        showImgPixel += "<img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id="+fbPixel+"&ev=PageView&noscript=1'/>"; 
      });
      if(showPixel != '') {
		// Pixel here
		if(delay_show > 0) {
			delay_show = delay_show + '000';
			setTimeout(function(){
				$('head').append("<script>"+fbTrackCode+""+showPixel+"fbq('track', 'PageView');</script><noscript>"+showImgPixel+"</noscript>");
			}, delay_show);
		} else {
			$('head').append("<script>"+fbTrackCode+""+showPixel+"fbq('track', 'PageView');</script><noscript>"+showImgPixel+"</noscript>");
		}
		
        // Start On Checkout button click
        $('body').on('click', '[name="checkout"]', function() {
          $('head').append("<script>"+fbTrackCode+"fbq('track', 'InitiateCheckout');</script>");
        });
        if($('[name="checkout"]').length == 0) {
          $('body').on('click', 'form[action="/checkout"] [type="submit"], [href="/checkout"]', function(){
            $('head').append("<script>"+fbTrackCode+"fbq('track', 'InitiateCheckout');</script>");
          });
        }
        // End On Checkout button click
      }
    }
  }
  // end collection page
  // start Feed page
  else if(pageURL.indexOf('/apps/multipixel/v1/feeds') > -1 ){
	$('html').html('');
	$.ajax({
		url: server+'getFeeds.php?shop='+shop,
		type: 'GET',
		crossDomain : true,
		success: function(response) {
			$('html').html('<pre style="word-wrap: break-word; white-space: pre-wrap;"></pre>');
			$('html pre').text(response);
		}
	});  
  }
  // end Feed page
  // start other pages
  else {
    if($('.shopMetafields p').length) {
      $('.shopMetafields p').each(function() {
        var fbPixel = $(this).text();
		if(microdata == 0) {
			showPixel += "fbq('set', 'autoConfig', false, '"+fbPixel+"');";
		}
        showPixel += "fbq('init', '"+fbPixel+"');";
        showImgPixel += "<img height='1' width='1' style='display:none' src='https://www.facebook.com/tr?id="+fbPixel+"&ev=PageView&noscript=1'/>";
      });

      if(showPixel != '') {
        var fbTrackCode = "!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');";
		
		if(delay_show > 0) {
			delay_show = delay_show + '000';
			setTimeout(function(){
				$('head').append("<script>"+fbTrackCode+""+showPixel+"fbq('track', 'PageView');</script><noscript>"+showImgPixel+"</noscript>");
			}, delay_show);
		} else {
			$('head').append("<script>"+fbTrackCode+""+showPixel+"fbq('track', 'PageView');</script><noscript>"+showImgPixel+"</noscript>");
		}

        // Start On Checkout button click
        $('body').on('click', '[name="checkout"]', function() {
          $('head').append("<script>"+fbTrackCode+"fbq('track', 'InitiateCheckout');</script>");
        });
        if($('[name="checkout"]').length == 0) {
          $('body').on('click', 'form[action="/checkout"] [type="submit"], [href="/checkout"]', function(){
            $('head').append("<script>"+fbTrackCode+"fbq('track', 'InitiateCheckout');</script>");
          });
        }
        // End On Checkout button click
      }
    }
  }
  // end other pages
}