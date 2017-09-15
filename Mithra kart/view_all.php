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
          <a class="navbar-brand" href="index.php"><img src="images/logo 4.png"></a>
          <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <div class="navbar-nav mr-auto"></div>
            <form class="form-inline mt-2 mt-md-0">
              <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
              <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="log_in.php">
                  <button class="btn btn-outline-success my-2 my-sm-0" type="button">Log in</button>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="register_user.php">
                  <button class="btn btn-outline-success my-2 my-sm-0" type="button">Register</button>
                </a>
              </li>
            </ul>
          </div>
        </nav>
    </header>
    <div class="main-wrapper">
          <?php
            if (isset($_GET['category'])) {
                $category_id = $_GET['category'];
                $category_name = $_GET['category_name'];
          ?>
          <h2 class="col-12"><?php echo $category_name; ?></h2>
          <div class="main-row">
            <ul class="row text-center list-unstyled" id="viewAllItems">
              <?php
                require 'database-config.php';
                $msg = '';
                $q = "SELECT item_table.item_id, item_table.item_name, item_table.stock, item_table.price, item_table.discount, item_table.sale_price, images.image FROM item_table INNER JOIN images ON item_table.item_id=images.item_id WHERE item_table.category_id=".$category_id." AND images.image_number = '0'";

                $query = $dbh->prepare($q);

                $query->execute(array(':item_id', ':item_name', ':stock', ':price', ':discount', ':sale_price', ':image'));

                if($query->rowCount() >= 0){
                  while($row=$query->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
              ?>
              <li class="img-size col-6 col-md-2">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" alt="" class="img-fluid">
                <a href="product_view.php?item=<?php echo $item_id; ?>"><b><?php echo $item_name; ?></b></a>
                <p val="<?php echo $price; ?>"><b class="text-danger"><span>&#8377</span> <?php echo $sale_price; ?></b> <s><span>&#8377</span> <?php echo $price; ?></s></p>
              </li>
              <?php
                  }
                }else{
              ?>
              <li class="col-6 col-md-2">
                <a href="#">No item found</a>
              </li>
              <?php }session_write_close();} ?>
            </ul>
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
