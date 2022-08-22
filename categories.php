<?php 
	
	session_start();
	$pageTitle = "category";
	include "init.php";

	?>

	<div class="container">
		<h1 class="text-center">show Category</h1>
		<div class="row">
			<?php

				foreach(getItems('category_id',$_GET['catId']) as $item){

					echo '<div class="col-sm-6 col-md-3">';
						echo '<div class="thumbnail item-box">';
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
			?>
		</div>
	</div>
<?php	

include $tpl . "footer.php"; 