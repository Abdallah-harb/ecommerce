<?php
	
	/*
		Categories = [ Manage | Edit | Update | Add | insert | Delete |Stats]
	*/
		$do = isset($_GET['do'])?$_GET['do'] :'Manage';

		/*
		if(isset($_GET['do'])){
			$do = $_GET['do'];
		}else{	$do = 'Manage';
		}
		*/

		if($do=='Manage'){
			echo "You are welcome on the manage category page";
		}elseif ($do == 'Add') {
			echo "Welcome on the category page to Add ";

		}elseif($do == 'Insert'){

			echo "Welcome you are on the insert category page";

		}else{

			echo "error has ocured";
		}
			
?>