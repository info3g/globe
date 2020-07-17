<?php
if (!isset($_SESSION)) session_start();
// Required File Start.........
require __DIR__.'/conf.php'; //Configuration
require __DIR__.'/connect.php'; //Database
require __DIR__.'/vendor/autoload.php';
use phpish\shopify;
// Required File END...........
error_reporting(E_ALL); 	
ini_set('display_errors', 1);
if((isset($_REQUEST['shop'])) && $_REQUEST['shop'] != '' ) {
	$shop = $_REQUEST['shop'];
	$sql = "SELECT * FROM PixelInstaller WHERE shop_url='$shop'";
	$result = mysqli_query($con, $sql);
	if(mysqli_num_rows($result) > 0){
		$row = mysqli_fetch_assoc($result);
		$access_token = $row['access_token'];
		include_once('uninstallApp.php');
?>
		<html>
		<head>
		    <title>Product Bundle</title>
	
			<!--Style-->
			<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,700" rel="stylesheet">
			<link href="css/jquery-ui.css" rel="stylesheet" />
			<link href="css/bootstrap.min.css"  rel="stylesheet" type="text/css"/>  
			<link href="css/main.css" rel="stylesheet" type="text/css"/>
			<!--Style-->
			<!--Script-->
			<script src="js/jquery.min.js"></script>
			<script src="js/jquery-ui.js"></script>
			<script src="js/bootstrap.min.js"></script>
			<script src="https://use.fontawesome.com/988a7dc35f.js"></script>
			<!--Script-->
		</head>
		<body>
		<div class="content-container">
			<div class="loader"><span>Loading..Please Wait</span></div>
			<div id="main-container" class="container">
					
				<div id="tabs">
					<ul>
					<li><a href="#feeds">Collections </a></li>
						<li><a href="#product_tag">Cart Page</a></li>
						<li><a href="#settings">Settings</a></li>
						
						<li><a href="#faq">Instruction</a></li>
						
						<li><a href="#contact_us">Product Subscription</a></li>
						<!--<li><a href="#advanced">Advanced</a></li> -->
						
					</ul>
					<div id="settings">
						<?php include_once(__DIR__.'/files/settings.php'); ?>
					</div>
					<div id="faq">
						<?php include_once(__DIR__.'/files/Faq.html'); ?>
					</div>
					<div id="demo_video">
						<?php include_once(__DIR__.'/files/demoVideo.php'); ?>
					</div>
					<div id="contact_us">
					
						<?php include_once(__DIR__.'/files/advanced.php'); ?>
			
					</div>
					 
					<div id="feeds">
						<?php include_once(__DIR__.'/files/productFeeds.php'); ?>
					</div>
					<div id="product_tag">
						<?php include_once(__DIR__.'/files/productTags.php'); ?>
					</div>
				</div>
				
				<div class="PixelPopup" style="display:none;">
					<div class="PixelPopup-inner">
						<a href="" class="popup_close"><i class="fa fa-times" aria-hidden="true"></i></a>
						<div class="popupForm"></div>
					</div>
				</div>
				<div class="TagPopup" style="display:none;">
					<div class="TagPopup-inner">
						<a href="" class="tagpopup_close"><i class="fa fa-times" aria-hidden="true"></i></a>
						<div class="popupTagForm"></div>
					</div>
				</div>
				<div class="view_demo_instruction" style="display:none;">
					<img src="images/instruction.png" />
				</div>
			</div>
		</div> 
		<script>
		// Cookies Code Start
		function setCookie(cname, cvalue, exdays) {
			var d = new Date();
			d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
			var expires = "expires="+d.toUTCString();
			document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
		}
		
		function getCookie(cname) {
			var name = cname + "=";
			var ca = document.cookie.split(';');
			for(var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
					return c.substring(name.length, c.length);
				}
			}
			return "";
		}

		function checkCookie() {
			var user = getCookie("username");
			if (user != "") {
				alert("Welcome again " + user);
			} else {
				user = prompt("Please enter your name:", "");
				if (user != "" && user != null) {
					setCookie("username", user, 365);
				}
			}
		}
		
		function deleteCookie(cname) {
			var d = new Date(); 
			d.setTime(d.getTime() - (1000*60*60*24)); 
			var expires = "expires=" + d.toGMTString(); 
			window.document.cookie = cname+"="+"; "+expires;
		}
		// Cookies Code End
		
		// On error Go to Main APP page
		function showLoader(shop) {
			$.ajax({
				url: 'removeShopData.php?shop='+shop,
				type: 'GET',
				success: function(response) {
					console.log(response);
					top.location.href = response;
				}
			});
		}
		// Add ScriptTag API on load
		function addScript(access_token,shop) {
			$.ajax({
				url: 'addScript.php?access_token='+access_token+'&shop='+shop,
				type: 'GET',
				success: function(response) {
					console.log(response);
				}
			});
		}		
			function addcart(access_token,shop) {	
			console.log('add file');
			$.ajax({	
				type: 'GET',			
				url: 'addCart.php?access_token='+access_token+'&shop='+shop,		
				success: function(response){
					console.log('adding file');					
					console.log(response);
					
					
				}
			});
		}
		function addcollection(access_token,shop) {	
			console.log('add file');
			$.ajax({	
				type: 'GET',			
				url: 'addCollection.php?access_token='+access_token+'&shop='+shop,		
				success: function(response){
							
					console.log(response);
					
					
				}
			});
		}
	
		// Add Asset				
		function addAsset(access_token,shop) {	
			console.log('add file');
			$.ajax({	
				type: 'GET',			
				url: 'addAsset.php?access_token='+access_token+'&shop='+shop,		
				success: function(response){
				console.log(response);
					
					
				}
			});
		}
		// add cart
		function addCart(access_token,shop) {	
			$.ajax({	
				type: 'GET',			
				url: 'addCart.php?access_token='+access_token+'&shop='+shop,		
				success: function(response){			
					console.log(response);
				}
			});
		}
		
		// Contact info on load
		function contactInfo(access_token,shop) {
			$.ajax({
				url: 'contactInfo.php?access_token='+access_token+'&shop='+shop,
				type: 'GET',
				success: function(response) {
					if($.trim(response)) {
						var contactData = JSON.parse(response);
						$('#contactUs #email').val(contactData['email']);
						$('#contactUs #store_url').val(contactData['myshopify_domain']);
						var shop_owner = contactData['shop_owner'].split(' ');
						$('#contactUs #firstname').val(shop_owner[0]);
						$('#contactUs #lastname').val(shop_owner[1]);
					}
				}
			});
		}
			
		function getAdvanced(access_token,shop) {
			$.ajax({
				url: 'getAdvanced.php?access_token='+access_token+'&shop='+shop,
				type: 'GET',
				success: function(response) {
					if($.trim(response)) {
						var advanced = response.split('&&');
						if(advanced[0]) {
							$('#purchase_conversion').val($.trim(advanced[0])).change();
						}
						if(advanced[1]) {
							$('#delay_show').val(advanced[1]);
						}
						if(advanced[2] == 0) {
							$('#enable_microdata').attr('checked', false);
						}
						if(advanced[3] == 0) {
							$('#include_shipping').attr('checked', false);
						}
					}
				}
			});
		}
		
		// Generate Feeds
	function generateFeeds(access_token,shop,collection,type) {
			$.ajax({
				url: 'generateFeeds.php?access_token='+access_token+'&shop='+shop+'&collection='+collection+'&type='+type,
				type: 'GET',
				success: function(response) {
					console.log(response);
				}
			});
		}
		
		// Get Advanced Settings
		function getproductFeeds(access_token,shop) {
						$.ajax({
				url: 'getproductFeeds.php?access_token='+access_token+'&shop='+shop,
				type: 'GET',
				success: function(response) {
					if($.trim(response)) {
						var colHtml = $('#select_collection').html();
						colHtml = colHtml.replace('<option value="all">All - Main / Backup Pixel</option>','');
						$('.feedCollections').html('<select name="selFeedsCol" id="selFeedsCol" class="form-control"><option value="0"> -Select- </option>'+colHtml+'</select>');
						if(response != 'test') {
							var rssData = JSON.parse(response);
							var collection_Data = rssData['collection'].split('=&&=');
							$('#selFeedsCol').val(collection_Data[0]);
							$('#product_feed_url').val(rssData['rssURL']);
							generateFeeds(access_token,shop,collection_Data[1],collection_Data[2]);
						}
					}
				}
			});
		}
			
		// Get Collections				
		function getCollections(access_token,shop) {
		
			$.ajax({	
				type: 'GET',
				url: 'getCollections.php?access_token='+access_token+'&shop='+shop,		
				success: function(response) {
					console.log(response);
					if($.trim(response).indexOf("[401]") > -1) {
						showLoader(shop);
							console.log('loading');
					} else if($.trim(response).indexOf("[429]") > -1) {
						console.log('Try again Later!');
						$(".loader").hide();
					} else {
						$(".loader").hide();
						if($.trim(response)) {
							getPixelCode(access_token,shop);
							getTagsCode(access_token,shop);
							var colHtml = '<label for="select_collection">Select Collection</label><select id="select_collection" class="selCollection" name="select_collection"><option value="all">All - Main / Backup Pixel</option>'+response+'</select>';
							$('.feedCollections').html(colHtml);
						}
					}
				}
			});
		}
			// GET pixel Tags on Load
		function getTagsCode(access_token,shop) {
			$.ajax({
				url: 'getTagsCode.php?access_token='+access_token+'&shop='+shop,
				type: 'GET',
				success: function(response) {
		
					if($.trim(response)) {
						$('.editProductTags').html('');
						var pixelRows = JSON.parse(response);
						$('form#tagSetting').addClass('haveprocess');
							$.each(pixelRows, function(index,value) {
							var pixel_with_Tag = value.split('&with&');
							var pixel = pixel_with_Tag[0];
							var selTag = pixel_with_Tag[1];
							if(selTag == undefined || selTag == '') {
								
									console.log('tag no');
								selTag = '';
					
							}
							var htmlPixel = '<tr><td><input class="form-control edittagpixel_fields" type="hidden" id="custId" name="pixelTagCode'+index+'" value='+pixel+'><input class="form-control edittagpixel_fields" type="text" name="pixelTagCode'+index+'" value="'+pixel+'" disabled /></td><td>$<input class="form-control selTags" type="text" name="selTag'+index+'" id="selTag'+index+'"  value="'+selTag+'" disabled /></td><td><a href="" class="delete_pixeltag" data-pixel-id="pixelTagCode'+index+'"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>';
							$('.editProductTags').append(htmlPixel);
						});
					}
				},
				complete: function() {
					$('#savepixelTag').val('ADD');
					
				}
			});
		}
		
		// GET Pixel Code on Load
		function getPixelCode(access_token,shop) {
			$.ajax({
				url: 'getPixelCode.php?access_token='+access_token+'&shop='+shop,
				type: 'GET',
				success: function(response) {
					if($.trim(response)) {
						$('.editPixelCode').html('');
						var pixelRows = JSON.parse(response);
						$.each(pixelRows, function(index,value) {
							var pixel_with_Col = value.split('&with&');
							var pixel = pixel_with_Col[0];
							var selCol = pixel_with_Col[1];
							if(selCol == undefined || selCol == '') {
								selCol = 'all';
							}
							var colHtml = $('#select_collection').html();
							var htmlPixel = '<tr><td><input class="form-control editpixel_fields" type="text" name="pixelCode'+index+'" value="'+pixel+'" disabled /></td><td><select name="selCol'+index+'" id="selCol'+index+'" class="selCols">'+colHtml+'</select></td><td><a href="" class="edit_pixel" data-pixel-id="pixelCode'+index+'"><i class="fa fa-pencil" aria-hidden="true"></i></a><a href="" class="delete_pixel" data-pixel-id="pixelCode'+index+'"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>';
							$('.editPixelCode').append(htmlPixel);
							$('#selCol'+index).val(selCol);
						});
					}
				},
				complete: function() {
					addScript(access_token,shop);
					addAsset(access_token,shop);
					addcart(access_token,shop);
					addcollection(access_token,shop);
					addCart(access_token,shop);
					contactInfo(access_token,shop);				
					getAdvanced(access_token,shop);
					getproductFeeds(access_token,shop);
					$('#savepixel').val('ADD');
					$('.PixelPopup').hide();
				}
			});
		}

		$(document).ready(function() {
			$( "#tabs" ).tabs();
			var access_token = '<?php echo $access_token ?>';
			var shop = '<?php echo $shop ?>';
			var icons = {
			  header: "ui-icon-plus",
			  activeHeader: "ui-icon-minus"
			};
			$( "#faq-accordion" ).accordion({
				collapsible: true,
				heightStyle: "content",
				icons: icons
			});
			var showVideo = getCookie('showVideo');
			if (showVideo != null && showVideo == 'showVideo') {
				$('.view_demo_instruction').show();
			}
			setTimeout(function() {
				$('.view_demo_instruction').hide();
				deleteCookie('showVideo');
			}, 3500);
			
			// get All collections
			getCollections(access_token,shop);
			
			// on change of select_collections on Main page
			$('body').on('mousedown', '.selCols', function() {
				var _thisVal = $(this).val();
				var _this = $(this);
				_this.on('change', function() {
					$(this).parents('tr').addClass('active');
					var changeVal = $(this).val();
					var allCol_count = 0;
					if(changeVal == 'all') {
						$('.editPixelCode tr').each(function(){
							selCol = $('.selCols',this).val();
							if(selCol == 'all') {
								allCol_count++;
							}
						});
						if(allCol_count > 6) {
							alert('You can add a maximum of 6 main/back up pixels. Please select another Collection');
							_this.val(_thisVal);
							_this.parents('tr').removeClass('active');
						}
					}
				});
			});
			
			// Edit Pixel Code
			$('body').on('click', '.edit_pixel', function(e) {
				e.preventDefault();
				var pixelRow = $(this).data('pixel-id');
				var rowID = pixelRow.replace('pixelCode', '');
				$('input[name="'+pixelRow+'"]').attr('disabled',false);
				$(this).parents('tr').addClass('active');
			});
			
			$('body').on('click', '#saveEditPixel', function(e) {
				if($('.editPixelCode tr').length) {
					var allCol_count = 0;
					$('.editPixelCode tr').each(function(){
						selCol = $('.selCols',this).val();
						if(selCol == 'all') {
							allCol_count++;
						}
					});
					if(allCol_count > 6) {
						alert('You can add a maximum of 6 main/back up pixels. Please select another Collection');
					} else {
						
						var pixelArr = {}, allPixels = {}, pixelflag = false;
						$('.editPixelCode tr.active').each(function() {
							allPixels[$('input',this).attr('name')] = $('input',this).val();
							pixelArr[$('select',this).attr('name')] = $('select',this).val();
							var pixelVal = $('input',this).val();
							var regex = /^[0-9]+$/;
							if (!pixelVal.match(regex)) {
								pixelflag = true;
								return false;
							}
						});
						
						if(pixelflag == true) {
							alert('Facebook Pixels are numerical fields');
						} else {
							pixelArr['allPixels'] = allPixels;
							$('#saveEditPixel').val('Saving...');
							$.ajax({
								url: 'editPixelCode.php?access_token='+access_token+'&shop='+shop,
								type: 'POST',
								data: pixelArr,
								success: function(response) {
									console.log(response);
								},
								complete: function() {
									$('#saveEditPixel').val('Save');
									$('.editPixelCode input').attr('disabled', true);
								}
							});
						}
					}
				}
			});
			// end Edit Pixel Code
			
			// Delete Pixel Code
			$('body').on('click', '.delete_pixel', function(e) {
				e.preventDefault();
				var _this = $(this);
				var pixelRow = $(this).data('pixel-id');
				$(this).html('<i class="fa fa-ellipsis-h" aria-hidden="true"></i>');
				$.ajax({
					url: 'deletePixelCode.php?access_token='+access_token+'&shop='+shop,
					type: 'POST',
					data: {'delPixelRow' : pixelRow},
					success: function(response) {
						_this.parents('tr').remove();
					}
				});
			});
		// Add New Pixel Code
			$('#addNewPixel').click(function(e){
				e.preventDefault();
				var count = 1, pixel_id = 1;
				if($('.editPixelCode tr').length) {
					count = $('.editPixelCode tr').length + 1;
					pixel_id = $('.editPixelCode tr:last-child .delete_pixel').attr('data-pixel-id');
					pixel_id = pixel_id.replace('pixelCode','');
					pixel_id = parseInt(pixel_id) + 1;
				}
				if(count > 20) {
					alert('You can add a maximum of 20 pixel IDs');
				} else {
					var colHtml = $('.select_collections').html();
					var newForm = '<form id="pixelSetting" name="pixelSetting"><h4 class="heading">Add new Pixel</h4><div class="form-group first"><label for="pixelEditor">Facebook Pixel Id</label><input class="form-control" name="pixelEditor" placeholder="XXXXXXXXX" id="pixelEditor" required /></div><div class="form-group">'+colHtml+'</div><input type="hidden" value="'+pixel_id+'" name="pixelCount" /><input type="submit" value="ADD" id="savepixel" class="btn btn-default" name="savepixel" /></form>';
					$('.popupForm').html(newForm);
					$('.PixelPopup').show();
				}
			});
			
			//Close Popup
			$('body').on('click', '.popup_close', function(e) {
				e.preventDefault();
				$('.PixelPopup').hide();
			});
			
			// Save New add Pixel Code
			$('body').on('submit', '#pixelSetting', function(e){
				e.preventDefault();
				var allCol_count = 0;
				var select_collection = $('#pixelSetting #select_collection').val();
				if(select_collection == 'all') {
					$('.editPixelCode tr').each(function(){
						selCol = $('.selCols',this).val();
						if(selCol == 'all') {
							allCol_count++;
						}
					});
				}
				if(allCol_count >= 6) {
					alert('You can add a maximum of 6 main/back up pixels. Please select another Collection');
				} else {
					var pixelVal = $('#pixelSetting #pixelEditor').val();
					var regex = /^[0-9]+$/;
					if (!pixelVal.match(regex)) {
						alert('Facebook Pixels are numerical fields');
						return false;
					} else {
						$('#savepixel').val('ADDING...');
						var formData = $('#pixelSetting').serialize();
						$.ajax({
							url: 'savePixelCode.php?access_token='+access_token+'&shop='+shop,
							type: 'GET',
							data: formData,
							success: function(response) {
								console.log(response);
							}, 
							complete: function() {
								getPixelCode(access_token,shop);
							}
						});
					}
				}
			});
			
			// Add New Pixel Tag
			$('#addNewTag').click(function(e){
				e.preventDefault();
				var count = 1, pixel_id = 1;
				if($('.editProductTags tr').length) {
					count = $('.editProductTags tr').length + 1;
					pixel_id = $('.editProductTags tr:last-child .delete_pixeltag').attr('data-pixel-id');
					pixel_id = pixel_id.replace('pixelTagCode','');
					pixel_id = parseInt(pixel_id) + 1;
				}
				if(count >= 1) {
					alert('You can add a maximum of 20 pixel IDs');
				} else {
				}
			});
		
			// Save New add Pixel Tag
			$('body').on('submit', '#tagSetting', function(e){
				e.preventDefault();
				var pixelVal = $('#tagSetting #pixeltagEditor').val();
				var regex = /^[0-9]+$/;
				if (!pixelVal.match(regex)) {
					alert('Facebook Pixels are numerical fields');
					return false;
				} else {
					$('#savepixelTag').val('ADDING...');
					var formData = $('#tagSetting').serialize();
					$.ajax({
						url: 'savePixelTag.php?access_token='+access_token+'&shop='+shop,
						type: 'GET',
						data: formData,
						success: function(response) {
							console.log(response);
							$('form#tagSetting').addClass('haveprocess');
							
						}, 
						complete: function() {
							getTagsCode(access_token,shop);
						}
					});
				}
			});
			
			// start Edit Pixel Tag
			$('body').on('click', '.edit_pixeltag', function(e) {
				e.preventDefault();
				var pixelRow = $(this).data('pixel-id');
				var rowID = pixelRow.replace('pixelTagCode', '');
				$('input[name="'+pixelRow+'"]').attr('disabled',false);
				$('input[name="selTag'+rowID+'"]').attr('disabled',false);
				$(this).parents('tr').addClass('active');
			});
			
			$('body').on('click', '#saveEditTags', function(e) {
				if($('.editProductTags tr').length) {
					var pixelArr = {}, allPixels = {}, pixelflag = false;
					$('.editProductTags tr.active').each(function() {
						allPixels[$('.edittagpixel_fields',this).attr('name')] = $('.edittagpixel_fields',this).val();
						pixelArr[$('.selTags',this).attr('name')] = $('.selTags',this).val();
						var pixelVal = $('.edittagpixel_fields',this).val();
						var regex = /^[0-9]+$/;
						if (!pixelVal.match(regex)) {
							pixelflag = true;
							return false;
						}
					});
					
					if(pixelflag == true) {
						alert('Facebook Pixels are numerical fields');
					} else {
						pixelArr['allPixels'] = allPixels;
						$('#saveEditTags').val('Saving...');
						$.ajax({
							url: 'editProductTags.php?access_token='+access_token+'&shop='+shop,
							type: 'POST',
							data: pixelArr,
							success: function(response) {
								//console.log(response);
							},
							complete: function() {
								$('#saveEditTags').val('Save');
								$('.editProductTags input').attr('disabled', true);
							}
						});
					}
				}
			});
			// end Edit Pixel Code
			
			// Delete Pixel Tag
			$('body').on('click', '.delete_pixeltag', function(e) {
				e.preventDefault();
				var _this = $(this);
				var pixelRow = $(this).data('pixel-id');
				$(this).html('<i class="fa fa-ellipsis-h" aria-hidden="true"></i>');
				$.ajax({
					url: 'deletePixelTag.php?access_token='+access_token+'&shop='+shop,
					type: 'POST',
					data: {'delPixelRow' : pixelRow},
					success: function(response) {
						_this.parents('tr').remove();
						$('form#tagSetting').removeClass('haveprocess');
					}
				});
			});
			
			
			// Send Email
			$('form#sendEmail').click(function(e){
				if($('#firstname').val() != '' && $('#lastname').val() != '' && $('#email').val() != '' && $('#storeurl').val() != '' && $('#query').val() != '') {
					e.preventDefault();
					$('#contactUs .alert').hide();
					var formData = $('form#contactUs').serialize();
					$.ajax({
						url: 'sendEmail.php?access_token='+access_token+'&shop='+shop,
						type: 'GET',
						data: formData,
						success: function(response) {
							if(response.indexOf('Sent') > -1) {
								$('#contact_us .alert-success').show().fadeOut(4000);
							} else {
								$('#contact_us .alert-danger').show().fadeOut(4000);
							}
						}
					});
				}
			});
			// End Send Email
			/****************suggestion form start**********************/
				$('form#sendEmail').click(function(e){
				if($('#firstname').val() != '' && $('#lastname').val() != '' && $('#email').val() != '' && $('#store_url').val() != '' && $('#query').val() != '') {
					e.preventDefault();
					$('#contactUs .alert').hide();
					var formData = $('form#contactUs').serialize();
					$.ajax({
						url: 'sendEmail.php?access_token='+access_token+'&shop='+shop,
						type: 'GET',
						data: formData,
						success: function(response) {
							if(response.indexOf('Sent') > -1) {
							console.log('data send');
								$('#contact_us .alert-success').show().fadeOut(4000);
							} else {
										console.log('not send');
								$('#contact_us .alert-danger').show().fadeOut(4000);
							}
						}
					});
				}
			});
			
			$('#suggestion').click(function(e){
				if($('#issue').val() != '' && $('#query').val() != '') {
				e.preventDefault();
				console.log(8888);
				$('#suggestion .alert').hide();
					var formData = $('#suggestion').serialize();
					console.log(formData);					
					$.ajax({
						url: 'suggestionEmail.php?access_token='+access_token+'&shop='+shop,
						type: 'GET',
						data: formData,
						success: function(response) {
							console.log(1212121);
							if(response.indexOf('Sent') > -1) {
								$('#sendsuggestion .alert-success').show().fadeOut(4000);
								console.log('data send');
						} else {
							console.log('not send');
								$('#sendsuggestion .alert-danger').show().fadeOut(4000);
							}
						}
					});
				}
			});
			//App suggestion
			$('#suggestion').click(function(e){
				e.preventDefault();
				$('.suggestionPopup').show();
			});
			//App suggestion Close Popup
			$('body').on('click', '.popup_close', function(e) {
				e.preventDefault();
				$('.suggestionPopup').hide();
			});
		/****************form end*************************/
		/****************form adons*************************/
		  $('#adonsFormproduct').on('submit', function(e){
			e.preventDefault();
            var favorite = [];
            $.each($(".Collection-product ul li input[name='product']:checked"), function(){
                favorite.push($(this).val());
		
            });
				var prodcutcart = favorite.join(",");
        var str_arr = prodcutcart.toString();
        //window.location.replace('addCart.php?access_token='+access_token+'&shop='+shop+'&data_id='+prodcutcart);
        $.ajax({
        url: 'addCart.php?access_token='+access_token+'&shop='+shop+'&data_id='+str_arr,
        type: 'POST',
        success: function(response) {
        console.log('addCart.php?access_token='+access_token+'&shop='+shop+'&data_id='+str_arr);
        console.log(response);
        }
        });
        });
		
		/****************end adons form*************************/
			// save Advanced Data
			$('#advancedForm').on('submit', function(e){
				e.preventDefault();
				$('#saveAdvanced').val('Saving...');
				var formData = $(this).serialize();
				$.ajax({
					url: 'saveAdvanced.php?access_token='+access_token+'&shop='+shop,
					type: 'GET',
					data: formData,
					success: function(response) {
						$('#saveAdvanced').val('Saved');
						setTimeout(function(){ $('#saveAdvanced').val('Save'); }, 2000);
					},
					complete: function() {
						addAsset(access_token,shop);
						addCart(access_token,shop);
					}
				});
			});
			
				$('body').on('change', '#purchase_subscription', function() {
				var subscription = $(this).children("option:selected").val();
				 $('.subscriptiondata').hide();
				$('#' + $(this).val()).show();
				});
 
			
			
			
			
			
			// save Advanced Data
			
			// save Product Feeds
			$('body').on('change', '.feedCollections #selFeedsCol', function() {
				var selfeedCol = $(this).val();		
			//	alert(selfeedCol);
			console.log(selfeedCol);
				var selfeedCol_id = $(this).find(':selected').data('id');
				//var selfeedCol_type = $(this).find(':selected').data('type');		
				
				if(selfeedCol == 0) {
					alert('Please select another Collection');
				} else {
				
					$.ajax({
					type: 'GET',
						url: 'saveproductFeeds.php?access_token='+access_token+'&shop='+shop,
						type: 'GET',
						data: {'selfeedCol_id': selfeedCol_id },
						success: function(response){
							console.log(response);
							$(".Collection-product").empty();
							$(".Collection-product").append(response);
							
							
							
						
						}
					});
				}
			});
			// save Product Feeds
			// Copy to Clipboard
			$('body').on('click', '#copy_to_clipboard', function(e) {
				e.preventDefault();
				$('body .copied_txt').remove();
				var copyText = document.getElementById("product_feed_url");
				copyText.select();
				document.execCommand("copy");
				$('#product_feed_url').after("<p class='copied_txt'>Copied the text!</p>");
			});
			// Copy to Clipboard
			// delay Input
			$('#advancedForm #delay_show').on('keydown', function(e) {
				e.preventDefault();
				if (event.keyCode) {
					e.preventDefault();
				}
			});
			// delay Input
			
			// input Pixel Edit
			 $('body').on('keydown', '.editpixel_fields, #pixelSetting #pixelEditor', function(event) {
				var pixelVal = $(this).val();
				if (event.keyCode == '32') {
					event.preventDefault(); 
					alert('Facebook Pixels should not contain empty spaces');
				} else if (event.keyCode != 8 && (event.keyCode < 48 || event.keyCode > 57) ) {
					event.preventDefault(); 
					alert('Facebook Pixels are numerical fields');
				}
				
			}); 
			// input Pixel Edit
			
		});
		
		/***********repeater**********************/
		
		

		/***********repeater**********************/
		</script>
		</body>
	</html>
<?php
	}
}
?>