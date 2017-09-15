<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if($role!="user"){
      header('Location: no_access.php');
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
    
    <!-- <link rel="stylesheet" type="text/css" href="reset.css">
    <link rel="stylesheet" type="text/css" href="style.css"> -->
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
    <div class="main-row main-wrapper">
      <div class="row">
      	<?php
            if (isset($_GET['item'])) {
                $itemID = $_GET['item'];
          ?>
        <?php
          require 'database-config.php';
          $msg = '';
          $q = "SELECT item_id, category_id, item_name, stock, price, discount, sale_price FROM item_table WHERE item_id=".$itemID."";
          $query = $dbh->prepare($q);

          $query->execute(array(':item_id', ':category_id', ':item_name', ':stock', ':price', ':discount', ':sale_price'));

          if($query->rowCount() > 0){

          	$row = $query->fetch(PDO::FETCH_ASSOC);

              $item_name = $row['item_name'];
              $item_id = $row['item_id'];
              $price = $row['price'];
              $discount = $row['discount'];
              $stock = $row['stock'];
              $sale_price = $row['sale_price'];
              $category_id = $row['category_id'];
              // $main_img = $row['image'];

              $_SESSION['item_name'] = $item_name;
              $_SESSION['item_id'] = $item_id;
              $_SESSION['price'] = $price;
              $_SESSION['discount'] = $discount;
              $_SESSION['sale_price'] = $sale_price;

        ?>

        <div class="col-4 col-md-1">
            <ul class="list-inline list-unstyled row" id="thumbImages">
            <?php 
              $qImage = "SELECT image FROM images WHERE item_id = ".$itemID."";
              $queryImage = $dbh->prepare($qImage);
              $queryImage->execute(array(':image'));
              if($queryImage->rowCount() > 0){
                while($row=$queryImage->fetch(PDO::FETCH_ASSOC)){
                  extract($row); 
            ?>
              <li class="col-12">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" alt="Image" class="img-thumbnail img-fluid rounded mx-auto d-block" width="50" height="50" onclick="changeImage(this);">
              </li>
              <?php 
                }
              }else{ ?>
                <li class="col-12"><p>No image found !!</p></li>
                <?php 
              }
              ?>
            </ul>
        </div>
        <div class="col-8 col-md-3">
          <div class="row">
            <img src="" alt="No Image" class="img-fluid rounded d-block" id="mainImage">
          </div><br>
        </div>
        <div class="col-6 col-md-4">
          <h4><?php echo $item_name; ?></h4>
          <div class="row">
            <p class="col">Price<span class="col">: <s><span>&#8377</span> <?php echo $price; ?></s></span></p>
          </div>
          <div class="row">
            <p class="col">Discount<span class="col">: <?php echo $discount; ?> <span>&#37</span></p></span>
          </div>
          <div class="row">
            <p class="col">Sale<span class="col">: <b class="text-danger"><span>&#8377</span> <?php echo $sale_price; ?></b></span></p>
          </div>
        </div>
        <div class="col-6 col-md-4">
          <form action="add_to_cart.php" method="post" role="form" enctype="multipart/form-data" class="" id="addToCartForm">
            <input type="hidden" name="action" value="submit" />
            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>" />
            <input type="hidden" name="item_name" value="<?php echo $item_name; ?>" />
            <input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
            <input type="hidden" name="price" value="<?php echo $price; ?>" />
            <input type="hidden" name="discount" value="<?php echo $discount; ?>" />
            <input type="hidden" name="sale_price" value="<?php echo $sale_price; ?>" />
            <div id="quantitySelectBox">
              <label class="mr-sm-2" for="quantity">Quantity</label>
              <select class="custom-select mb-2 mr-sm-2 mb-sm-0" name="quantity" id="quantity">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">3+</option>
              </select>
            </div>
            <br>
            <div class="row">
              <input id="add-to-cart" type="submit" class="btn btn-info col-10" name="submit" value="Add to cart">
            </div><br>
            <div class="row">
              <input id="buy-now" type="submit" class="btn btn-info col-10" name="submit" value="Buy now">
            </div>
          </form>
          <br>
        </div>
        </div>
          <div class="row">
            <h5 class="col-12 col-md-4"></h5>
            <p class="col-12 col-md-4"><b>Description:</b><br>ffdgfdgfdgfdgfdgfdgdgghfgdghhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh</p>
            <div class="col-12 col-md-4"></div>
          </div>
        <?php 

        }else{ ?>

        <div class="col">
          <h3>No data found !!</h3>
        </div>

        <?php }session_write_close(); } ?>
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
    <script src="js/libs/jquery.elevatezoom.js" type="text/javascript"></script>
    <script>
      var activeThumbImg = $('#thumbImages').find("li :first");
      activeThumbImg.addClass("active-thumb-img");
      var activeThumbImgSource = activeThumbImg.prop("src");
      $("#mainImage").prop("src",activeThumbImgSource);

      function changeImage(event){
        $('#thumbImages').find("li :first-child").removeClass("active-thumb-img");
        var imageSource = $(event).prop("src");
        $(event).addClass("active-thumb-img");
        $("#mainImage").prop("src",imageSource);
        // $("#mainImage").elevateZoom({
        // });
      }
      $('#quantity').on('change', function(){
        var val = $(this).val();
        if(val === '4') {
          $("#addToCartForm").find("#quantitySelectBox").remove();
          $("#addToCartForm").prepend('<label class="mr-sm-2" for="quantity">Quantity</label><input type="text" name="quantity" id="quantity" style="width: 60px;"/>');
        }
      });
    </script>
  </body>
</html>
