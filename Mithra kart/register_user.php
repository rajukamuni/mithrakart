<?php
if(count($_POST)>0) {
  /* Form Required Field Validation */
  foreach($_POST as $key=>$value) {
  if(empty($_POST[$key])) {
  $message = ucwords($key) . " field is required";
  break;
  }
  }
  /* Password Matching Validation */
  if($_POST['password'] != $_POST['confirm_password']){ 
  $message = 'Passwords should be same<br>'; 
  }

  /* Email Validation */
  if(!isset($message)) {
  if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
  $message = "Invalid Email";
  }
  }

  /* Validation to check if Terms and Conditions are accepted */
  if(!isset($message)) {
  if(!isset($_POST["terms"])) {
  $message = "Accept Terms and conditions before submit";
  }
  }

  if(!isset($message)) {
    require_once("dbcontroller.php");
    $db_handle = new DBController();
    $query = "SELECT * FROM registered_users where email = '" . $_POST["email"] . "'";
    $count = $db_handle->numRows($query);
    
    if($count==0) {
      $query = "INSERT INTO registered_users (first_name, last_name, mobile, password, email) VALUES
      ('" . $_POST["firstName"] . "', '" . $_POST["lastName"] . "', '" . $_POST["mobile"] . "', '" . hash('sha256',$_POST["password"]) . "', '" . $_POST["email"] . "')";
      $current_id = $db_handle->insertQueryEmail($query);
      if(!empty($current_id)) {
        $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"."activate.php?id=" . $current_id;
        $toEmail = $_POST["email"];
        $subject = "User Registration Activation Email";
        $content = "Click this link to activate your account. <a href='" . $actual_link . "'>" . $actual_link . "</a>";
        $mailHeaders = "From: Admin\r\n";
        if(mail($toEmail, $subject, $content, $mailHeaders)) {
          $message = "You have registered and the activation mail is sent to your email. Click the activation link to activate you account."; 
        }
        unset($_POST);
      } else {
        $message = "Problem in registration. Try Again!"; 
      }
    } else {
      $message = "Your Email is already in use."; 
    }
  }
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
    <div class="main-wrapper row justify-content-center align-items-center">
    <?php if(isset($message)) { ?>
    <div class="alert alert-success col-12 text-center"><?php echo $message; ?></div>
    <?php } ?>
      <form action="" id="registerForm" method="POST" class="col-10 col-md-4" role="form">
        <h4 class="text-center col-12">Register</h4>
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
                    <input type="password" class="form-control" name="password" placeholder="Password" >
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
            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>" />
            <input type="hidden" name="item_name" value="<?php echo $item_name; ?>" />
            <input type="hidden" name="category_id" value="<?php echo $category_id; ?>" />
            <input type="hidden" name="price" value="<?php echo $price; ?>" />
            <input type="hidden" name="discount" value="<?php echo $discount; ?>" />
            <input type="hidden" name="sale_price" value="<?php echo $sale_price; ?>" />

            <input type="submit" name="register-user" value="Register" class="btn btn-info">
                  <!-- <button type="submit" name="register-user" class="btn btn-info">Register</button> -->
        </form>
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
