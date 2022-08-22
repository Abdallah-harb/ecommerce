<?php 
	
	ob_start();
	session_start();
	$pageTitle = "Home Page";
	include "init.php";
	?>
	<div class="container">

		<!-- start search for items-->
		<div class="row">
			<form method="POST" class="search " action="<?php echo $_SERVER['PHP_SELF'];?>">
				    <div class=" col-lg-4 col-lg-offset-4">
       				   <div class="input-group">
	         			   <input type="search" class="form-control" placeholder="Search for item you need" name="item" /> 
	         			   <span class="input-group-btn">
	            			    <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
	           				 </span>
        			   </div><!-- /input-group -->
   					</div>
			</form>	
		</div>
		<!-- End search for items-->
		<div class="row">
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
					if($count > 0 ){

						foreach($items as $item){

							if($item['Approve'] == 1){	
								echo '<div class="col-sm-6 col-md-3">';
									echo '<div class="thumbnail item-box">';
										echo '<span class="price-tag">$'.$item['price'].'</span>';
										echo '<img class="img-resposive"src="upload\avatar\\'.$item['image'].'" alt="items" style="height:200px" >';
										echo '<div class="caption">';
											echo '<h3><a  href="item.php?itemid='.$item['item_id'].'">'.$item['Name'].'</a></h3>';
											echo "<p>".$item['item_desc']."</p>";
											echo '<div class="data">'.$item['item_date'].'</div>';
										echo '</div>';
									echo '</div>';		
								echo '</div>';	
							}else{
								echo "<div class='alert alert-danger'>";
									echo "Sorry this item you need Adminastrater Not Approved Yet.! ";
								echo "</div>";
								redirectHome('','back',3);
							}

						}

					}else{

						echo '<div class="container">';
							echo "<div class='alert alert-info'>Sorrry.! no item to show</div>";
							redirectHome('','back',3);
						echo "</div>";
					}
				}else{
						$items = getAll('items','item_id');
						foreach($items as $item){
							if($item['Approve'] == 1){

								echo '<div class="col-sm-6 col-md-3">';
									echo '<div class="thumbnail item-box">';
										echo '<span class="price-tag">$'.$item['price'].'</span>';
										echo '<img class="img-resposive"src="upload\avatar\\'.$item['image'].'" alt="items" style="height:200px">';
										echo '<div class="caption">';
											echo '<h3><a  href="item.php?itemid='.$item['item_id'].'">'.$item['Name'].'</a></h3>';
											echo "<p>".$item['item_desc']."</p>";
											echo '<div class="data">'.$item['item_date'].'</div>';
										echo '</div>';
									echo '</div>';
									
								echo '</div>';
							}	
						}
				}





				
			?>
		</div>
	</div>
<?php	

include $tpl . "footer.php"; 
ob_end_flush();