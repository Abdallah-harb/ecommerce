$(function (){

	'use strict';
	
	//switch between login and signup

	$('.login-page h1 span').click(function(){

		$(this).addClass('selected').siblings().removeClass('selected');
		
		$('.login-page form').hide();

		$('.'+$(this).data('class')).fadeIn(100);

	});
	
	//hide placehoder from input
	$('[placeholder]').focus(function (){

		$(this).attr('data-text',$(this).attr('placeholder'));
		$(this).attr('placeholder',"");
	}).blur(function (){

		$(this).attr('placeholder',$(this).attr('data-text'));

	});

	//add * to form filed that is required

	$('input').each(function(){
		if($(this).attr('required')==='required'){
			$(this).after('<span class="asterix">*</span>');
		}
	});

	/*
	===============================================
	=============== Make live preview =============
	===============================================
	add new data ads and show it on live preview
	*/
	$('.live-name').keyup(function(){

		$('.live-preview .caption h3').text($(this).val());
	});

	$('.live-desc').keyup(function(){
		$('.live-preview .caption p').text($(this).val());
	})

	$('.live-price').keyup(function(){
		$('.live-preview .price-tag').text('$'+ $(this).val());
	})

});