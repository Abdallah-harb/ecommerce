<?php
	/*
		=====================================
		==templet  page you can do that
		== Add | Edit | insert | Update |DELETE 
		=====================================
	*/
	ob_start();
	session_start();

	if(isset($_SESSION['Username'])){

		$pageTitle  = " ";

		include "init.php";
		

		$do = isset($_GET['do'])?$_GET['do'] :'Manage';

		if($do == 'Manage'){

			echo "Welcome";

		}elseif($do == 'Add'){


		}elseif($do == 'Insert'){


		}elseif ($do == 'Edit') {

			
		}elseif ($do == 'Update') {


			
		}elseif($do == 'Delete'){


		}

		include  $tpl  . "footer.php";

	}else{

		header('Location:index.php');
		exit();
	}

ob_end_flush();
?>


