$(function (){
	'use strict';

	//dashboardpage
		/*
		**function to show and hide body of panel 
		** Add + = to show panel body
		** Add - = to hide panel body
		*/
		$('.toggle-info').click(function(){

			$(this).toggleClass('selected').parent().next('.panel-body').fadeToggle(100);

			if($(this).hasClass('selected')){

				$(this).html('<i class="fa fa-minus fa-lg"></i>');

			}else{

				$(this).html('<i class="fa fa-plus fa-lg"></i>');
			}
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

	//confirm delete

	$('.confirm').click(function(){

		return confirm("Are You Sure To  Delete");
	});

	//confirm Activated

	$('.active').click(function(){

		return confirm("Are You Sure To  Active user");
	});
});