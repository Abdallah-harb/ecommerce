<?php
	/*
		=====================================
		==Comments page you can do that
		== Add | Edit | insert | Update |DELETE
		=====================================
	*/
	ob_start();
	session_start();

	if(isset($_SESSION['Username'])){

		$pageTitle  = "Comments";
		include "init.php";
		

		$do = isset($_GET['do'])?$_GET['do'] :'Manage';

		if($do == 'Manage'){ //manage member page
			
			//select  to show on table 
			$stmt = $db->prepare("SELECT `comments`.*,`items`.`Name` AS `ItemName`,`users`.`UserName`
									FROM `comments` 
									INNER JOIN `items` ON `items`.`item_id` = `comments`.`item_id`
									INNER JOIN `users` ON `users`.`UserID` = `comments`.`user_id`");

			//execute statment
			$stmt->execute();
			//fetch data
			$rows = $stmt->fetchAll();

			?>

			<div class='container'>
				<h1 class='text-center'> Manage Comments</h1>
				<!-- start table of members -->
				<div class="table-responsive"> 
					<table class="main-table text-center table table-bordered">
					  
					    <tr>
					      <td scope="col">#ID</td>
					      <td scope="col">Comment</td>
					      <td scope="col">Item</td>
					      <td scope="col">By member </td>
					      <td scope="col">Date</td>
					      <td scope="col">Controler </td>


					    </tr>

					    <?php
					    	foreach($rows as $row){
					    		echo '<tr>';
					    			echo "<td>".$row['comment_id'].'</td>';
					    			echo "<td width='350px'>".$row['comment'].'</td>';
					    			echo "<td>".$row['ItemName'].'</td>';
					    			echo "<td>".$row['UserName'].'</td>';
					    			echo "<td>".$row['date'].'</td>';
					    			echo "<td>";
					    				echo "<a href='?do=Edit&comid=".$row['comment_id']."' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>";
					    				echo "<a href='?do=Delete&comid=".$row['comment_id']."' class='btn btn-danger confirm'><i class='fa fa-trash'></i>Delete</a>";

					    					if($row['status'] == 0){

					    						echo "<a href='?do=Activate&comid=".$row['comment_id']."' class='btn btn-info active'><i class='fa fa-check-square-o'></i>Approve</a>";
					    					}

					    			echo "</td>";					    	
					    			echo '<tr>';
					    	}

					    ?>
					</table>
				</div>
			</div>
		<?php

		}elseif($do == 'Edit'){//edit page 
			// for commid request that is number and feom database
			$comid = isset($_GET['comid']) && is_numeric($_GET['comid'])? intval($_GET['comid']):0;
			//select data from database
			$stmt   = $db->prepare("SELECT * FROM `comments` WHERE `comment_id`= ? ");
			//execute query
			$stmt->execute(array($comid));
			//fetch data
			$row    = $stmt->fetch();
			//check if is larger than 0
			$count  = $stmt->rowCount();

			if($count >0){
			
			?>
				
				<div class="container">
					<h1 class="text-center"> Edit Comment</h1>
					<form class="form-horizontal" action="?do=Update" method="POST">
						<!--hidden id for comment -->
						<input type="hidden" name="yourid" value="<?php echo $comid?>">
						<!--start comment field -->
						<div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Comment</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<textarea class="form-control" name="comment"><?php echo $row['comment'];?>
					       	 	</textarea>
						    </div>
						</div>
						<!--end comment field -->
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
			echo "<h1 class='text-center'>Update comment </h1>";
			if($_SERVER['REQUEST_METHOD'] == 'POST'){

				$id       = $_POST['yourid'];
				$comment     = $_POST['comment'];
					//validate form
				$formerrors = array();
				if(empty($comment)){
					$formerrors[]="comment cannot be empty";					
				}
				if(strlen($comment) >900){
					$formerrors[]="user Name mustNot be larger than<span class='text-bold'> 900 character</span>";					
				}
					//loop errors and print it
				foreach ($formerrors as $errors) {
					echo "<div class='text-center alert alert-danger' role='alert'>";
  							echo $errors." <br>";
					echo "</div>";
				}
				//if there are no errors update data
				if(empty($formerrors)){
					//update date
					$stmt = $db->prepare("UPDATE `comments` 
										  SET `comment` =? 	
										  WHERE `comment_id` = ?");

					//execute query
					$stmt->execute(array($comment,$id));

					//success message 
							$msg = "<div class='text-center alert alert-success' role='alert'>"       . $stmt->rowCount()."  Updated successfuly</div>";
							redirectHome($msg,"back",4);   

				}else{
						
						$msg = "<div class='text-center alert alert-success' role='alert'>Data Not Updated</div>";
						redirectHome($msg,"back",4);
				}

				
				 
			}else{
					
                        $msg="<div class='alert alert-info'>Sorry you can not browse this page directly</div>";
			      		redirectHome($msg);

			}
			echo "</div>";
		}elseif($do == 'Delete'){//delete comment page

			echo "<div class='container'>";
			echo "<h1 class='text-center'>Delete Comment </h1>";
			// for commid request that is number and feom database
			$comid = isset($_GET['comid'])&& is_numeric($_GET['comid'])? intval($_GET['comid']):0;
			//select data from database
			$stmt   = $db->prepare("SELECT * FROM `comments` WHERE `comment_id`= ? ");
			//execute query
			$stmt->execute(array($comid));
			//check if is larger than 0
			$count  = $stmt->rowCount();

			if($count >0){

				//delete data
				$stmt = $db->prepare('DELETE FROM `comments` WHERE `comment_id` = :zid ');
				$stmt->bindParam(":zid",$comid);
				$stmt->execute();
					$msg = "<div class='text-center alert alert-success' role='alert'>"			       . $stmt->rowCount()." Data  Deleted successfuly</div>";
					redirectHome($msg,"back",3); 

			}else{

				$msg ="<div class='text-center alert alert-danger'>This id no exist</div>";
  				redirectHome($msg,'',4);
			}	
			echo "</div>";
		}elseif($do == 'Activate'){//page of pending comments
			echo "<div class='container'>";
			echo "<h1 class='text-center'>Active Pending comments </h1>";
			// for commid request that is number and feom database
			$comid = isset($_GET['comid'])&& is_numeric($_GET['comid'])? intval($_GET['comid']):0;
			//select data from database
			$stmt   = $db->prepare("SELECT * FROM `comments` WHERE `comment_id`= ? ");
			//execute query
			$stmt->execute(array($comid));
			//check if is larger than 0
			$count  = $stmt->rowCount();

			if($count >0){

				//delete data
				$stmt = $db->prepare(" UPDATE `comments` SET `status` = 1 WHERE `comment_id` =:zcomid");
				$stmt->bindParam(":zcomid",$comid);
				$stmt->execute();
					$msg = "<div class='text-center alert alert-success' role='alert'>Activate "			       . $stmt->rowCount()." comment  successfuly</div>";
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