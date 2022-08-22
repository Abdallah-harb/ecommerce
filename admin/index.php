<?php 
	
	session_start();

	$noNavbar = '';//variable that page has it no nanavbar on it
	$pageTitle = "Login";
	if(isset($_SESSION['Username'])){

		header('Location:dashboard.php'); //redirect to dashboard if i login before

	}
	include "init.php";
	

	//check if user coming from http request

	if($_SERVER['REQUEST_METHOD'] == 'POST'){

		echo "<div class='container margin-top:10px'>";

		$username = $_POST['user'];
		$password = $_POST['pass'];
		$hashPass = sha1($password);

		//validate
		$errors = array();
		if(empty($username)){
			$errors [] = "User Name must be written";
		}
		if(strlen($username) <2){
			$errors[] = 'User Name must be larger than two characters';
		}
		if(strlen($username) >20){
			$errors[] = 'User Name must Not be larger than 10 characters';
		}
		if(empty($password)){
			$errors[] = "Password must be written";
		}

		//print error from form
		foreach($errors as $error){

			echo "<div class='text-center alert alert-danger' role='alert'>";
  							echo $error." <br>";
					echo "</div>";
		}
		//if no errors select data and login
		if(empty($errors)){
					//check if user exist on the db

				$stmt = $db->prepare(" SELECT `UserID`,`UserName`,`password` 
									    FROM `users`
									    WHERE `UserName` = ?
									    AND `password`   = ? 
									    AND `GroupId`    = 1
									    LIMIT 1
									  ");
			    $stmt->execute(array($username,$hashPass));
			    $row = $stmt->fetch();
				$count =$stmt->rowCount();
				if($count > 0){

					$_SESSION['Username'] = $username; //register session name from login form and Username is session name for admin only
					$_SESSION['ID'] = $row['UserID'];//register session id
					header('Location:dashboard.php');
					exit();

				}else{

					echo "error has ocured";
				}


		}else{
			echo "<div class='text-center alert alert-danger' role='alert'>";
  							echo "There are errors ocured";
					echo "</div>";
		}


		echo '</div>';
		
	}

?>
<!-- start main form for login -->
<form class="login" action="<?php echo $_SERVER['PHP_SELF']?>" method = "POST">
	<h4 class="text-center">Admin login</h4>
	<input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off">
	<input class="form-control" type="password" name="pass" placeholder="Password"
		   autocomplete="new-password" >
	<div class="d-grid gap-2">
		<input class="btn btn-primary btn-block " type="submit" name="Login">
	</div>
</form>

<!-- end main form for login -->




<?php	include $tpl . "footer.php"; ?>