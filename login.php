<?php 
	ob_start();
	session_start();
	$pageTitle = "Login";
	if(isset($_SESSION['User'])){

		header('Location:homepage.php'); //redirect to index if i login before

	}
	include "init.php";

	if($_SERVER['REQUEST_METHOD'] == 'POST'){

			//for form Login [Name on submit = Login]	
			if(isset($_POST['Login'])){



			$user     = $_POST['username'];
			$pass 	  = $_POST['password'];
			$hashpass = sha1($pass);


			//select data to login
			$stmt = $db->prepare(" SELECT `UserID`,`UserName`,`password` 
										    FROM `users`
										    WHERE `UserName` = ?
										    AND `password`   = ?
							    ");
		    $stmt->execute(array($user,$hashpass));

		    $get = $stmt->fetch();

			$count =$stmt->rowCount();

			if($count > 0){

				$_SESSION['User'] = $user; // [User] is the session name 
				$_SESSION['uid']  = $get['UserID']; // session id for user
				header('Location:homepage.php');
				exit();
			}

			//for form signup [Name on submit = signup]	
		}else{
			
			$formerrors = array();

			$username    = $_POST['username'];
			$useremail   = $_POST['email'];
			$userpass1   = $_POST['password'];
			$userpass2   = $_POST['password-agin'];

			if(isset($username)){

				$filterUser = filter_var($username, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
				if(strlen($filterUser) < 3){

					$formerrors[] = 'User Name Must be Larger Than 3 characters';
				}
			}
			if(isset($userpass1) && isset($userpass2)){

				if(empty($userpass1)){

					$formerrors[] = 'Password Can\'t be empty ';
				}
				if(strlen($userpass1) && strlen($userpass2) <3){

					$formerrors[] = 'Password Must be larger than 3 chars';
				}

				$pass1 = sha1($userpass1);
				$pass2 = sha1($userpass2);

				if($pass1 !== $pass2){

					$formerrors[] = 'Password not Identical with password2';
				}
			}

			if(isset($useremail)){

				$filterEmail = filter_var($useremail, FILTER_SANITIZE_EMAIL);
				if(filter_var($filterEmail,FILTER_VALIDATE_EMAIL) !=TRUE){

					$formerrors[] = 'This Email IS Not Valid';
				}
			}

			//start to insert new user

				//if there are no errors insert data
				if(empty($formerrors)){

					//check if user exist on the database 
					$check = checkItem("UserName","users",$username);
					if($check == 1){

							$formerrors[] = 'Sorry User Name is exist on database';



					}else{

						//Insert date
						$stmt = $db->prepare("  INSERT INTO `users`
														(`UserName`,`email`,`password`,
															`Regstatus`,`Date`)
											    VALUES (:zuser, :zemail, :zpass, 0,now())");

						//execute query
						$stmt->execute(array(

											'zuser'  => $username,
											'zemail' => $useremail,
											'zpass'  => sha1($userpass1)

						));
						//success message 
							$success = 'Congertalte you are register.!';      
					}
					
					
				}


		}	

	}

?>
	<div class="container login-page">
		<h1 class="text-center">
		 	<span class="selected" data-class="login">Login |</span> <span data-class="signup"> SignUp</span>
		</h1>
	<!-- start login form -->
		<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<div class="input-control">
				<input type="text" name="username" class="form-control"
				 	autocomplete="off" placeholder="Type your Name" required>
			</div>	
			<div class="input-control"> 
				<input type="password" name="password" class="form-control"
					 autocomplete="new-password" placeholder="Write Your password"required>
			</div>		 
			<div class="d-grid gap-2">
				<input class="btn btn-primary btn-block " value="Login" type="submit" name="Login">
		    </div>
		</form>
		<!-- End login form -->

		<!-- start signup form -->
		<form class="signup" action="" method="POST">
			<div class="input-control">
				<input type="text" name="username" class="form-control" 
					autocomplete="off" placeholder="Write Your Name"
					pattern=".{3,10}" title="Name char between 2 : 10 chars"
					required>
			</div>
			<div class="input-control">		
				<input type="email" name="email" class="form-control" 
					autocomplete="off" placeholder="Write a Valid Email" required>
			</div>
			<div class="input-control">		
				<input type="password" name="password" class="form-control"
					 autocomplete="new-password" placeholder="Write New password"
					 minlength="4" required>
			</div>
			<div class="input-control">		
				<input type="password" name="password-agin" class="form-control"
					 autocomplete="new-password" placeholder="Write Your password Again"
					 minlength="4" required>
			</div>
			<div class="d-grid gap-2">
				<input class="btn btn-success btn-block " value="Signup" type="submit" name="signup">
		    </div>
		</form>
		<!-- end signup form -->

		<div class="error-handle text-center">
			<?php 
				if (!empty($formerrors)){

					foreach($formerrors as $error){

						echo "<div class= 'alert alert-danger'>";
							echo $error ."<br>";
						echo "</div>";
					}
				}

				if(isset($success)){

					echo "<div class='alert alert-success'>".$success."</div>";
				}
			?>
		</div>
	</div>
		
<?php
ob_end_flush();