<?php

	//initialization file (prev for any thing on the program as temp, path, .....)
	
	//error reportin
	
	ini_set('display_errors', 'ON');
	error_reporting(E_ALL);
	
    //connetion with database

	include 'connect.php';

	//Routes or path

	$lang   =  "includes/languages/"; 
	$tpl 	= 'includes/templates/'; //for templet
	$fun    = 'includes/function/';   //for function
	$css 	= 'layout/css/'; //for css 
	$js   	= "layout/js/"; //for js
	

	// include the important file 

	include  $fun  ."function.php";
	include  $lang . "english.php";
	include  $tpl  . "header.php";
	
	

 
?>