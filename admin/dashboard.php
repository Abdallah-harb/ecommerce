<?php
	
	ob_start(); //for error heade [output Buffering start]
	session_start();
	if(isset($_SESSION['Username'])){
		$pageTitle  = "Dashboard";
		include "init.php";

		//number of user to get 
		$lastusercount = 5;
		//the varible to get latest and use it on foreach
		$latestmembers = latestItem("*", 'users', 'UserID',$lastusercount);
		/* start dashboard page */

		//number of item get
		$lastItemCount = 5;
		$latestItems = latestItem("*",'items','item_id',$lastItemCount);

		// number of latest comments
		$lastcountComment = 5;
		?>
		<div class='container home-stat text-center'>
			<div class="row">
				<h1>Dashboard Page</h1>	
				<div class="col-md-3">
					<div class="stat st-member">
						<i class="fa fa-users"></i>
						<div class="info">
							Total Members
							<span><a href="members.php"><?php echo itemCount('UserID','users')?></a>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-pending">
						<i class="fa fa-user-plus"></i>
						<div class="info">
							pending Members
							<span><a href="members.php?do=Manage&page=Pending">
								 <?php echo checkItem('Regstatus', 'users', 0)?>
								 </a>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="stat st-item">
						<i class="fa fa-tag"></i>
						<div class="info">
							Total Items
							<span><a href="item.php"><?php echo itemCount('item_id','items')?></a></span>
						</div>
						
					</div>
				</div>
				<div class="col-md-3 ">
					<div class="stat st-comments">
						<i class="fa fa-comment"></i>
						<div class="info">
							Total Comments
						<span> 
							<a href="comment.php"><?php echo itemCount('comment_id','comments')?></a>
						</span>
						</div>
						
					</div>
				</div>
			</div>
		</div>	

		<div class="container latest">
			<div class="row">
				<div class="col-sm-6">
					<div class="panel panel-default">
					    <div class="panel-heading">
					    	<i class="fa fa-user"></i> latest <?php echo $lastusercount ?> User 
					    	Regiseter
					    	<span class="pull-right toggle-info">
					    		<i class="fa fa-plus fa-lg"></i>
					    	</span>	
					    </div>
						<div class="panel-body">
						    <ul class="list-unstyled latest-user">
						    	<?php 

						     		foreach($latestmembers as $latest){

						     			echo "<li>". $latest['UserName'];

						     			
						     				echo "<a class = 'btn btn-success pull-right' href='members.php?do=Edit&userId=".$latest['UserID']."'>";
						     						echo "<i class='fa fa-edit'></i>";
						     				echo "Edit</a>";
						     				if($latest['Regstatus'] == 0){

					    						echo "<a href='members.php?do=Activate&userId="
					    							.$latest['UserID']."'
					    								 class='btn btn-info active pull-right'>
					    								 <i class='fa fa-check-square-o'></i>Activate</a>";
					    					}
						     			echo "</li>";

						     			
						     		}
						 		?>
						 		
						 	</ul>
					    </div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="panel panel-default">
					    <div class="panel-heading">
					    	<i class="fa fa-tag"></i> latest Items
					    	<span class="pull-right toggle-info">
					    		<i class="fa fa-plus fa-lg"></i>
					    	</span>
					    </div>
						<div class="panel-body">
						    <ul class="list-unstyled latest-user">
						    	<?php 

						     		foreach($latestItems as $items){

						     			echo "<li>". $items['Name'];

						     			
						     				echo "<a class = 'btn btn-success pull-right' href='item.php?do=Edit&itemid=".$items['item_id']."'>";
						     						echo "<i class='fa fa-edit'></i>";
						     				echo "Edit</a>";
						     				if($items['Approve'] == 0){

					    						echo "<a href='item.php?do=Approve&itemid="
					    							.$items['item_id']."'
					    								 class='btn btn-info active pull-right'>
					    								 <i class='fa fa-check-square-o'></i>Activate</a>";
					    					}
						     			echo "</li>";

						     			
						     		}
						 		?>
						 		
						 	</ul>
					    </div>
					</div>
				</div>
			</div>
			<!-- start show comments -->
			<div class="row">
				<div class="col-sm-6">
					<div class="panel panel-default">
					    <div class="panel-heading">
					    	<i class="fa fa-comments-o"></i> 
					    	latest comments
					    	<span class="pull-right toggle-info">
					    		<i class="fa fa-plus fa-lg"></i>
					    	</span>	
					    </div>
						<div class="panel-body">
						    <ul class="list-unstyled latest-user">
						    	<?php 

						     		$stmt = $db->prepare("SELECT `comments`.*,`users`.`UserName`
									FROM `comments` 
									INNER JOIN `users` ON `users`.`UserID` = `comments`.`user_id`
									");

									//execute statment
									$stmt->execute();
									//fetch data
									$comments = $stmt->fetchAll();
									foreach($comments as $comment){
										echo '<div class="comment-box">';
											echo "<span class ='comment-m'>".$comment['UserName']."</span>";
											echo "<p class ='comment-c'>".$comment['comment']."</p>";
										echo '</div>';	
									}
									

						 		?>
						 		
						 	</ul>
					    </div>
					</div>
				</div>
			</div>
			<!-- end show comments -->
		</div>	
		<?php
		/* End dashboard page */


		include  $tpl  . "footer.php";

	}else{

		header('Location:index.php');
		exit();
	}
	ob_end_flush();
?>