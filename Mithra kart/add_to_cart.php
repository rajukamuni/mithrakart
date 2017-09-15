<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if($role!="user"){
      header('Location: no_access.php');
    }
?>
<?php
	if (isset($_POST['action'])) {

		$button_value = $_POST['submit'];

		if ($button_value == "Buy now") {
			$_SESSION['quantity'] = $_POST['quantity'];
			header('Location: address.php');
		}
		if ($button_value == "Add to cart"){

			$customerId = $_SESSION['sess_user_id'];
		    $customerName = $_SESSION['sess_firstname'];
		    $priceChanged = $_POST['price'];
		    $discountChanged = $_POST['discount'];
			$itemId = $_SESSION['item_id'];
		    $itemName = $_POST['item_name'];
		    $categoryId = $_POST['category_id'];
		    $salePrice = $_POST['sale_price'];
	    
			$_SESSION['quantity'] = $_POST['quantity'];
			$quantityChanged = $_POST['quantity'];

		    // total price calculation with respect to quantity
		    $total_price = $salePrice * $quantityChanged ;


		    require 'database-config.php';
		    $q = 'SELECT item_id FROM cart WHERE customer_id = '.$customerId.'';

            $queryCart = $dbh->prepare($q);

            $queryCart->execute(array(':item_id'));

            if($queryCart->rowCount() > 0){
              while($row=$queryCart->fetch(PDO::FETCH_ASSOC)){
                extract($row);

                if ($item_id == $itemId) {
                	$same_item = '1';
            		$msg = "This item is already in your cart";
                }
                else {
                	$same_item = '0';
            		$msg = "Item added to cart";
				}
			 }
			}else{
                $same_item = '0';
            	$msg = "No Item found in cart";
            }
			require_once("dbcontroller.php");
			$db_handle = new DBController();
			if ($same_item == '1') {
	            $query = "UPDATE cart SET  quantity=" . $quantityChanged . " WHERE item_id='".$itemId."' AND  customer_id='".$customerId."'";
				$added_to_cart = $db_handle->updateQuery($query);
			}
			if ($same_item == '0') {
				$query = "INSERT INTO cart (customer, customer_id, item_id, quantity) VALUES
						('" . $customerName . "', '" . $customerId . "','" . $itemId . "', '" . $quantityChanged . "')";
				$added_to_cart = $db_handle->insertQuery($query);
			}
		
			$_SESSION['msg'] = $msg;
			$_SESSION['added_to_cart'] = $added_to_cart;
			header('Location: added_to_cart.php');
		}
	}
?>