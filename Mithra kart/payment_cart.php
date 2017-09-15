<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];

    if($role!="user"){
      header('Location: no_access.php');
    }

?>
<?php 
  $customer_id = $_SESSION['sess_user_id'];
  $customer_name = $_SESSION['sess_firstname'];
  $customer_email = $_SESSION['sess_email'];
?>
<!DOCTYPE html>
<html>
  <head>
        <!-- Content Type Meta tag -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <!--Responsive Viewport Meta tag-->
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        
        
    <title>Shopping cart app</title>
        
    <!-- Roboto font embed -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
  </head>
  <body>

    <header>
      <nav class="navbar-top navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="userHome.php">MITHRA KART</a>
          <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <a class="nav-link" href="cart.php">My cart <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="my_orders.php">My Orders</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="my_profile.php">My Profile</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="help.php">Help</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="#">Hi <?php echo $_SESSION['sess_firstname'];?><span class="sr-only">(current)</span></a>
              </li>
            </ul>
            <form class="form-inline mt-2 mt-md-0">
              <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="logout.php">
                  <button class="btn btn-outline-success my-2 my-sm-0" type="button">Log out</button>
                </a>
              </li>
            </ul>
          </div>
        </nav>
    </header>
	<?php
		      require 'database-config.php';
          $q = "SELECT item_table.item_id, item_table.item_name, item_table.sale_price, cart.quantity FROM item_table INNER JOIN cart ON item_table.item_id=cart.item_id WHERE cart.customer_id='".$customer_id."'";

          $query = $dbh->prepare($q);

          $query->execute(array(':item_id',':item_name', ':sale_price' ,':quantity'));

          if($query->rowCount() > 0){ 
            require_once("dbcontroller.php");
            $db_handle = new DBController();
            while($row=$query->fetch(PDO::FETCH_ASSOC)){
              extract($row);
            	// total price calculation
              $total_price = $sale_price * $quantity ;

              $queryOrder = "INSERT INTO orders (item_id, item_name, customer, customer_id, quantity, amount) VALUES ('" . $item_id . "', '" . $item_name . "', '" . $customer_name . "', '" . $customer_id . "', '" . $quantity . "', '" . $total_price . "')";
              $result = $db_handle->insertQuery($queryOrder);

              $query_user = "INSERT INTO user_orders (item_id, item_name, customer, customer_id, quantity, amount) VALUES ('" . $item_id . "', '" . $item_name . "', '" . $customer_name . "', '" . $customer_id . "', '" . $quantity . "', '" . $total_price . "')";
              $result_user = $db_handle->insertQuery($query_user);
            }
            if(!empty($result) && !empty($result_user)) {
              $message = "Orders Placed successfully!";
              require_once 'database-config.php';
              // it will delete cart items
              $stmt_delete = $dbh->prepare('DELETE FROM cart WHERE customer_id =:id');
              $stmt_delete->bindParam(':id',$customer_id);
              $stmt_delete->execute();
            } else {
              $message = "Problem in Orders Placing. Try Again!"; 
            }
        }else{
        	$message = "No items in cart";
        }

?>
    <div class="col-12 align-items-center main-wrapper">
      <?php if(isset($message)) { ?>
      <div class="alert alert-success col-12 text-center"><?php echo $message; ?></div>
      <?php } ?>
        <h5 class="text-center col-12">Payment here!!</h5>
    </div>

    <footer>
      <div class="footer-copyright-section">
        <div class="container">
          <div class="row">
            <div class="col-12 col-md-6">
              <div class="footer-copyright">copyright@TechMithra</div>
            </div>
            <div class="col-12 col-md-6">
              <ul class="list-unstyled list-inline footer-icons">
                <li class="list-inline-item">
                  <a href="https://www.facebook.com/TechMithra/" target="_blank">
                    <i class="fa fa-facebook"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a href="https://twitter.com/TechMithra" target="_blank">
                    <i class="fa fa-twitter"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a href="#" target="_blank">
                    <i class="fa fa-linkedin"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a href="https://plus.google.com/u/1/108447099777048828294" target="_blank">
                    <i class="fa fa-google-plus"></i>
                  </a>
                </li>
                <li class="list-inline-item">
                  <a href="https://www.instagram.com/techmithra/" target="_blank">
                    <i class="fa fa-instagram"></i>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>

    <!-- JavaScripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
  </body>
</html>