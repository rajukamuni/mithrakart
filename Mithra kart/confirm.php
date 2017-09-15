<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];

    if($role!="user"){
      header('Location: no_access.php');
    }

    $customer_id = $_SESSION['sess_user_id'];
    $customer_name = $_SESSION['sess_firstname'];
    $customer_email = $_SESSION['sess_email'];
    $item_id = $_SESSION['item_id'];
    $item_name = $_SESSION['item_name'];
    $price = $_SESSION['price'];
    $discount = $_SESSION['discount'];
    $quantity = $_SESSION['quantity'];
    $sale_price = $_SESSION['sale_price'];

    // total price calculation
    $total_price = $sale_price * $quantity ;
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
    <div class="main-wrapper">
      <?php
          require 'database-config.php';
          $msg = '';
          $q = "SELECT item_table.item_id, item_table.item_name, item_table.stock, item_table.price, item_table.discount, item_table.sale_price, images.image FROM item_table INNER JOIN images ON item_table.item_id=images.item_id WHERE item_table.item_id='".$item_id."' AND images.image_number = '0'";

          $query = $dbh->prepare($q);

          $query->execute(array(':item_id', ':item_name', ':stock', ':price', ':discount', ':sale_price', ':image'));

          if($query->rowCount() > 0){ 
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $items = $quantity;
            $subtotal = $sale_price * $quantity;
      ?>
      <ul class="list-unstyled row cart-view-row">
        <li class="col-6 col-md-2">
          <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']); ?>" alt="" class="img-fluid" width="100" height="100">
        </li>
        <li class="col-6 col-md-5">
          <h4><a href="itemView.php?item=<?php echo $item_id; ?>" id="viewBtn"><?php echo $item_name; ?></a></h4>
          <p>Sale Price: <b class="text-danger"><span>&#8377</span> <?php echo $sale_price; ?></b> <s><span>&#8377</span> <?php echo $price; ?></s></p>
          <p>Discount: <?php echo $discount; ?> <span>&#37</span></p>
        </li>
        <li class="col-6 col-md-3">
          <h4><span>Quantity: </span> <?php echo $items; ?></h4>
        </li>
        <li class="col-6 col-md-2">
          <h4><span>&#8377</span> <?php echo $subtotal; ?></h4>
        </li>
      </ul>
      <?php
          require 'database-config.php';

          $qAddress = "SELECT id,full_name,delivery_mobile,pincode,house_no,colony,landmark,city FROM address WHERE customer_id='".$customer_id."'";

          $queryAddress = $dbh->prepare($qAddress);

          $queryAddress->execute(array(':id', ':full_name', ':delivery_mobile', ':pincode', ':house_no', ':colony', ':landmark', ':city'));

          if($queryAddress->rowCount() > 0){ 
            while($row=$queryAddress->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
      ?>
        <ul class="list-unstyled address-view-row">
          <li class="row"><span class="col-4">Name </span><span class="col-8">: <?php echo $full_name; ?></span></li>
          <li class="row"><span class="col-4">Mobile Number </span><span class="col-8">: <?php echo $delivery_mobile; ?></span></li>
          <li class="row"><span class="col-4">Pin code </span><span class="col-8">: <?php echo $pincode; ?></span></li>
          <li class="row"><span class="col-4">House Number </span><span class="col-8">: <?php echo $house_no; ?></span></li>
          <li class="row"><span class="col-4">Colony </span><span class="col-8">: <?php echo $colony; ?></span></li>
          <li class="row"><span class="col-4">Landmark </span><span class="col-8">: <?php echo $landmark; ?></span></li>
          <li class="row"><span class="col-4">City </span><span class="col-8">: <?php echo $city; ?></span></li>
        </ul>
        <?php 
          }
        }else{ 
      ?>
      <ul class="text-center list-unstyled row alert alert-danger">
        <li class="col-12">
            <a href="#">Problem with server. Try Again!</a>
        </li>
      </ul>
     <?php }session_write_close(); ?>
            <div class="row alert alert-success" role="alert">
              <div class="col-12 col-md-8">
                <h5>Subtotal (<?php echo $items; ?> item):  <span>&#8377</span> <?php echo $subtotal; ?></h5>
              </div>
              <div class="col-12 col-md-4">
                <a href="payment.php"><button class="btn btn-info">Proceed to Checkout</button></a>
              </div>
            </div>

      <?php 
        $_SESSION['subtotal'] = $subtotal;
        $_SESSION['units'] = $items;

        }else{ 
      ?>
      <ul class="text-center list-unstyled row alert alert-danger">
        <li class="col-12">
            <a href="#">Problem with server. Try Again!</a>
        </li>
      </ul>

        <?php }session_write_close(); ?>
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