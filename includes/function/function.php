<?php

	//function to get all record on database
	
	function getAll($table,$order){
		global $db;

		$getall = $db->prepare("SELECT * FROM $table ORDER BY $order DESC");
		$getall ->execute();
		$getdata = $getall->fetchAll();
		return $getdata;
	}
	/* 
	function to get category 
	*/
	function getCat(){

		global $db;
		$cats = $db->prepare("SELECT * FROM categories WHERE parent = 0 ORDER BY cat_id ASC");
		$cats->execute();
		$rows= $cats->fetchAll();
		return $rows;

	}
	/* function to get items and show it*/

	function getItems($where, $value, $Approve = NULL){

		global $db;

		if($Approve == NULL){

			$sql = 'AND Approve = 1';
		}else{
			$sql = NULL;
		}

		$items = $db->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY item_id DESC");
		$items->execute(array($value));
		$allItems = $items->fetchAll();
		return $allItems;
	}



	/* ============================================
	   ============================================
	   ========== Backend Function ================
	   ============================================
	   ============================================

	*/

	//function to print page title for the pages & the page has no tiltle print defoult title
	
		function pageTitle(){

			global $pageTitle;

			if(isset($pageTitle)){

				echo $pageTitle;
			}else{

				echo 'Defoult';
			}
		}

		/* 
		** redirect back or home function accept  params [$Msg , $url, $seconds]
		** $Msg = echo error message 
		** $url = home page | back
		** $seconds  = noumbers of seconds to redirect 
		*/
	function redirectHome($msg, $url =null ,$seconds =3){

		echo $msg;
		if($url === null){
			$url  = 'index.php';
			$link = 'home';
		}else{

			//handel error if write on url direct
			if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']!==''){

				$url =$_SERVER['HTTP_REFERER'];
				$link = 'back';
			}else{

				$url  = 'index.php';
				$link = 'home';
			}
		}

		echo "<div class='alert alert-info'>You will be redirct to $link page after ".$seconds.' seconds</div>';
		
		header("Refresh:$seconds; url=$url");
		exit();
	}

		/*
		** function to checkitem on database that accept param[$select,$from,$value]
		** $select = select row on database such as (username ,email) 
		** $from   = the table name (users , category)
		** $value  = the value of selected such as (abdallah , abdallah@gmail.com)
		*/
	function checkItem($select, $from, $value){

		global $db;

		$statment =$db->prepare("SELECT `$select` FROM `$from`  WHERE `$select` = ?");

		$statment->execute(array($value));

		$count = $statment->rowCount();

		return $count;
	}

		/*
		** function to count the number of data to show on dashboard
		** accept param [$item | $table ]
		** $item  =  the column on data base as [ userid | userName | ...]
		** $table =  the name of table on database
		*/
	function itemCount($item, $table){

		global $db;
		$stmt = $db->prepare("SELECT COUNT(`$item`) FROM `$table`");
		$stmt->execute();
		$rows = $stmt->fetchColumn();

		return $rows;

	}
 
		/* function to show latest [ user | item | ]
		** function accept param [ ]
		**
		**
		**
		*/
	function latestItem($select, $table, $orderby, $limit = 6){

		global $db;
		$stmt = $db->prepare("SELECT $select FROM `$table` ORDER BY `$orderby` DESC LIMIT $limit");
		$stmt->execute();
		$rows = $stmt->fetchAll();

		return $rows;
	}

?>