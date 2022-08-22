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

		$pageTitle  = " Categories";

		include "init.php";
		

		$do = isset($_GET['do'])?$_GET['do'] :'Manage';

		if($do == 'Manage'){

			echo "<div class='container'>";
			echo "<h1 class='text-center'> Manage Categories</h1>";

			// varible to sort the category 
			$sort = 'ASC';
			$sort_array = array('ASC','DESC');
			//condition to arrange as admin need asc | desc
			if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)){

				$sort = $_GET['sort'];
			}
			//statment to select all row on category table
			$stmt = $db->prepare(" SELECT * FROM `categories` ORDER BY `Odering` $sort ");
			$stmt->execute();
			$cats = $stmt->fetchAll();
			if ($cats > 0){

			?>
			<!-- start panel to show info of category-->
			<div class="panel panel-primary category">
				<div class="panel-heading">
					Categories
					<a href='?do=Add' class='btn add-cat'>Add Category </a>
					<div class="ordering pull-right">
						Ordering : 
						<a href="?sort=ASC" class="<?php if($sort == 'ASC'){echo 'active';} ?>"> ASC  </a> |
						<a href="?sort=DESC"class="<?php if($sort == 'DESC'){echo 'active';} ?>"> DESC </a>
					</div>
				</div>
				<div class="panel-body">
					<?php
						foreach($cats as $cat){
							echo "<div class='cat'>";
								echo "<div class='hidden-button'>";
									echo "<a href = '?do=Edit&catid=".$cat['cat_id']."'class ='btn btn-success'><i class='fa fa-edit'></i> Edit</a>";
									echo "<a href = '?do=Delete&catid=".$cat['cat_id']."'class ='btn btn-danger'><i class='fa fa-close'></i> Delete</a>";
								echo '</div>';
								echo "<h3>".$cat['category_name']. " </h3>";
								echo "<div class='content'>";
									echo "<p><span> Description </span>: ";
											if($cat['description'] == ''){
												echo "This Category has no comment";
											}else{
												echo $cat['description'];
											}
									echo "</p>";	
									echo "<div class='rotiuch'> ";
										if($cat['Visibility'] == 1){
											echo "<span class = 'visible'><i class='fa fa-eye'></i>Hidden</span>"; 
											}
										if($cat['Allow_comment'] == 1){
											echo "<span class = 'commenting'><i class='fa fa-close'></i>Comment Disabled</span>"; 
											}
										if($cat['Allow_ads'] == 1){
											echo "<span class = 'advertise'><i class='fa fa-close'></i>Ads Disabled</span>"; 
											}
									echo '</div>';		
								echo "</div>";
								
								echo "<hr>";
							echo "</div>";		
						}
					?>
				</div>
				
			</div>
			<!-- End panel to show info of category-->
			<?php
			}else{

				echo "<div class='alert alert-info'>Sorry There Are no Category Add Category</div>";

				
			}	
			
			echo "<div>";
			

		}elseif($do == 'Add'){ ?>

			<div class='container'>
				<h1 class='text-center'> Add New Categories</h1>
				<form class="form-horizontal" action="?do=Insert" method="POST">
						<!--start category name field -->
						<div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Name</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" name="name" 
							       	 	autocomplete="off"
							       	 	placeholder="Category Name" required='required'>
						    </div>
						</div>
						<!--end category name field -->
						<!--start Description field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Description</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" name="description" 
					       	 		 placeholder="Description of Category" >
						    </div>
						</div>
						<!--end Description field -->
						<!--start parent field -->
						<div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Parent ?</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<select class="form-control" name="parent">
					       	 		<option value="0" >....</option>
					       	 		<?php 
					       	 			$stmt = $db->prepare("SELECT * FROM `categories`
					       	 					 WHERE `parent` = 0 ORDER BY `cat_id` DESC");
					       	 			$stmt->execute();
					       	 			$getParents = $stmt->fetchAll();
					       	 			foreach($getParents as $getparent){
					       	 				echo '<option value="'.$getparent['cat_id'].'">';
					       	 					echo $getparent['category_name'];
					       	 				echo '</div>';	
					       	 			}
					       	 		?>
					       	 	</select>
						    </div>
						</div>
						<!--end parent field -->
						<!--start Ordering field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Ordering</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="number" class="form-control" 
							       	 	name="order"
							       	 	 placeholder="number to arrange category">
						    </div>
						</div>
						<!--end Ordering field -->
						<!--start Visibility field -->
						<div class="form-group  form-group-lg">
							<label  class="col-sm-2 control-label">Visible</label>
						    <div class="col-sm-10 col-md-6">
						    	<div class="form-check">
						    		<input class="form-check-input" type="radio" name="visible"
						    			 id="visible-yes" value="0" checked>
	  								<label class="form-check-label" for="visible-yes">
								   		 Yes
								    </label>
						    	</div>
						    	<div class="form-check">
						    		<input class="form-check-input" type="radio" name="visible"
						    			 id="visible-no" value="1">
	  								<label class="form-check-label" for="visible-no">
								   		 No
								    </label>
						    	</div>
						    </div>
						</div>
						<!--end Visibility field -->
						<!--start Comments field -->

						<div class="form-group  form-group-lg">
							<label  class="col-sm-2 control-label">Allow Comments</label>
						    <div class="col-sm-10 col-md-6">
						    	<div class="form-check">
						    		<input class="form-check-input" type="radio" name="comment"
						    			 id="comment-yes" value="0" checked>
	  								<label class="form-check-label" for="comment-yes">
								   		 Yes
								    </label>
						    	</div>
						    	<div class="form-check">
						    		<input class="form-check-input" type="radio" name="comment"
						    			 id="comment-no" value="1">
	  								<label class="form-check-label" for="comment-no">
								   		 No
								    </label>
						    	</div>
						    </div>
						</div>
						<!--end Comments field -->
						<!--start Ads field -->
						<div class="form-group  form-group-lg">
							<label  class="col-sm-2 control-label">Allow Ads</label>
						    <div class="col-sm-10 col-md-6">
						    	<div class="form-check">
						    		<input class="form-check-input" type="radio" name="ads"
						    			 id="ads-yes" value="0" checked>
	  								<label class="form-check-label" for="ads-yes">
								   		 Yes
								    </label>
						    	</div>
						    	<div class="form-check">
						    		<input class="form-check-input" type="radio" name="ads"
						    			 id="ads-no" value="1">
	  								<label class="form-check-label" for="ads-no">
								   		 No
								    </label>
						    	</div>
						    </div>
						</div>
						<!--end Comments field -->
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
				echo "<div class='container'>";
				if($_SERVER['REQUEST_METHOD'] == 'POST'){

				$catname     	= $_POST['name'];
				$description    = $_POST['description'];
				$ordering     	= $_POST['order'];
				$parent     	= $_POST['parent'];
				$visibility 	= $_POST['visible'];
				$comments       = $_POST['comment'];
				$Ads            = $_POST['ads'];
					//validate form
				$formerrors = array();
				if(empty($catname)){
					$formerrors[]="Ctaegory name cannot be empty";					
				}
				if(strlen($catname) <3){
					$formerrors[]="Ctaegory name must be larger than<span class='text-bold'> 3 character</span>";					
				}
				if(strlen($catname) >19){
					$formerrors[]="Ctaegory name mustNot be larger than<span class='text-bold'> 19 character</span>";					
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
					$check = checkItem("category_name","categories",$catname);
					if($check == 1){

							$msg = '<div class="alert alert-danger">Sorry category Name is exist on database</div>';
							redirectHome($msg ,"back",4);

					}else{

						//Insert date
						$stmt = $db->prepare("  INSERT INTO `categories`
														(`category_name`,`description`,`parent`,`Odering`,
															`Visibility`,`Allow_comment`,`Allow_ads`)
							    VALUES (:zname, :zdesc, :zparent, :zorder, :zvisible, :zcomment,:zads)");
						//execute query
						$stmt->execute(array(

											'zname'    => $catname,
											'zdesc'    => $description,
											'zparent'  => $parent,
											'zorder'   => $ordering,
											'zvisible' => $visibility,
											'zcomment' => $comments,
											'zads'     => $Ads

						));
						//success message 
							$msg = "<div class='text-center alert alert-success' role='alert'>"       . $stmt->rowCount()." record Insert successfuly</div>";
							redirectHome($msg,'back',2);        
					}
					
				}else{
					$msg = "<div class='text-center alert alert-danger' role='alert'>"			       . $stmt->rowCount()." Data Not Updated</div>";
							redirectHome($msg,'back');  
				}

				
				 
			}else{
					$msg ="<div class='alert alert-info'>Sorry you can not browse this page directly</div>";
					
					redirectHome($msg,"",4);
				

			}
			echo '</div>';
		}elseif ($do == 'Edit') {

			// for userid request that is number and feom database
			$catId  = isset($_GET['catid']) && is_numeric($_GET['catid'])? intval($_GET['catid']):0;
			//select data from database
			$stmt   = $db->prepare("SELECT * FROM `categories` WHERE `cat_id`= ? ");
			//execute query
			$stmt->execute(array($catId));
			//fetch data
			$cat    = $stmt->fetch();
			//check if is larger than 0
			$count  = $stmt->rowCount();

			if($count >0){
			
			?>
				<div class='container'>
					<h1 class='text-center'> Edit Categories</h1>
					<form class="form-horizontal" action="?do=Update" method="POST">
						<!-- hidden input for id to use on update data -->
						<input type="hidden" name="catid" value="<?php echo $catId;?>"> 
						<!--start category name field -->
						<div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Name</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" name="name" 
							       	 	autocomplete="off"
							       	 	value="<?php echo $cat['category_name'] ?>"
							       	 	 >
						    </div>
						</div>
						<!--end category name field -->
						<!--start Description field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Description</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="text" class="form-control" name="description" 
					       	 		 value="<?php echo $cat['description'] ?>" >
						    </div>
						</div>
						<!--end Description field -->
						<!--start Ordering field -->
					    <div class="form-group form-group-lg">
							<label  class="col-sm-2 control-label">Ordering</label>
						    <div class="col-sm-10 col-md-6">
					       	 	<input type="number" class="form-control" 
							       	 	name="order"
							       	 	 value="<?php echo $cat['Odering'] ?>">
						    </div>
						</div>
						<!--end Ordering field -->
						<!--start Visibility field -->
						<div class="form-group  form-group-lg">
							<label  class="col-sm-2 control-label">Visible</label>
						    <div class="col-sm-10 col-md-6">
						    	<div class="form-check">
						    		<input class="form-check-input" type="radio" name="visible"
						    			 id="visible-yes" value="0" 
						    			 <?php if($cat['Visibility']== 0){ echo 'checked';} ?>>
	  								<label class="form-check-label" for="visible-yes">
								   		 Yes
								    </label>
						    	</div>
						    	<div class="form-check">
						    		<input class="form-check-input" type="radio" name="visible"
						    			 id="visible-no" value="1"
						    			 <?php if($cat['Visibility']== 1){ echo 'checked';} ?>>
	  								<label class="form-check-label" for="visible-no">
								   		 No
								    </label>
						    	</div>
						    </div>
						</div>
						<!--end Visibility field -->
						<!--start Comments field -->

						<div class="form-group  form-group-lg">
							<label  class="col-sm-2 control-label">Allow Comments</label>
						    <div class="col-sm-10 col-md-6">
						    	<div class="form-check">
						    		<input class="form-check-input" type="radio" name="comment"
						    			 id="comment-yes" value="0" 
						    			 <?php if($cat['Allow_comment']== 0){ echo 'checked';} ?>>
	  								<label class="form-check-label" for="comment-yes">
								   		 Yes
								    </label>
						    	</div>
						    	<div class="form-check">
						    		<input class="form-check-input" type="radio" name="comment"
						    			 id="comment-no" value="1"
						    			 <?php if($cat['Allow_comment']== 1){ echo 'checked';} ?>>
	  								<label class="form-check-label" for="comment-no">
								   		 No
								    </label>
						    	</div>
						    </div>
						</div>
						<!--end Comments field -->
						<!--start Ads field -->
						<div class="form-group  form-group-lg">
							<label  class="col-sm-2 control-label">Allow Ads</label>
						    <div class="col-sm-10 col-md-6">
						    	<div class="form-check">
						    		<input class="form-check-input" type="radio" name="ads"
						    			 id="ads-yes" value="0" 
						    			 <?php if($cat['Allow_ads']== 0){ echo 'checked';} ?>>
	  								<label class="form-check-label" for="ads-yes">
								   		 Yes
								    </label>
						    	</div>
						    	<div class="form-check">
						    		<input class="form-check-input" type="radio" name="ads"
						    			 id="ads-no" value="1"
						    			 <?php if($cat['Allow_ads']== 1){ echo 'checked';} ?>>
	  								<label class="form-check-label" for="ads-no">
								   		 No
								    </label>
						    	</div>
						    </div>
						</div>
						<!--end Comments field -->
						<!--start submit button-->
						<div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
					       	 	<input type="submit" class="btn btn-primary btn-lg"
					       	 			 value=" Save ">
						    </div>
						</div>
						<!--end submit button-->
					</form>
				</div>
				
		<?php

			}else{

					$msg = '<div class="alert alert-danger">This id is not exist </div>';
					redirectHome($msg,'back');
			}
			
		}elseif ($do == 'Update') {

			echo "<div class='container'>";
				echo "<h1 class='text-center'>Update Cataegory </h1>";
				if($_SERVER['REQUEST_METHOD'] == 'POST'){

					$id           = $_POST['catid'];
					$catName      = $_POST['name'];
					$catDesc      = $_POST['description'];
					$ordering     = $_POST['order'];
					$visiblity    = $_POST['visible'];
					$commenting   = $_POST['comment'];
					$Ads          = $_POST['ads'];
						
						//validate form
					$formerrors = array();
					if(empty($catName)){
						$formerrors[]="Cataegory Name cannot be empty";					
					}
					if(strlen($catName) <3){
						$formerrors[]="Cataegory Name must be larger than<span class='text-bold'> 3 character</span>";					
					}
					if(strlen($catName) >20){
						$formerrors[]="Cataegory Name mustNot be larger than<span class='text-bold'> 20 character</span>";					
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
						$stmt = $db->prepare("UPDATE `categories` 
											  SET `category_name` =? ,`description` = ?,`Odering`= ?,
											  `Visibility`= ?, `Allow_comment` = ? , `Allow_ads` = ?	
											  WHERE `cat_id` = ?");

						//execute query
						$stmt->execute(
							array($catName,$catDesc,$ordering,$visiblity,$commenting,$Ads,$id)
									);

						//success message 
								$msg = "<div class='text-center alert alert-success' role='alert'>"       . $stmt->rowCount()."  Updated successfuly</div>";
								redirectHome($msg,"back",4);   

					}else{
							
							$msg = "<div class='text-center alert alert-success' role='alert'> Data Not Updated</div>";
							redirectHome($msg,"back",4);
					}

					
					 
				}else{
						
	                        $msg="<div class='alert alert-info'>Sorry you can not browse this page directly</div>";
				      		redirectHome($msg);

				}
			echo "</div>";
			
		}elseif($do == 'Delete'){
			echo "<div class='container'>";
				echo "<h1 class='text-center'>Delete Category </h1>";
				// for userid request that is number and feom database
				$catId  = isset($_GET['catid']) && is_numeric($_GET['catid'])? intval($_GET['catid']):0;
				//select data from database
				$stmt   = $db->prepare("SELECT * FROM `categories` WHERE `cat_id`= ? ");
				//execute query
				$stmt->execute(array($catId));
				//check if is larger than 0
				$count  = $stmt->rowCount();

				if($count >0){

					//delete data
					$stmt = $db->prepare('DELETE FROM `categories` WHERE `cat_id` = :zcatid ');
					$stmt->bindParam(":zcatid",$catId);
					$stmt->execute();
						$msg = "<div class='text-center alert alert-success' role='alert'>"			       . $stmt->rowCount()." Data  Deleted successfuly</div>";
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


