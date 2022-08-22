<?php 
	
	session_start();
	$pageTitle = "Show Items";
	include "init.php";

		// for itemid request that is number and feom database
		$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])? intval($_GET['itemid']):0;
		//select data from database
		$stmt   = $db->prepare("SELECT 
									`items`.*,`categories`.`category_name`,
									`users`.`UserName` AS `member_name`
								FROM `items`
								INNER JOIN 
								 		`categories` ON `categories`.`cat_id` = `items`.`category_id`
								INNER JOIN 
								 		`users` ON `users`.`UserID` = `items`.`member_id`
						 		WHERE `item_id` = ?
						 		AND `Approve` =1");
		//execute query
		$stmt->execute(array($itemid));
		//fetch data
		$item    = $stmt->fetch();
		//check if is larger than 0
		$count  = $stmt->rowCount();

		if($count >0){	
?>
	<div class="container">
		<h1 class="text-center"><?php echo $item['Name'] ?></h1>
		<div class="row">
			<div class="col-md-3">
				<?php 
					echo '<img class="img-resposive"   src="upload\avatar\\'.$item['image'].'" alt="items" style="height:200px">';
				?>
			</div>
			<div class="col-md-9 item-info">
				<ul class="list-unstyled">
					<li><?php echo $item['Name'];?></li>

					<li>
						<i class="fa fa-align-justify"></i>
						<span>Description</span> : <?php echo $item['item_desc'] ?>
					</li>
					<li>
						<i class="fa fa-dollar"></i>
						<span>Price</span> : <?php echo $item['price'] ?>
					</li>
					<li>
						<i class="fa fa-calendar"></i>
						<span>Date</span> : <?php echo $item['item_date'] ?>
					</li>
					<li>
						<i class="fa fa-flag"></i>
						<span>Made in</span>  : <?php echo $item['country_made'] ?>
					</li>
					<li>
						<i class="fa fa-sitemap"></i>
						<span>Category Name : </span><a href="categories.php?catId=<?php echo $item['category_id'] ?>"><?php echo $item['category_name'] ?></a>
					</li>
					<li>
						<i class="fa fa-user"></i>
						<span>Add By</span> : <a href="#"><?php echo $item['member_name'] ?></a>
					</li>
				</ul >
			</div>
		</div>
		<hr class="custom-hr">
		<?php 
		if(isset($_SESSION['User'])){?>
		<div class="col-md-offset-3">
			<div class="add-comment">
				<h3>Add your Comment</h3>
				<form action="<?php echo $_SERVER['PHP_SELF'].'?itemid='. $item['item_id']?>" method="POST">
					<!--Basic textarea-->
					<div class="md-form mb-4 pink-textarea active-pink-textarea-2">
					  <textarea id="form17" class="md-textarea form-control"
					  			name="comment" rows="3" required></textarea>
					</div>
					<input type="submit" value="Add comment" class="btn btn-primary">
				</form>
				<?php 
					if($_SERVER['REQUEST_METHOD'] == 'POST'){

						$comment = filter_var($_POST['comment'],FILTER_SANITIZE_FULL_SPECIAL_CHARS);
						$itemid = $item['item_id'];
						$userid  = $_SESSION['uid'];

						if(!empty($comment)){

							$stmt = $db->prepare("INSERT INTO
														`comments`(
																	`comment`,`status`,`date`,
																	`item_id`,`user_id`
																  )
												VALUES (:zcomment,0,now(),:zitemid,:zuserid)
													
													");
							$stmt->execute(array(

										'zcomment' => $comment,
										'zitemid'  => $itemid,
										"zuserid"  => $userid
							));

							if($stmt){

								echo "<div class='alert alert-success'>";
									echo '<p> Comment Added Succesfuly</p>';
								echo "</div>";	
							}

						}

				

					}


				?>
			</div>
		</div>	
		<hr class="custom-hr">
		<?php 
			}else{
				 echo "<div class = 'alert alert-info'>";
				 		echo " Please <a href='login.php'> login | register </a> to Add Comment";


				 echo "</div>";
			}

				//select comment to show on table 
					$stmt = $db->prepare("SELECT `comments`.*,`users`.`UserName`

											FROM `comments`
											
											INNER JOIN `users` ON `users`.`UserID` = `comments`.`user_id`

											WHERE `item_id` = ?");

					//execute statment
					$stmt->execute(array($itemid));
					//fetch data
					$comments = $stmt->fetchAll();
					foreach( $comments as $comment){
						echo '<div class="comment-box">';
							echo "<div class='row'>";
								echo "<div class='col-sm-2'>";
									echo '<img class="img-resposive img-thumbnail img-circle center-block"src="flat.jpg" alt=items>';

									echo '<p class="text-center">'.$comment['UserName'].'</p>';
								echo '</div>';
								echo "<div class='col-sm-10'>";
									echo "<p class='lead content'>".$comment['comment'].'</p>';
								echo '</div>';
							echo '</div>';
							echo "<hr class='custom-hr'>";
						echo "</div>";	
					
					}

			?>
	</div>

<?php 
	}else{
		echo "<div class='container'>";
			echo "<div class='alert alert-danger'> .! Item watting to Approve </div>";
			redirectHome('','back',3);
		echo "</div>";

		
	}

include $tpl . "footer.php"; 