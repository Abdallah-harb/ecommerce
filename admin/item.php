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

		$pageTitle  = "Items";

		include "init.php";
		

		$do = isset($_GET['do'])?$_GET['do'] :'Manage';

		if($do == 'Manage'){

			//select  to show on table 
			$stmt = $db->prepare("SELECT 
										`items`.*,`categories`.`category_name`,
										`users`.`UserName` AS `member_name`
								 FROM `items`
								 INNER JOIN 
								 		`categories` ON `categories`.`cat_id` = `items`.`category_id`
								 INNER JOIN 
								 		`users` ON `users`.`UserID` = `items`.`member_id`");

			//execute statment
			$stmt->execute();
			//fetch data
			$items = $stmt->fetchAll();

			?>

			<div class='container'>
				<h1 class='text-center'> Manage Items</h1>
				<!-- start table of members -->
				<div class="table-responsive"> 
					<table class="main-table text-center table table-bordered">
					  
					    <tr>
					      <td scope="col">#ID</td>
					      <td scope="col">Name</td>
					      <td scope="col">Description</td>
					      <td scope="col">Price</td>
					      <td scope="col">Country made</td>
					      <td scope="col">Avatar</td>
					      <td scope="col">MemberName</td>
					      <td scope="col">Register date </td>
					      <td scope="col">Controler </td>


					    </tr>

					    <?php
					    	foreach($items as $item){
					    		echo '<tr>';
					    			echo "<td>".$item['item_id'].'</td>';
					    			echo "<td>".$item['Name'].'</td>';
					    			echo "<td style='width:300px'>".$item['item_desc'].'</td>';
					    			echo "<td>".$item['price'].'</td>';
					    			echo "<td>";
					    				echo '<img class="img-resposive"src="..\upload\avatar\\'.$item['image'].'" alt="items" style="height:50px" >';
					    			echo "</td>";
					    			echo "<td>".$item['category_name'].'</td>';
					    			echo "<td>".$item['member_name'].'</td>';
					    			echo "<td>".$item['item_date'].'</td>';
					    			echo "<td>";
					    				echo "<a href='?do=Edit&itemid=".$item['item_id']."' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>";
					    				echo "<a href='?do=Delete&itemid=".$item['item_id']."' class='btn btn-danger confirm'><i class='fa fa-trash'></i>Delete</a>";

					    				if($item['Approve'] == 0){

					    						echo "<a href='?do=Approve&itemid=".$item['item_id']."' class='btn btn-info active'><i class='fa fa-check-square-o'></i>Approve</a>";
					    					}

					    			echo "</td>";					    	
					    			echo '<tr>';
					    	}

					    ?>
					</table>
				</div>
				<!-- end table of members -->
				<a href='?do=Add' class='btn btn-primary'><i class="fa fa-plus">Add New Item</i></a>
			</div>
		<?php

		}elseif($do == 'Add'){?>
			<div class='container'>
				<h1 class='text-center'> Add New Item</h1>
				<form class="form-horizontal" action="?do=Insert" method="POST">
						<!--start item name field -->
						<div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Name</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" name="name" 
							       	 	placeholder="item Name" >
						    </div>
						</div>
						<!--end item name field -->
						<!--start Description field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Description</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" name="description" 
					       	 		 placeholder="Description of the item" >
						    </div>
						</div>
						<!--end Description field -->
						<!--start price field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Price</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" 
							       	 	name="price" 
							       	 	 placeholder="Price of the item [eg , dollar$,..]">
						    </div>
						</div>
						<!--end price field -->
						<!--start country made field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Country Made</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" 
							       	 	name="countryMade" 
							       	 	 placeholder="Like made on [egypt , china , spain ,..]">
						    </div>
						</div>
						<!--end country made field -->
						<!--start status field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Status</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<select class="form-control" name="status">
					       	 		<option value="0" >....</option>
					       	 		<option value="1">New</option>
					       	 		<option value="2">like New</option>
					       	 		<option value="3">Used</option>
					       	 		<option value="4">Old</option>
					       	 	</select>
						    </div>
						</div>
						<!--end status field -->
						<!--start members field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Members</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<select class="form-control" name="member">
					       	 		<option value="0" >....</option>
					       	 		<?php 
					       	 			$stmt = $db->prepare("SELECT * FROM `users`");
					       	 			$stmt->execute();
					       	 			$users = $stmt->fetchAll();
					       	 			foreach($users as $user){

					       	 				echo "<option value = '".$user['UserID']."'>";
					       	 						echo $user['UserName'];
					       	 				echo "</option>";		
					       	 			}

					       	 		?>
					       	 	</select>
						    </div>
						</div>
						<!--end members field -->
						<!--start category field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Category</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<select class="form-control" name="category">
					       	 		<option value="0" >....</option>
					       	 		<?php 
					       	 			$stmt = $db->prepare("SELECT * FROM `categories`");
					       	 			$stmt->execute();
					       	 			$users = $stmt->fetchAll();
					       	 			foreach($users as $user){

					       	 				echo "<option value = '".$user['cat_id']."'>";
					       	 						echo $user['category_name'];
					       	 				echo "</option>";		
					       	 			}

					       	 		?>
					       	 	</select>
						    </div>
						</div>
						<!--end category field -->
						<!--start submit button-->
						<div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
					       	 	<input type="submit" class="btn btn-primary btn-lg"
					       	 			 value="Add Category ">
						    </div>
						</div>
						<!--end submit button-->
				</form>
			</div>
			<?php
		}elseif($do == 'Insert'){
           
           echo '<div class="text-center"> ';
           echo "<h1 class='container'>Insert items</h1>";
			if($_SERVER['REQUEST_METHOD'] == 'POST'){

				$name        	= $_POST['name'];
				$desc    	    = $_POST['description'];
				$price        	= $_POST['price'];
				$country_made 	= $_POST['countryMade'];
				$status 	    = $_POST['status'];
				$member 	    = $_POST['member'];
				$cate 	    = $_POST['category'];
					//validate form
				$formerrors = array();
				if(empty($name)){
					$formerrors[]="Item Name cannot be empty";					
				}
				if(empty($desc)){
					$formerrors[]="Description cannot be <strong>empty</strong>";					
				}
				if(empty($price)){
					$formerrors[]="Price cannot be empty";					
				}
				if(empty($country_made)){
					$formerrors[]="Country cannot be <strong>empty</strong>";

				}if(empty($status)){
					$formerrors[]="Status cannot be <strong>empty</strong>";					
				}
				if(empty($member)){
					$formerrors[]="Member cannot be <strong>empty</strong>";					
				}
				if(empty($cate)){
					$formerrors[]="category cannot be <strong>empty</strong>";					
				}
					//loop errors and print it
				foreach ($formerrors as $errors) {
					echo "<div class='text-center alert alert-danger' role='alert'>";
  							echo $errors." <br>";
					echo "</div>";
				}
				//if there are no errors insert data
				   if(empty($formerrors)){

						//check if user exist on the database 
						$check = checkItem("UserName","users",$name);
						if($check == 1){

								$msg = '<div class="alert alert-danger">Sorry User Name is exist on database</div>';
								redirectHome($msg ,"back",4);

						}else{

							//Insert date
							$stmt = $db->prepare(' INSERT INTO `items`
															(`name`,`item_desc`,`price`
																,`country_made`,`status`,`item_date`,
																`member_id`,`category_id`)
												    VALUES (:zname, :zdesc, :zprice, :zmade, :zstat,now(), :zmember , :zcat) ');

							//execute query
							$stmt->execute(array(

												'zname'   => $name,
												'zdesc'   => $desc,
												'zprice'  => $price,
												'zmade'   => $country_made,
												'zstat'   => $status,
												'zmember' => $member,
												'zcat'    => $cate

							));
							//success message 
								$msg = "<div class='text-center alert alert-success' role='alert'>"       . $stmt->rowCount()." record Add successfuly</div>";
								redirectHome($msg,'back',4);        
						}
						
					}else{
						$msg = "<div class='text-center alert alert-danger' role='alert'> Data Not Updated</div>";
								redirectHome($msg,'back',400);  
					}

				
				 
			}else{
					$msg ="<div class='alert alert-info'>Sorry you can not browse this page directly</div>";
					
					redirectHome($msg);
				

			}
			echo '</div>';

		}elseif ($do == 'Edit') {

           // for itemid request that is number and feom database
			$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])? intval($_GET['itemid']):0;
			//select data from database
			$stmt   = $db->prepare("SELECT * FROM `items` WHERE `item_id`= ? ");
			//execute query
			$stmt->execute(array($itemid));
			//fetch data
			$row    = $stmt->fetch();
			//check if is larger than 0
			$count  = $stmt->rowCount();

			if($count >0){
			
			?>	
				<div class= 'container'>
					<h1 class="text-center"> Edit Comment</h1>
					<form class="form-horizontal" action="?do=Update" method="POST">
						<!--hidden id for item -->
						<input type="hidden" name="itemid" value="<?php echo $itemid?>">
						<!--start item name field -->
						<div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Name</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control"
					       	 	 name="name" 
					       	 	 value="<?php echo $row['Name']; ?>" >
						    </div>
						</div>
						<!--end item name field -->
						<!--start Description field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Description</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" name="description" 
					       	 		 value="<?php echo $row['item_desc']; ?>">
						    </div>
						</div>
						<!--end Description field -->
						<!--start price field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Price</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" 
							       	 	name="price" 
							       	 	value="<?php echo $row['price']; ?>" >
						    </div>
						</div>
						<!--end price field -->
						<!--start country made field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Country Made</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" 
							       	 	name="countryMade" 
							       	 	 value="<?php echo $row['country_made']; ?>">
						    </div>
						</div>
						<!--end country made field -->
						<!--start status field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Status</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<select class="form-control" name="status">
					       	 		<option value="1" <?php if($row['status'] == 1){echo 'selected';} ?>>New</option>
					       	 		<option value="2"<?php if($row['status'] == 2){echo 'selected';} ?>>like New</option>
					       	 		<option value="3"<?php if($row['status'] == 3){echo 'selected';} ?>>Used</option>
					       	 		<option value="4"<?php if($row['status'] == 4){echo 'selected';} ?>>Old</option>
					       	 	</select>
						    </div>
						</div>
						<!--end status field -->
						<!--start members field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Members</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<select class="form-control" name="member">	
					       	 		<?php 
					       	 			$stmt = $db->prepare("SELECT * FROM `users`");
					       	 			$stmt->execute();
					       	 			$users = $stmt->fetchAll();
					       	 			foreach($users as $user){

					       	 				echo "<option value = '".$user['UserID']."'";
					       	 					if($row['member_id']==$user['UserID']){
					       	 						echo 'selected';}
					       	 				echo ">";		
					       	 						echo $user['UserName'];
					       	 					

					       	 				echo "</option>";		
					       	 			}

					       	 		?>
					       	 	</select>
						    </div>
						</div>
						<!--end members field -->
						<!--start category field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Category</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<select class="form-control" name="category">
					       	 		<?php 
					       	 			$stmt = $db->prepare("SELECT * FROM `categories`");
					       	 			$stmt->execute();
					       	 			$cats = $stmt->fetchAll();
					       	 			foreach($cats as $cat){

					       	 				echo "<option value = '".$cat['cat_id']."'";
					       	 					if($row['category_id']==$cat['cat_id']){
					       	 						echo 'selected';
					       	 					}
					       	 				echo ">";	
					       	 						echo $cat['category_name'];
					       	 					

					       	 				echo "</option>";		
					       	 			}

					       	 		?>
					       	 	</select>
						    </div>
						</div>
						<!--end category field -->
						<!--start submit button-->
						<div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
					       	 	<input type="submit" class="btn btn-success btn-sm"
					       	 			 value="Save ">
						    </div>
						</div>
						<!--end submit button-->
					</form>
					<?php
					//select  to show on table 
			$stmt = $db->prepare("SELECT `comments`.*,`users`.`UserName`
									FROM `comments` 
									INNER JOIN `users` ON `users`.`UserID` = `comments`.`user_id`
									WHERE item_id = ?");

			//execute statment
			$stmt->execute(array($itemid));
			//fetch data
			$rows = $stmt->fetchAll();

			if(!empty($rows)){


			?>

			
				<h1 class='text-center'> Manage [<?php echo $row['Name']; ?>] Comments</h1>
				<!-- start table of members -->
				<div class="table-responsive"> 
					<table class="main-table text-center table table-bordered">
					  
					    <tr>
					      <td scope="col">Comment</td>
					      <td scope="col">By member </td>
					      <td scope="col">Date</td>
					      <td scope="col">Controler </td>


					    </tr>

					    <?php
					    	foreach($rows as $row){
					    		echo '<tr>';
					    			echo "<td>".$row['comment'].'</td>';
					    			echo "<td>".$row['UserName'].'</td>';
					    			echo "<td>".$row['date'].'</td>';
					    			echo "<td>";
					    				echo "<a href='comment.php?do=Edit&comid=".$row['comment_id']."' class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>";
					    				echo "<a href='comment.php?do=Delete&comid=".$row['comment_id']."' class='btn btn-danger confirm'><i class='fa fa-trash'></i>Delete</a>";

					    					if($row['status'] == 0){

					    						echo "<a href='comment.php?do=Activate&comid=".$row['comment_id']."' class='btn btn-info active'><i class='fa fa-check-square-o'></i>Approve</a>";
					    					}

					    			echo "</td>";					    	
					    			echo '<tr>';
					    	}

					    ?>
					</table>
				</div>
			<?php } ?>
			</div>	
			
		<?php

			}else{

					$msg = '<div class="alert alert-danger">that id not exist  </div>';
					redirectHome($msg,'back');
			}
			
		}elseif ($do == 'Update') {
			echo "<div class='container'>";
			echo "<h1 class='text-center'>Update Items </h1>";
			if($_SERVER['REQUEST_METHOD'] == 'POST'){

				$id             = $_POST['itemid'];
				$name        	= $_POST['name'];
				$desc    	    = $_POST['description'];
				$price        	= $_POST['price'];
				$country_made 	= $_POST['countryMade'];
				$status 	    = $_POST['status'];
				$member 	    = $_POST['member'];
				$cate 	        = $_POST['category'];
					//validate form
				$formerrors = array();
				if(empty($name)){
					$formerrors[]="Item Name cannot be empty";					
				}
				if(empty($desc)){
					$formerrors[]="Description cannot be <strong>empty</strong>";					
				}
				if(empty($price)){
					$formerrors[]="Price cannot be empty";					
				}
				if(empty($country_made)){
					$formerrors[]="Country cannot be <strong>empty</strong>";

				}if(empty($status)){
					$formerrors[]="Status cannot be <strong>empty</strong>";					
				}
				if(empty($member)){
					$formerrors[]="Member cannot be <strong>empty</strong>";					
				}
				if(empty($cate)){
					$formerrors[]="category cannot be <strong>empty</strong>";					
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
					$stmt = $db->prepare("UPDATE `items` 
										  SET `Name` =? ,`item_desc` = ?,`price`= ?,`country_made`= ?,
										  		`status` = ?,`member_id` = ?,`category_id` = ?

										  WHERE `item_id` = ?");

					//execute query
					$stmt->execute(array($name,$desc,$price,$country_made,$status,$member,$cate,$id));

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

			
		}elseif($do == 'Delete'){
			echo "<div class='container'>";
			echo "<h1 class='text-center'>Delete Item </h1>";
			// for itemid request that is item and from database
			$itemid = isset($_GET['itemid'])&& is_numeric($_GET['itemid'])? intval($_GET['itemid']):0;
			//select data from database
			$stmt   = $db->prepare("SELECT * FROM `items` WHERE `item_id`= ? ");
			//execute query
			$stmt->execute(array($itemid));
			//check if is larger than 0
			$count  = $stmt->rowCount();

			if($count >0){

				//delete data
				$stmt = $db->prepare('DELETE FROM `items` WHERE `item_id` = :zitemid ');
				$stmt->bindParam(":zitemid",$itemid);
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


		}elseif($do == 'Approve'){

			echo "<div class='container'>";
			echo "<h1 class='text-center'>Active Pending Item </h1>";
			// for itemid request that is item and from database
			$itemid = isset($_GET['itemid'])&& is_numeric($_GET['itemid'])? intval($_GET['itemid']):0;
			//select data from database
			$stmt   = $db->prepare("SELECT * FROM `items` WHERE `item_id`= ? ");
			//execute query
			$stmt->execute(array($itemid));
			//check if is larger than 0
			$count  = $stmt->rowCount();

			if($count >0){

				//delete data
				$stmt = $db->prepare(" UPDATE `items` SET `Approve` = 1 WHERE `item_id` =:zitemid");
				$stmt->bindParam(":zitemid",$itemid);
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


