<?php 
	
	session_start();
	$pageTitle = "New Ads";
	include "init.php";

	if(isset($_SESSION['User'])){

		if($_SERVER['REQUEST_METHOD'] == 'POST'){

		$formerrors     = array();
		$name        	= filter_var($_POST['name'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$desc    	    = filter_var($_POST['description'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$price        	= filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
		$country_made 	= filter_var($_POST['countryMade'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$status 	    = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
		$cate 	        = filter_var($_POST['category'],FILTER_SANITIZE_NUMBER_INT);
		//avatar
				$avatar      = $_FILES['image'];

				$avatarName  = $_FILES['image']['name'];
				$avatarSize  = $_FILES['image']['size'];
				$avatarTmp   = $_FILES['image']['tmp_name'];
				$avatarType  = $_FILES['image']['type'];

				//avatar extention allow to upload
				$avatarallowExtention = array("jpeg", "jpg" ,"jfif","webp","png" , "gif");
				//shift the avatar after upload 
				$avatarExtenstion = explode('.',$avatarName);
				$avatarExtenstionend = strtolower(end($avatarExtenstion));


			if(strlen($name) <3){
				$formerrors[] = 'Item Name muste be larger than 2 chars';
			}
			if(strlen($desc) <10){
				$formerrors[] = 'Item description muste be larger than 10 chars';
			}
			if(strlen($country_made) <2){
				$formerrors[] = 'Country made muste be larger than 2 chars';
			}
			if(empty($price)){
				$formerrors[] = 'price can not be empty';
			}
			if(empty($status)){
				$formerrors[] = 'status can not be empty';
			}
			if(empty($cate)){
				$formerrors[] = 'category can not be empty';

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

			//if there are no errors insert data
		   if(empty($formerrors)){

		   		//get random name for avatar 
					$avatar = rand(0,10000)."_".$avatarName;

					//UPLOAD AVATAR

					move_uploaded_file($avatarTmp, "upload\avatar\\".$avatar);

				//Insert date
				$stmt = $db->prepare(' INSERT INTO `items`
												(`name`,`item_desc`,`price`
													,`country_made`,`status`,`item_date`,
													`member_id`,`category_id`,`image`)
									    VALUES (:zname, :zdesc, :zprice, :zmade, :zstat,now(), :zmember, :zcat, :zavatar ) ');

				//execute query
				$stmt->execute(array(

									'zname'   => $name,
									'zdesc'   => $desc,
									'zprice'  => $price,
									'zmade'   => $country_made,
									'zstat'   => $status,
									'zmember' => $_SESSION['uid'],
									'zcat'    => $cate,
									'zavatar' => $avatar

				));

				if($stmt){
					echo '<div class="container">';
						echo "<div class='alert alert-success'> New Ads Add successfuly</div>";
						redirectHome('','back',3);
					echo "</div>";
				}
			}

		}

?>
<!-- start new ads -->
	<h1 class="text-center">Add New Ads</h1>
	<div class="information block">
		<div class="container">
			<div class="panel panel-primary">
				<div class="panel-heading"> MY Ads</div>
				<div class="panel-body">
					<div class="col-md-8">
						<form class="form-horizontal main-form" enctype="multipart/form-data"
								action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
							<!--start item name field -->
							<div class="form-group form-group-lg">
								<label  class="col-sm-2 control-label">Name</label>
							    <div class="col-sm-10 col-md-9">
						       	 	<input type="text" class="form-control live-name"
						       	 	 name="name" placeholder="item Name" required>
							    </div>
							</div>
							<!--end item name field -->
							<!--start Description field -->
						    <div class="form-group form-group-lg">
								<label  class="col-sm-2 control-label">Description</label>
							    <div class="col-sm-10 col-md-9">
						       	 	<input type="text" class="form-control live-desc" required
						       	 	name="description" placeholder="Description of the item" >
							    </div>
							</div>
							<!--end Description field -->
							<!--start price field -->
						    <div class="form-group form-group-lg">
								<label  class="col-sm-2 control-label">Price</label>
							    <div class="col-sm-10 col-md-9">
						       	 	<input type="text" class="form-control live-price" 
								       	 	name="price" required
								       	 	placeholder="Price of the item [eg , dollar$,..]">
							    </div>
							</div>
							<!--end price field -->

							<!--start country made field -->
						    <div class="form-group form-group-lg">
								<label  class="col-sm-2 control-label">Country Made</label>
							    <div class="col-sm-10 col-md-9">
						       	 	<input type="text" class="form-control" 
								       	 	name="countryMade" required
								       	 	 placeholder="Like made on [egypt , china , spain ,..]">
							    </div>
							</div>
							<!--end country made field -->
							<!--start Avatar field -->
							<div class="form-group form-group-lg">
								<label  class="col-sm-2 control-label">Avatar</label>
							    <div class="col-sm-10 col-md-9">
						       	 	<input type="file" class="form-control" name="image"
						       	 			required='required'>
							    </div>
							</div>
							<!--end avatar field -->
							<!--start status field -->
						    <div class="form-group form-group-lg">
								<label  class="col-sm-2 control-label">Status</label>
							    <div class="col-sm-10 col-md-9">
						       	 	<select class="form-control" name="status"
						       	   	style="background-color:#eee">
						       	 		<option value="" >....</option>
						       	 		<option value="1">New</option>
						       	 		<option value="2">like New</option>
						       	 		<option value="3">Used</option>
						       	 		<option value="4">Old</option>
						       	 	</select>
							    </div>
							</div>
							<!--end status field -->
							<!--start category field -->
						    <div class="form-group form-group-lg">
								<label  class="col-sm-2 control-label">Category</label>
							    <div class="col-sm-10 col-md-9">
						       	 	<select class="form-control" name="category" 
						       	 		style="background-color:#eee">
						       	 		<option value="" >....</option>
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
							    <div class="col-sm-offset-2 col-sm-8">

						       	 	<input type="submit" class="btn btn-primary btn-sm"
						       	 			 value="Add Category ">
							    </div>
							</div>
							<!--end submit button-->
						</form>
					</div>

					<div class="col-md-4">
						<div class="thumbnail item-box live-preview">
							<span class="price-tag">$0</span>
							<img class="img-resposive"src="items.WEBP" alt=items>
							<div class="caption">
								<h3> Title</h3>
								<p>description</p>
							</div>
						</div>
					</div>
				</div>
				<!-- start show form errors -->
					<?php
						if(!empty($formerrors)){

							foreach($formerrors as $errors){

							echo '<div class="alert alert-danger">'.$errors.'</div>';
							}
						}
						
					?>
					<!-- end show form errors -->
			</div>
		</div>	
	</div>	

	
	<!-- end add new ads -->
	

<?php 

	}else{
		header('Location:index.php');
	}
include $tpl . "footer.php"; 