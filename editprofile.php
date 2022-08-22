<?php 
	
	ob_start();
	session_start();
	$pageTitle = "Edit Profile";
	include "init.php";
	if(isset($_SESSION['User'])){
	echo "<div class='container'>";
		$do = isset($_GET['do'])?$_GET['do'] :'Manage';

		if($do == 'Edit'){//edit page 
			// for userid request that is number and feom database
			$userid = isset($_GET['userId']) && is_numeric($_GET['userId'])? intval($_GET['userId']):0;
			//select data from database
			$stmt   = $db->prepare("SELECT * FROM `users` WHERE `UserID`= ? ");
			//execute query
			$stmt->execute(array($userid));
			//fetch data
			$row    = $stmt->fetch();
			//check if is larger than 0
			$count  = $stmt->rowCount();

			if($count >0){
			
			?>
				<h1 class="text-center"> Edit Profile <?php echo $_SESSION['User'] ?></h1>
				<div class="container">
					<form class="main-form form-horizontal" action="?do=Update" method="POST">
						<!--hidden id for user -->
						<input type="hidden" name="yourid" value="<?php echo $userid?>">
						<!--start username field -->
						<div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">UserName</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" name="username" 
							       	 	required="required"autocomplete="off" 
							       	 	value="<?php echo $row['UserName']?>">
						    </div>
						</div>
						<!--end username field -->
						<!--start email field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Email</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="email" class="form-control" name="email" 
					       	 		required="required" value="<?php echo $row['email']?>">
						    </div>
						</div>
						<!--end email field -->
						<!--start password field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Password</label>
						    <div class="col-sm-10 col-md-6">
						    	<input type="hidden"  name="oldpassword" 
						    		value="<?php echo $row['password'] ?>" >
					       	 	<input type="password" class="form-control" 
							       	 	name="newpassword"autocomplete="new-password"
							       	 	 placeholder="Leave Blank if you want to change">
						    </div>
						</div>
						<!--end password field -->
						<!--start fullname field -->
						<div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">FullName</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" name="fullname"
					       	 	 value="<?php echo $row['fullName']?>" required="required">
						    </div>
						</div>
						<!--end fullname field -->
						<!--start submit button-->
						<div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
					       	 	<input type="submit" class="btn btn-primary btn-lg" value="Save">
						    </div>
						</div>
						<!--end submit button-->
					</form>
				</div>
		<?php

			}else{

					$msg = '<div class="alert alert-danger">There are errors ocured to edit </div>';
					redirectHome($msg,'back');
			}
		}elseif($do == 'Update'){ //update page

			echo "<div class='container'>";
			echo "<h1 class='text-center'>Update Profile </h1>";
			if($_SERVER['REQUEST_METHOD'] == 'POST'){

				$id       = $_POST['yourid'];
				$name     = $_POST['username'];
				$email    = $_POST['email'];
				$fullname = $_POST['fullname'];
					//password truck 
				$pass =empty($_POST['newpassword'])?$_POST['oldpassword']:sha1($_POST['newpassword']);
					
					//validate form
				$formerrors = array();
				if(empty($name)){
					$formerrors[]="user Name cannot be empty";					
				}
				if(strlen($name) <3){
					$formerrors[]="user Name must be larger than<span class='text-bold'> 3 character</span>";					
				}
				if(strlen($name) >10){
					$formerrors[]="user Name mustNot be larger than<span class='text-bold'> 10 character</span>";					
				}
				if(empty($email)){
					$formerrors[]="emil cannot be <span class='text-bold'>empty</span>";					
				}
				if(empty($fullname)){
					$formerrors[]="fullName cannot be <span class='text-bold'>empty</span>";					
				}
				if(strlen($fullname) <5){
					$formerrors[]="fullname must be larger than <span class='text-bold'>5 character</span>";					
				}
				if(strlen($fullname) >20){
					$formerrors[]="fullname mustnot be larger than <span class='text-bold'>20 character</span>";					
				}
					//loop errors and print it
				foreach ($formerrors as $errors) {
					echo "<div class='text-center alert alert-danger' role='alert'>";
  							echo $errors." <br>";
					echo "</div>";
				}
				//if there are no errors update data
				if(empty($formerrors)){
					//$check = checkItem("UserName","users",$name);
					//if($check == 1){

							//$msg = '<div class="alert alert-danger">Sorry User Name is exist on database</div>';
						//	redirectHome($msg ,"back",4);

					//}else{

						//update date
							$stmt = $db->prepare("UPDATE `users` 
												  SET `UserName` =? ,`email` = ?,`fullName`= ?,`password`= ?	
												  WHERE `UserID` = ?");

							//execute query
							$stmt->execute(array($name,$email,$fullname,$pass,$id));

							//success message 
									$msg = "<div class='text-center alert alert-success' role='alert'>"       . $stmt->rowCount()."  Updated successfuly</div>";
									redirectHome($msg,"back",4);
					//}
					   

				}else{
						
						$msg = "<div class='text-center alert alert-success' role='alert'>Data Not Updated</div>";
						redirectHome($msg,"back",4);
				}

				
				 
			}else{
					
                        $msg="<div class='alert alert-info'>Sorry you can not browse this page directly</div>";
			      		redirectHome($msg);

			}
			echo "</div>";
		}








	}else{

		echo "<div class='alert alert-danger'>";
			echo "you can serve this page directly";
		echo "</div>";	

	echo "</div>";	
	}


	include $tpl . "footer.php"; 
	ob_end_flush();
?>