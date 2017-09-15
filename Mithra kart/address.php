<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if($role != "user"){
      header('Location: no_access.php');
    }
?>
<?php 
    
    $customer_id = $_SESSION['sess_user_id'];
    $category_name = $_SESSION['sess_firstname'];
    $email = $_SESSION['sess_email'];
    $item_id = $_SESSION['item_id'];
    $item_name = $_SESSION['item_name'];
    $price = $_SESSION['price'];
    $discount = $_SESSION['discount'];
    $quantity = $_SESSION['quantity'];
    $sale_price = $_SESSION['sale_price'];
?>
<?php
if(!empty($_POST['add-address'])) {
  /* Form Required Field Validation */
  foreach($_POST as $key=>$value) {
  if(empty($_POST[$key])) {
  $message = ucwords($key) . " field is required";
  break;
  }
  }

  if(!isset($message)) {
    require 'database-config.php';
    $qAddress = "SELECT id FROM address WHERE customer_id='".$customer_id."'";

    $queryAddress = $dbh->prepare($qAddress);

    $queryAddress->execute(array(':id'));

    require_once("dbcontroller.php");
    $db_handle = new DBController();

    if($queryAddress->rowCount() > 0){ 
      $query = "UPDATE `address` SET `full_name`='" . $_POST["full_name"] . "',`delivery_mobile`='" . $_POST["delivery_mobile"] . "',`pincode`='" . $_POST["pincode"] . "',`house_no`='" . $_POST["house_no"] . "',`colony`='" . $_POST["colony"] . "',`landmark`='" . $_POST["landmark"] . "',`city`='" . $_POST["city"] . "' WHERE customer_id='".$customer_id."'";
      $result = $db_handle->updateQuery($query);
      if(!empty($result)) {
        header('Location: address.php');
        unset($_POST);
      } else {
        $message = "Problem in address update. Try Again!"; 
      }
    }else{
      $query = "INSERT INTO address (customer, customer_id, email, full_name, delivery_mobile, pincode, house_no, colony, landmark, city) VALUES
        ('" . $category_name . "', '" . $customer_id . "', '" . $email . "', '" . $_POST["full_name"] . "', '" . $_POST["delivery_mobile"] . "', '" . $_POST["pincode"] . "', '" . $_POST["house_no"] . "', '" . $_POST["colony"] . "', '" . $_POST["landmark"] . "', '" . $_POST["city"] . "')";
        $result = $db_handle->insertQuery($query);
        if(!empty($result)) {
          header('Location: address.php');
          unset($_POST);
        } else {
          $message = "Problem in address adding. Try Again!"; 
        }
      }
    }
    session_write_close();
  }
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
    <div class="row justify-content-center address-form-view main-wrapper">
    <?php if(isset($message)) { ?>
    <div class="alert alert-success col-12 text-center"><?php echo $message; ?></div>
    <?php } ?>
    <div class="col-12 col-md-4">
      <?php
          require 'database-config.php';

          $qAddress = "SELECT id,full_name,delivery_mobile,pincode,house_no,colony,landmark,city FROM address WHERE customer_id='".$customer_id."'";

          $queryAddress = $dbh->prepare($qAddress);

          $queryAddress->execute(array(':id', ':full_name', ':delivery_mobile', ':pincode', ':house_no', ':colony', ':landmark', ':city'));

          if($queryAddress->rowCount() > 0){ 
            while($row=$queryAddress->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
      ?>
        <ul class="list-unstyled alert alert-success">
          <li class="row"><span class="col-5">Name </span><span class="col-7">: <?php echo $full_name; ?></span></li>
          <li class="row"><span class="col-5">Mobile Number </span><span class="col-7">: <?php echo $delivery_mobile; ?></span></li>
          <li class="row"><span class="col-5">Pin code </span><span class="col-7">: <?php echo $pincode; ?></span></li>
          <li class="row"><span class="col-5">House Number </span><span class="col-7">: <?php echo $house_no; ?></span></li>
          <li class="row"><span class="col-5">Colony </span><span class="col-7">: <?php echo $colony; ?></span></li>
          <li class="row"><span class="col-5">Landmark </span><span class="col-7">: <?php echo $landmark; ?></span></li>
          <li class="row"><span class="col-5">City </span><span class="col-7">: <?php echo $city; ?></span></li><br>
          <li class=""><a href="confirm.php"><button class="btn btn-sm btn-info">Deliver to this address</button></a></li>
        </ul>
        <?php 
          }
        }else{ 
      ?>
      <ul class="text-center list-unstyled row alert alert-danger">
        <li class="col-12 col-md-12">
            <h5>Address</h5>
        </li>
      </ul>
     <?php }session_write_close(); ?>
    </div>
		<div class="col-12 col-md-3">
			<form action="" method="post" role="form" enctype="multipart/form-data">
				<div class="form-group">
				    <input type="text" class="form-control" id="fullName" name="full_name" placeholder="Full Name"/>
				</div>
        <div class="form-group">
            <input type="text" class="form-control" id="mobile" name="delivery_mobile" placeholder="Mobile Number"/>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Pin code"/>
        </div>
				<div class="form-group">
				    <input type="text" class="form-control" id="houseNo" name="house_no" placeholder="Flat / House No. / Floor / Building"/>
				</div>
        <div class="form-group">
            <input type="text" class="form-control" id="colony" name="colony" placeholder="Colony / Street / Locality"/>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="landmark" name="landmark" placeholder="Landmark"/>
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="city" name="city" placeholder="Town/City"/>
        </div>
        
				<input type="submit" name="add-address" value="Add Address" class="btn btn-info">
        
			</form>
		</div>
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
