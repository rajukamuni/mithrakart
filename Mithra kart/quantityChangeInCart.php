<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if($role!="user"){
      header('Location: no_access.php');
    }
?>
<?php
	// if (isset($_POST['change_quantity'])) {

		$itemId = $_POST['itemId'];
	    
		$_SESSION['quantity'] = $_POST['quantity'];
		$quantityChanged = $_POST['quantity'];

			$customerId = $_SESSION['sess_user_id'];
		    $customerName = $_SESSION['sess_firstname'];
		    $priceChanged = $_POST['price'];
		    $discountChanged = $_POST['discount'];
		    $salePrice = $_POST['sale_price'];

		    // total price calculation w.r.t quantity
		    $total_price = $salePrice * $quantityChanged ;


			require_once 'database-config.php';
  
	  
	    // select query
	    $stmt_select = $dbh->prepare('SELECT quantity FROM cart WHERE customer_id='.$customerId.' AND item_id=:id');
	    $stmt_select->execute(array(':id'=>$_POST['itemId']));
	    $imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
	    
	    // it will update an actual record from db
	    $stmt_delete = $dbh->prepare("UPDATE cart SET quantity=" . $quantityChanged . " WHERE item_id=:id");
	    $stmt_delete->bindParam(':id',$_POST['itemId']);
	    $stmt_delete->execute();
	    
	    header("Location: cart.php");
	 
	// }
	
?>