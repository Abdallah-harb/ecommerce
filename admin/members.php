<?php
	/*
		=====================================
		==members page you can do that
		== Add | Edit | insert | Update |DELETE
		=====================================
	*/
	ob_start();
	session_start();

	if(isset($_SESSION['Username'])){

		$pageTitle  = "Members";
		include "init.php";
		

		$do = isset($_GET['do'])?$_GET['do'] :'Manage';

		if($do == 'Manage'){ //manage member page
			/*
			** variable to use on statment to show pending members only 
			** if user come from members only show all members  and no execute $query
			** if come from user pending show pending members only and exexute $query
			*/
			$query ='';
			if(isset($_GET['page']) && $_GET['page'] == 'Pending'){
				$query = 'AND Regstatus = 0';
			}
			//select  to show on table 
			$stmt = $db->prepare("SELECT * FROM `users` WHERE `GroupId` != 1 $query");

			//execute statment
			$stmt->execute();
			//fetch data
			$rows = $stmt->fetchAll();

			?>

			<div class='container'>
				<h1 class='text-center'> Manage Members</h1>
				<!-- start table of members -->
				<div class="table-responsive"> 
					<table class="main-table text-center table table-bordered">
					  
					    <tr>
					      <td scope="col">#ID</td>
					      <td scope="col">Avatar</td>
					      <td scope="col">Name</td>
					      <td scope="col">Email</td>
					      <td scope="col">Full Name</td>
					      <td scope="col">Register date </td>
					      <td scope="col">Controler </td>


					    </tr>

					    <?php
					    	foreach($rows as $row){
					    		echo '<tr>';
					    			echo "<td>".$row['UserID'].'</td>';
					    			if(!empty($row['avatar'])){

					    				echo "<td>";
					    					echo "<img style='width:50px'class='img-circle' src='upload/avatar/".$row['avatar']."'>";
					    				echo "</td>";
					    			}else{
					    				echo "<td>";
					    					echo "<img class='img-circle' style='width:50px'src='upload/avatar/avatar.png'>";
					    				echo "</td>";

					    			}
					    				
					    			echo "<td>".$row['UserName'].'</td>';
					    			echo "<td>".$row['email'].'</td>';
					    			echo "<td>".$row['fullName'].'</td>';
					    			echo "<td>".$row['Date'].'</td>';
					    			echo "<td>";
					    				echo "<a href='?do=Edit&userId=".$row['UserID']."' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>";
					    				echo "<a href='?do=Delete&userId=".$row['UserID']."' class='btn btn-danger confirm'><i class='fa fa-trash'></i>Delete</a>";

					    					if($row['Regstatus'] == 0){

					    						echo "<a href='?do=Activate&userId=".$row['UserID']."' class='btn btn-info active'><i class='fa fa-check-square-o'></i>Activate</a>";
					    					}

					    			echo "</td>";					    	
					    			echo '<tr>';
					    	}

					    ?>
					</table>
				</div>
				<!-- end table of members -->
				<a href='?do=Add' class='btn btn-primary'><i class="fa fa-plus">Add New Member</i></a>
			</div>
		<?php
		}elseif($do == 'Add'){//Add page?>
			<div class='container'>
				<h1 class='text-center'> Add New Members</h1>
				<form class="form-horizontal" action="?do=Insert" method="POST" 
					           enctype="multipart/form-data">
						<!--start username field -->
						<div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">UserName</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" name="username" 
							       	 	autocomplete="off"
							       	 	placeholder="Write Your Name" required='required'>
						    </div>
						</div>
						<!--end username field -->
						<!--start email field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Email</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="email" class="form-control" name="email" 
					       	 		 placeholder="Write Valid Email" required='required'>
						    </div>
						</div>
						<!--end email field -->
						<!--start password field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Password</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="password" class="form-control" 
							       	 	name="password" autocomplete="new-password"
							       	 	 placeholder="please write your password"required='required'>
						    </div>
						</div>
						<!--end password field -->
						<!--start fullname field -->
						<div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">FullName</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" name="fullname"
					       	 	          placeholder="write your full name"required='required'>
						    </div>
						</div>
						<!--end fullname field -->
						<!--start Avatar field -->
						<div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Avatar</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="file" class="form-control" name="avatar"
					       	 			required='required'>
						    </div>
						</div>
						<!--end avatar field -->
						<!--start submit button-->
						<div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
					       	 	<input type="submit" class="btn btn-primary btn-lg" value="Add">
						    </div>
						</div>
						<!--end submit button-->
				</form>
			</div>
		<?php
		}elseif($do == 'Insert'){//insert page
			echo "<div class='container'>";
				if($_SERVER['REQUEST_METHOD'] == 'POST'){

				$name     	= $_POST['username'];
				$email    	= $_POST['email'];
				$pass     	= $_POST['password'];
				$fullname 	= $_POST['fullname'];
				$hashpass   = sha1($pass);

				//avatar
				$avatar      = $_FILES['avatar'];

				$avatarName  = $_FILES['avatar']['name'];
				$avatarSize  = $_FILES['avatar']['size'];
				$avatarTmp   = $_FILES['avatar']['tmp_name'];
				$avatarType  = $_FILES['avatar']['type'];

				//avatar extention allow to upload
				$avatarallowExtention = array("jpeg", "jpg" , "png" , "gif");
				//shift the avatar after upload 
				$avatarExtenstion = explode('.',$avatarName);
				$avatarExtenstionend = strtolower(end($avatarExtenstion));



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
				if(empty($pass)){
					$formerrors[]="Password cannot be empty";					
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
				if(!empty($avatarExtenstionend) && ! in_array($avatarExtenstionend, $avatarallowExtention)){

						$formerrors[] = "This Extenstion  is Not <strong>Allowed</strong>";

				}
				if(empty($avatarExtenstionend)){

					$formerrors[] = 'avatar Must be <strong>Uploaded </strong>';
				}
				if($avatarSize > 4194304){
					$formerrors[] = "sorry avatar cannot be larger than 4mB";
				}

					//loop errors and print it
				foreach ($formerrors as $errors) {
					echo "<div class='text-center alert alert-danger' role='alert'>";
  							echo $errors." <br>";
					echo "</div>";
				}

				
				//if there are no errors insert data
				if(empty($formerrors)){

					//get random name for avatar 
					$avatar = rand(0,10000)."_".$avatarName;

					//UPLOAD AVATAR

					move_uploaded_file($avatarTmp, "upload\avatar\\".$avatar);

					
					//check if user exist on the database 
					$check = checkItem("UserName","users",$name);
					if($check == 1){

							$msg = '<div class="alert alert-danger">Sorry User Name is exist on database</div>';
							redirectHome($msg ,"back",4);

					}else{

						//Insert date
						$stmt = $db->prepare("  INSERT INTO `users`
														(`UserName`,`email`,`password`,
															`fullName`,`Regstatus`,
															`Date`,`avatar`)
											    VALUES (:zuser, :zemail, :zpass,
											    		 :zfname, 1,now(), :zavatar)");

						//execute query
						$stmt->execute(array(

											'zuser'  => $name,
											'zemail' => $email,
											'zpass'  => $hashpass,
											'zfname' => $fullname,
											'zavatar' => $avatar

						));
						//success message 
							$msg = "<div class='text-center alert alert-success' role='alert'>"       . $stmt->rowCount()." record Add successfuly</div>";
							redirectHome($msg,'back',4);        
					}
					
				}else{
					$msg = "<div class='text-center alert alert-danger' role='alert'> Data Not Updated</div>";
							redirectHome($msg,'back'); 
							
				}
			
				
				 
			}else{
					$msg ="<div class='alert alert-info'>Sorry you can not browse this page directly</div>";
					
					redirectHome($msg,"",4);
				

			}
			echo '</div>';

		}elseif($do == 'Edit'){//edit page 
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
				<h1 class="text-center"> Edit Member</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
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
			echo "<h1 class='text-center'>Update Members </h1>";
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
					$check = checkItem("UserName","users",$name);
					if($check == 1){

							$msg = '<div class="alert alert-danger">Sorry User Name is exist on database</div>';
							redirectHome($msg ,"back",4);

					}else{

						//update date
							$stmt = $db->prepare("UPDATE `users` 
												  SET `UserName` =? ,`email` = ?,`fullName`= ?,`password`= ?	
												  WHERE `UserID` = ?");

							//execute query
							$stmt->execute(array($name,$email,$fullname,$pass,$id));

							//success message 
									$msg = "<div class='text-center alert alert-success' role='alert'>"       . $stmt->rowCount()."  Updated successfuly</div>";
									redirectHome($msg,"back",4);
					}
					   

				}else{
						
						$msg = "<div class='text-center alert alert-success' role='alert'>Data Not Updated</div>";
						redirectHome($msg,"back",4);
				}

				
				 
			}else{
					
                        $msg="<div class='alert alert-info'>Sorry you can not browse this page directly</div>";
			      		redirectHome($msg);

			}
			echo "</div>";
		}elseif($do == 'Delete'){//delete memper page

			echo "<div class='container'>";
			echo "<h1 class='text-center'>Delete Members </h1>";
			// for userid request that is number and feom database
			$userid = isset($_GET['userId'])&& is_numeric($_GET['userId'])? intval($_GET['userId']):0;
			//select data from database
			$stmt   = $db->prepare("SELECT * FROM `users` WHERE `UserID`= ? ");
			//execute query
			$stmt->execute(array($userid));
			//check if is larger than 0
			$count  = $stmt->rowCount();

			if($count >0){

				//delete data
				$stmt = $db->prepare('DELETE FROM `users` WHERE `UserID` = :zuserid ');
				$stmt->bindParam(":zuserid",$userid);
				$stmt->execute();
					$msg = "<div class='text-center alert alert-success' role='alert'>"			       . $stmt->rowCount()." Data  Deleted successfuly</div>";
					redirectHome($msg,"back",3); 

			}else{

				$msg ="<div class='text-center alert alert-danger'>This id no exist</div>";
  				redirectHome($msg,'',4);
			}	
			echo "</div>";
		}elseif($do == 'Activate'){//page of pending members
			echo "<div class='container'>";
			echo "<h1 class='text-center'>Active Pending Members </h1>";
			// for userid request that is number and feom database
			$userid = isset($_GET['userId'])&& is_numeric($_GET['userId'])? intval($_GET['userId']):0;
			//select data from database
			$stmt   = $db->prepare("SELECT * FROM `users` WHERE `UserID`= ? ");
			//execute query
			$stmt->execute(array($userid));
			//check if is larger than 0
			$count  = $stmt->rowCount();

			if($count >0){

				//delete data
				$stmt = $db->prepare(" UPDATE `users` SET `Regstatus` = 1 WHERE `UserID` =:zuserid");
				$stmt->bindParam(":zuserid",$userid);
				$stmt->execute();
					$msg = "<div class='text-center alert alert-success' role='alert'>Activate "			       . $stmt->rowCount()." User  successfuly</div>";
					redirectHome($msg,"back",3); 

			}else{

				$msg ="<div class='text-center alert alert-danger'>This id no exist</div>";
  				redirectHome($msg,'',4);
			}	
			echo "</div>";

		}

		include  $tpl  . "footer.php";

	}else{

		header('Location:index.php');
		exit();
	}

ob_end_flush();
?>