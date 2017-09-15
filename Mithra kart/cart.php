<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if($role != "user"){
      header('Location: no_access.php');
    }

    $customer_id = $_SESSION['sess_user_id'];
    $customer_name = $_SESSION['sess_firstname'];
?>
<?php

  require_once 'database-config.php';
  
  if(isset($_GET['delete_id']))
  {
    // select image from db to delete
    // $stmt_select = $dbh->prepare('SELECT item_id FROM cart WHERE item_id =:id');
    // $stmt_select->execute(array(':id'=>$_GET['delete_id']));
    // $imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
    // unlink("user_images/".$imgRow['userPic']);
    
    // it will delete an actual record from db
    $stmt_delete = $dbh->prepare('DELETE FROM cart WHERE item_id =:id');
    $stmt_delete->bindParam(':id',$_GET['delete_id']);
    $stmt_delete->execute();
    
    header("Location: cart.php");
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
    <div class="main-wrapper">
      <?php
          require 'database-config.php';
          $msg = '';
          $q = "SELECT item_table.item_id, item_table.item_name, item_table.stock, item_table.price, item_table.discount, item_table.sale_price, images.image, cart.quantity FROM item_table INNER JOIN images INNER JOIN cart ON item_table.item_id=images.item_id AND item_table.item_id=cart.item_id WHERE cart.customer_id=".$customer_id." AND images.image_number = '0'";

          $query = $dbh->prepare($q);

          $query->execute(array(':item_id', ':item_name', ':stock', ':price', ':discount', ':sale_price', ':image', ':quantity'));

          if($query->rowCount() > 0){ 
            $subtotal = 0;
            $items = 0;
            while($row=$query->fetch(PDO::FETCH_ASSOC)){
              extract($row);
              
              // total price calculation w.r.t quantity
              $total_price = $sale_price * $quantity ;
              $subtotal = $subtotal + $total_price;

              // total items 
              $items = $quantity + $items;
      ?>
      <ul class="list-unstyled row cart-view-row">
        <li class="col-3 col-md-2">
          <img src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" alt="" class="img-fluid" width="100" height="100">
        </li>
        <li class="col-9 col-md-5">
          <h4><a href="itemView.php?item=<?php echo $item_id; ?>&category=<?php echo $category_name; ?>" id="viewBtn"><?php echo $item_name; ?></a></h4>
          <p>Sale Price: <b class="text-danger"><span>&#8377</span> <?php echo $sale_price; ?></b> <s><span>&#8377</span> <?php echo $price; ?></s></p>
          <p>Discount: <?php echo $discount; ?> <span>&#37</span></p>
        </li>
        <li class="col-4 col-md-2">
          <form action="quantityChangeInCart.php" method="post" role="form" enctype="multipart/form-data" class="form-inline" id="QuantityChangeCartForm">
            <?php if($quantity > "3") { ?>
              <input type="text" name="quantity" id="quantity" style="width: 60px;" onchange="this.form.submit()"  value="<?php echo $row['quantity']; ?>"/>
            <?php }if($quantity <= "3") { ?>
            <select class="custom-select mb-2 mr-sm-2 mb-sm-0" name="quantity" id="quantity" onchange="this.form.submit()">
              <option value="1" <?php if($quantity== "1") echo "selected"; ?>>1</option>
              <option value="2" <?php if($quantity== "2") echo "selected"; ?>>2</option>
              <option value="3" <?php if($quantity== "3") echo "selected"; ?>>3</option>
              <option value="4">3+</option>
            </select>
            <?php }; ?>
            <input type="hidden" value="<?php echo $row['item_id']; ?>" name="itemId">
            <input type="hidden" value="<?php echo $row['price']; ?>" name="price">
            <input type="hidden" value="<?php echo $row['discount']; ?>" name="discount">
            <input type="hidden" value="<?php echo $row['sale_price']; ?>" name="sale_price">
            <input type="submit" value="change_quantity" hidden="">
          </form>
        </li>
        <li class="col-5 col-md-2">
          <h4><span>&#8377</span> <?php echo $total_price; ?></h4>
        </li>
        <li class="col-3 col-md-1">
          <a href="?delete_id=<?php echo $row['item_id']; ?>" title="click for delete" onclick="return confirm('sure to delete ?')"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
        </li>
      </ul>

      <?php 
      }
      ?>
            <div class="row alert alert-success" role="alert">
              <div class="col-12 col-md-8">
                <h5>Subtotal (<?php echo $items; ?> items):  <span>&#8377</span> <?php echo $subtotal; ?></h5>
              </div>
              <div class="col-12 col-md-4">
                <a href="address_cart.php"><button class="btn btn-info">Proceed to Checkout</button></a>
              </div>
            </div>
      <?php 
        $_SESSION['subtotal'] = $subtotal;
        $_SESSION['units'] = $items;

        }else{ 
      ?>
      <ul class="text-center list-unstyled cart-view-row">
        <li class="col-12">
            <a href="#">No Item found in your cart</a>
        </li>
      </ul>

        <?php }session_write_close(); ?>
    </div>

    <footer class="">
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
    <script>
      $('#quantity').on('change', function(){
        var val = $(this).val();
        if(val === '4') {
          $("#QuantityChangeCartForm").find("#quantity").remove();
          $("#QuantityChangeCartForm").prepend('<input type="text" name="quantity" id="quantity" style="width: 60px;"/>');
        }
      });
    </script>
  </body>
</html>
