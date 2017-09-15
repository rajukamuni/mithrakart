<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if($role != "user"){
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
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner" id="bannerItems">
        <?php
                
                require 'database-config.php';
                $msg = '';
                $q = "SELECT id, name, image FROM banner ORDER BY id DESC";

                $query = $dbh->prepare($q);

                $query->execute(array(':name', ':image'));

                if($query->rowCount() >= 0){
                  while($row=$query->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
              ?>
        <div class="carousel-item">
          <img class="d-block w-100" src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" alt="First slide">
        </div>
        <?php
                  }
                }else{
              ?>
        <div class="row">
          <p>No image available</p>
        </div>
        <?php }session_write_close(); ?>
      <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
    </div>
    <div class="main-wrapper">
        <?php
              require 'database-config.php';
          $msg = '';
          $qCategory = 'SELECT category_id, category_name, discount FROM categories ORDER BY category_id DESC';

          $queryCategory = $dbh->prepare($qCategory);

          $queryCategory->execute(array(':category_id', ':category_name', ':discount'));

          if($queryCategory->rowCount() > 0){
            while($row=$queryCategory->fetch(PDO::FETCH_ASSOC)){
              extract($row);
        ?>
      <ul class="text-center row list-unstyled main-row" id="categoryItems">
        <li class="col-12 col-md-2 mobile-view-row">
          <h1 class="row-head"><?php echo $category_name; ?></h1>
          <a href="viewAll.php?category=<?php echo $category_id; ?>&category_name=<?php echo $category_name; ?>" id="viewAllBtn"><button type="button" class="btn btn-primary view-all-btn-sr">View all</button></a>
        </li>
        <?php
                
                require 'database-config.php';
                $msg = '';
                $q = "SELECT item_table.item_id, item_table.item_name, item_table.stock, item_table.price, item_table.discount, item_table.sale_price, images.image FROM item_table INNER JOIN images ON item_table.item_id=images.item_id WHERE item_table.category_id=".$category_id." AND images.image_number = '0'";

                $query = $dbh->prepare($q);

                $query->execute(array(':item_id', ':item_name', ':stock', ':price', ':discount', ':sale_price', ':image'));

                if($query->rowCount() > 0){
                  $counter = 0;
                  $max = 5;
                  while($row=$query->fetch(PDO::FETCH_ASSOC) and ($counter < $max)){
                    extract($row);
              ?>
              <li class="img-size col-6 col-md-2">
                <a href="itemView.php?item=<?php echo $item_id; ?>&category=<?php echo $category_name; ?>"><img src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" alt="" class="img-fluid" width="200" height="200"></a>
                <a href="itemView.php?item=<?php echo $item_id; ?>" id="viewBtn"><b><?php echo $item_name; ?></b></a>
                <p val="<?php echo $price; ?>"><b class="text-danger"><span>&#8377</span> <?php echo $sale_price; ?></b> <s><span>&#8377</span> <?php echo $price; ?></s></p>
              </li>
              <?php
                  $counter++;
                }
              ?>
            <?php
                }else{
              ?>
                <li class="col-12">
                  <a href="#">No Item found in this</a>
                </li>
              </ul>
              <?php }session_write_close(); ?>
              <li class="col-6 col-md-2 mobile-view-row">
                <a href="viewAll.php?category=<?php echo $category_id; ?>&category_name=<?php echo $category_name; ?>"><button type="button" class="btn btn-sm btn-primary view-all-btn-mobile">View all</button></a>
              </li>
            </ul>
        <?php
            } 
          }else{
        ?>
        
          <li class="col-12">
            <a href="#">No Category found</a>
          </li>
          <?php }session_write_close(); ?>
        
        
        
      </ul>
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
