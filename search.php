
<?php 
ob_start();
$pageTitle = "search";
	include "init.php";

	/*
		###################################################
		###################################################
		################ Test for search to use ###########
		###################################################
		###################################################
	*/
	?>


	<!-- start search form -->
<div class="container">
	<form method="POST" class="search" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<div class="form-group form-group-lg">
			<label  class="col-sm-2 control-label" style="top:16px;font-size: 16px;" >Search
			</label>
		    <div class="col-sm-10 col-md-6">
	       	 	<input type="search" class="form-control" 
			       	 	name="item" 
			       	 	 placeholder="Search for item you need">
		    </div>
		</div>
	</form>		
</div>
<!-- end search form -->
	<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){

	$search = $_POST['item'];

	$stmt = $db->prepare("
							SELECT * FROM `items` 
						 	WHERE `Name` LIKE '%$search%' 
						 	ORDER BY `item_id` DESC ");
	$stmt->execute();
	$items = $stmt->fetchAll();
	$count = $stmt->rowCount();
	if($count > 0){

		foreach($items as $item){
				echo '<div class="col-sm-6 col-md-3">';
					echo '<div class="thumbnail item-box">';
						echo '<span class="price-tag">'.$item['price'].'</span>';
						echo '<img class="img-resposive"src="items.WEBP" alt=items>';
						echo '<div class="caption">';
							echo '<h3><a  href="item.php?itemid='.$item['item_id'].'">'.$item['Name'].'</a></h3>';
							echo "<p>".$item['item_desc']."</p>";
							echo '<div class="data">'.$item['item_date'].'</div>';
						echo '</div>';
					echo '</div>';		
				echo '</div>';	


		}

	}else{

		echo '<div class="container">';
			echo "<div class='alert alert-info'>Sorrry.! no item to show</div>";
		echo "</div>";
	}
}


?>



<?php
include $tpl . "footer.php"; 
ob_end_flush();

?>