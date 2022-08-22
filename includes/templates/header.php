<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo pageTitle();?></title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo $css;?>front.css">
	</head>
	<body>
		

		<nav class="navbar navbar-inverse">
		  <div class="container">
		    <div class="navbar-header">

		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="homepage.php"> Home </a>
		    </div>
		    <div class="collapse navbar-collapse" id="app-nav">
		      <ul class="nav navbar-nav ">
		        <?php 
		        	foreach(getCat() as $cats){
		        		echo '<li>';
		        			echo '<a href="categories.php?catId='.$cats['cat_id'].'">'.$cats['category_name'].'</a>';
		        		echo '</li>';
		        	}
		        ?>
		      </ul>
		      <?php 

		      if(isset($_SESSION['User'])){

		      	// if the user is[admin] show link to go to adminpanel
		      	$stmt = $db->prepare("  SELECT `GroupId` 
		      							FROM `users`
		      							WHERE `UserID`=?
		      							AND `GroupId` = 1 ");
		      	$stmt ->execute(array($_SESSION['uid']));      	
		      	$adminCount = $stmt->rowCount();
		      	?>
		      		<ul class="nav navbar-nav navbar-right">
				        <li class="dropdown ">
				          <a href="profile.php" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['User']?><span class="caret"> </span></a>
				          <ul class="dropdown-menu">
				          	<?php  

				          		if($adminCount > 0){

				          			echo '<li>';
				          				echo "<a href='../e-commerce/admin/index.php' tarhet='_blank'> Go To Admin Panel</a>";
				          			echo "</li>";
				          		}
				          	?>
				            <li>
				              <a href="editprofile.php?do=Edit&userId=<?php echo $_SESSION['uid']?>" target="_blank"><i class="fas fa-warehouse"></i>Setting</a>
				            </li>
				            <li>
				              <a href="profile.php" ><i class="fas fa-warehouse"></i>My Profile</a>
				            </li>
				            <li role="separator" class="divider"></li>
				            <li><a href="logout.php">logout</a></li>
				          </ul>
				        </li>
		            </ul>
		  
		      	<?php 
		      }else{

		      	?>
		      		<ul class="nav navbar-nav navbar-right">
		      			<li>
		      				<a href="login.php">
								<span class="pull-right"> Login | SignUp</span>
							</a>
		      			</li>	
		      		</ul>	
		      		<!--
		      		<div class="container">
						<div class="upper-bar">
							<a href="login.php">
								<span class="pull-right"> Login | SignUp</span>
							</a>	
						</div>
				    </div>
				-->
		      	<?php 
		      }
		      ?>
		    </div>
		  </div>
		</nav>