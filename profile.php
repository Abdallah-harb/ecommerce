<?php 
	
	session_start();
	$pageTitle = "Profile page";
	include "init.php";

	if(isset($_SESSION['User'])){
		
		$getUser = $db->prepare("SELECT * FROM users WHERE UserName = ? ");
		$getUser->execute(array($_SESSION['User']));
		$getinfo = $getUser->fetch();

?>

		<!-- start profile -->
		<h1 class="text-center">My Profile</h1>
		<div class="information block">
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading"> 
						MY Information
						<a href="editprofile.php?do=Edit&userId=<?php echo $_SESSION['uid']?>" class="pull-right btn Edit">
							<i class="fa fa-edit"></i>

							Edit Profile
						</a>	
					</div>
					<div class="panel-body">
						<ul class="list-unstyled">
							<li>
								<i class="fa fa-unlock"></i>
								<span>Login Name </span> : <?php echo $getinfo['UserName']; ?>
							</li>
							<li>
								<i class="fa fa-envelope"></i>
								<span>Email </span> : <?php echo $getinfo['email']; ?>
							</li>
							<li>
								<i class="fa fa-user"></i>
								<span>FullName</span> : <?php echo $getinfo['fullName']; ?>
							</li>
							<li>
								<i class="fa fa-calendar"></i>
								<span>Regester Date </span> : <?php echo $getinfo['Date']; ?>
							</li>
							<li>
								<i class="fa fa-sitemap"></i>
								<span>favourite Category </span> :<?php echo $getinfo['UserID'];?>
							</li>
						</ul>
					</div>
				</div>
			</div>	
		</div>	

		<div class="my-ads block">
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading"> 
						Latest Ads

						<a href="newadd.php" class="pull-right btn ads">
							<i class="fa fa-plus"></i>

							Add New Ads
						</a>	
					</div>
					<div class="panel-body">
						<?php
							$dataitems = getItems('member_id',$getinfo['UserID'] );
							if(isset($dataitems)){
								echo "<div class='row'>";
								foreach(getItems('member_id',$getinfo['UserID'],'asd') as $item){

									echo '<div class="col-sm-6 col-md-3">';
										echo '<div class="thumbnail item-box">';
											if($item['Approve'] == 0){

													echo "<span class='Approve-status'>Watting To Approve</span>";
												}
											echo '<span class="price-tag">$'.$item['price'].'</span>';
											echo '<img class="img-resposive"   src="upload\avatar\\'.$item['image'].'" alt="items" style="height:200px">';
											echo '<div class="caption">';
												echo '<h3><a  href="item.php?itemid='.$item['item_id'].'">'.$item['Name'].'</a></h3>';
												echo "<p>".$item['item_desc']."</p>";
												echo '<div class="data">'.$item['item_date'].'</div>';
											echo '</div>';
										echo '</div>';
										
									echo '</div>';
								}
								echo "</div>";
							}else{
								echo "There's no Items yet to show";
							}


							
						?>
					</div>
				</div>
			</div>	
		</div>	

		<div class="comment block">
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading"> latest Comment</div>
					<div class="panel-body">
							<?php 
								$stmt = $db->prepare("SELECT comment FROM comments WHERE user_id = ?");
								$stmt->execute(array($getinfo['UserID']));
								$comments = $stmt->fetchAll();

								if(!empty($comments)){

									foreach($comments as $comment){

										echo '<p>'.$comment['comment'].'</p>';
									}

								}else{

									echo "<div class='container'>";
										echo 'No Comments Yet To Show';
									echo "</div>";
								}
							?>
					</div>
				</div>
			</div>	
		</div>	
		<!-- end profile -->

<?php 

}else{
	header('Location:index.php');
}
include $tpl . "footer.php"; 