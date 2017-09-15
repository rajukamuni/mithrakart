<!DOCTYPE html>
<html>
  <head>
        <!-- Content Type Meta tag -->
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <!--Responsive Viewport Meta tag-->
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        
        <meta name="google-signin-scope" content="profile email">

      <meta name="google-signin-client_id" content="mithrakart-178310.apps.googleusercontent.com">
    
    <title>Shopping cart app</title>
        
    <!-- Roboto font embed -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="icon" href="images/fav3.jpg">
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
          <a href="view_all.php?category=<?php echo $category_id; ?>&category_name=<?php echo $category_name; ?>"><button type="button" class="btn btn-primary view-all-btn-sr">View all</button></a>
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
                <a href="product_view.php?item=<?php echo $item_id; ?>"><img src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" alt="" class="img-fluid" width="200" height="200"></a>
                <a href="product_view.php?item=<?php echo $item_id; ?>" id="viewBtn"><b><?php echo $item_name; ?></b></a>
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
          <a href="view_all.php?category=<?php echo $category_id; ?>&category_name=<?php echo $category_name; ?>"><button type="button" class="btn btn-sm btn-primary view-all-btn-mobile">View all</button></a>
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
    <div class="login-bg row justify-content-md-left justify-content-sm-left justify-content-left">
      <!-- <form class="login-view col-10 col-sm-6 col-md-4">
        <h3 class="text-center">Mithra Kart</h3>
        <div class="form-group">
          <input type="email" class="form-control" id="email" name="email"  placeholder="Email"/>
        </div>
        <div class="form-group">
          <input type="password" class="form-control" id="password" placeholder="password">
        </div>
        <p><a href="forgotPassword.html">Can't access account?</a></p>
        <a href="../index.html" id="logIn"><button type="button" class="btn btn-info">Log In</button></a>
        <a href="signUp.html" id="logIn" class="pull-right"><button type="button" class="btn btn-info">Register</button></a>
      </form> -->

      <!-- log in modal -->
        <div class="modal fade" id="logInModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mithra Kart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form action="authenticate.php" id="logInForm" method="post" role="form">
                  <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Email" autofocus>
                  </div>
                  <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" >
                  </div>
                  
                <?php $errors = array(
                  1=>"Invalid user name or password, Try again",
                  2=>"Please login to access this area"
                  );

                  $error_id = isset($_GET['err']) ? (int)$_GET['err'] : 0;

                  if ($error_id == 1) {
                    echo '<p class="text-danger">'.$errors[$error_id].'</p>';
                  }elseif ($error_id == 2) {
                    echo '<p class="text-danger">'.$errors[$error_id].'</p>';
                  }
                ?>
                  <!-- <input type="submit" name="log-in" value="Log in" class="btn btn-info"> -->
                  <button class="btn btn-info" type="submit">Sign in</button>
                  <button type="button" class="pull-right btn btn-secondary" data-dismiss="modal">Close </button>
                </form>
              </div>
            </div>
          </div>
        </div>
    </div>
    <!-- log in modal closed -->
    <style>
      .error-message {
        padding: 7px 10px;
        background: #fff1f2;
        border: #ffd5da 1px solid;
        color: #d6001c;
        border-radius: 4px;
      }
      .success-message {
        padding: 7px 10px;
        background: #cae0c4;
        border: #c3d0b5 1px solid;
        color: #027506;
        border-radius: 4px;
      }
      .btnRegister {
        padding: 10px 30px;
        background-color: #3367b2;
        border: 0;
        color: #FFF;
        cursor: pointer;
        border-radius: 4px;
        margin-left: 10px;
      }
      </style>
    <!-- register modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mithra Kart</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <?php include 'register.php';?>
                <form action="" id="registerForm" method="POST" class="" role="form">
                  <div class="row">
                    <div class="form-group col">
                      <input type="text" class="form-control" id="firstname" name="firstName" value="<?php if(isset($_POST['firstName'])) echo $_POST['firstName']; ?>" placeholder="First Name"  autofocus/>
                    </div>
                    <div class="form-group col">
                      <input type="text" class="form-control" id="lastName" name="lastName" value="<?php if(isset($_POST['lastName'])) echo $_POST['lastName']; ?>" placeholder="Last Name" />
                    </div>
                  </div>
                  <div class="form-group">
                    <input type="mobile" class="form-control" id="mobile" name="mobile" value="<?php if(isset($_POST['mobile'])) echo $_POST['mobile']; ?>" placeholder="Mobile Number" />
                  </div>
                  <div class="form-group">
                    <input type="email" class="form-control" id="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" placeholder="Email" />
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control" name="passwordRegister" placeholder="password" >
                  </div>
                  <div class="form-group">
                    <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Confirm Password" >
                  </div>
                  <div class="form-check">
                    <label class="form-check-label">
                      <input type="checkbox" name="terms" class="form-check-input" >
                      I accept Terms and Conditions
                    </label>
                  </div>

                  <input type="submit" name="register-user" value="Register" class="btn btn-info">
                  <!-- <button type="submit" name="register-user" class="btn btn-info">Register</button> -->
                </form>
              </div>
            </div>
          </div>
        </div>
    </div>
    <!-- register modal closed -->

    
    <!-- JavaScripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
    <script>
      <?php 
          if (!empty($error_message)) {
      ?>
            $('#registerModal').modal('show');
      <?php
          }
      ?>
    </script>
    <script>
      <?php 
        $error = isset($_GET['err']) ? (int)$_GET['err'] : 0;
            if ($error == 1) {
      ?>
              $('#logInModal').modal('show');
      <?php 
            }elseif($error == 2){
      ?>
              $('#logInModal').modal('show');
      <?php
          }
      ?>
    </script>
  </body>
</html>