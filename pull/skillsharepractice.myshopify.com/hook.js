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
var shop = 'skillsharepractice.myshopify.com';
var server = 'https://3ginfo.in/shopify/globe/';

// cart page
if(pageURL.indexOf('cart') > -1) {
console.log('ddddd');
}
// product pages
else if(pageURL.indexOf('/products/') > -1) {
}
// start collection page
else if(pageURL.indexOf('/collections') > -1) {
}

else {
}

}