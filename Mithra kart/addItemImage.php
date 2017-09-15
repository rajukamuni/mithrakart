<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if($role!="admin"){
      header('Location: index.php?err=2');
    }
?>
<?php
	$msg = '';
	if (!empty($_POST["add-item"])) {
		$category_id = $_POST["category_id"];
		$item_name = $_POST["item_name"];
		// discount calculation
		$discount = $_POST["discount"];
		$price = $_POST["price"];
        $discount_in_rupees = ($discount/100) * $price ;
        $sale_price = $price - $discount_in_rupees ;

		require_once("dbcontroller.php");
		$db_handle = new DBController();
		$query = "INSERT INTO item_table (category_id, item_name, stock, price, discount, sale_price) VALUES ('".$category_id."', '" . $item_name . "', '" . $_POST["stock"] . "', '" . $price . "', '" . $discount . "', '".$sale_price."')";
		$result = $db_handle->insertQueryImage($query);
		if($result != false) {
			$item_id_image =  $result;
			$con = mysqli_connect('localhost','root','','mithrakart_db') or die('Unable To connect');
				for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
					//Get the temp file path
					$image = $_FILES['images']['tmp_name'][$i];
					$img = file_get_contents($image);
					$sqlImage = "INSERT INTO images (item_id, image, image_number) VALUES
								('".$item_id_image."', ?, '".$i."')";
					$stmtImage = mysqli_prepare($con,$sqlImage);
						 
					mysqli_stmt_bind_param($stmtImage, "s",$img);
					mysqli_stmt_execute($stmtImage);
								 
					$checkImage = mysqli_stmt_affected_rows($stmtImage);
					if(!empty($checkImage)){
						$msg = 'Successfullly Uploaded';
					}else{
						$msg = 'Could not upload images';
					}
				}
		} else {
			$error_message = "Could not add item in category table";	
		}
	}else{
		$msg = ' Problem in adding. Did not get the call.. Try Again!';
	}
	mysqli_close($con);
	unset($_POST);
	header('Location: addItem.php?msg='.$msg.'');
?>
