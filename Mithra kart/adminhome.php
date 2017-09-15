<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if($role!="admin"){
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
  <nav class="navbar-top navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="adminhome.php">Dashboard</a>
      <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="addItem.php" id="addItem">Add Item</a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="#"><?php echo $_SESSION['sess_firstname'];?><span class="sr-only">(current)</span></a>
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

    <div class="container-fluid">
      <div class="row">
        <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
          <ul class="nav nav-pills flex-column" id="categories">
            <?php
              require 'database-config.php';
              $msg = '';
              $q = 'SELECT category_id, category_name, discount FROM categories ORDER BY category_id DESC';

              $query = $dbh->prepare($q);

              $query->execute(array(':category_id', ':category_name', ':discount'));

              if($query->rowCount() > 0){
                while($row=$query->fetch(PDO::FETCH_ASSOC)){
                  extract($row);
            ?>
            <li class="nav-item">
              <a class="nav-link" href="adminHome.php?category_id=<?php echo $category_id; ?>&category_name=<?php echo $category_name; ?>" ><?php echo $category_name; ?>  <span class="badge badge-secondary"><?php echo $discount; ?> <span>&#37</span></span><span class="sr-only">(current)</span></a>
            </li>
            <?php
                }
              }else{
            ?>
            <li class="nav-item">
              <a class="nav-link" href="#">No category found</a>
            </li>
            <?php }session_write_close(); ?>
          </ul>
        </nav>

        <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
          <h1>Dashboard</h1>

          <section class="row text-center placeholders">
            <div class="col-6 col-sm-2 placeholder">
              <img src="images/sales.jpg" width="100" height="100" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4><a href="orders.php" id="salesDashboard">Orders</a></h4>
              <span class="text-muted">View Orders information</span>
            </div>
            <div class="col-6 col-sm-2 placeholder">
              <img src="images/sales.jpg" width="100" height="100" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4><a href="stocks.php" id="salesDashboard">Stocks</a></h4>
              <span class="text-muted">View Stock information</span>
            </div>
            <div class="col-6 col-sm-2 placeholder">
              <img src="images/customers.jpg" width="100" height="100" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4><a href="customers.php" id="customerDashboard">Customers</a></h4>
              <div class="text-muted">View Customer information</div>
            </div>
            <div class="col-6 col-sm-2 placeholder">
              <img src="images/banner.jpg" width="100" height="100" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4><a href="banner.php" id="bannerDashboard">Banners</a></h4>
              <span class="text-muted">View Banner details</span>
            </div>
            <div class="col-6 col-sm-2 placeholder">
              <img src="images/categories.jpg" width="100" height="100" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4><a href="categories.php" id="categoryDashboard">Categories</a></h4>
              <span class="text-muted">View Categories information</span>
            </div>
            <div class="col-6 col-sm-2 placeholder">
              <img src="images/sales.jpg" width="100" height="100" class="img-fluid rounded-circle" alt="Generic placeholder thumbnail">
              <h4><a href="sales.php" id="salesDashboard">Sales</a></h4>
              <span class="text-muted">View Sales information</span>
            </div>
          </section>
          <?php
                
                if (isset($_GET['category_id']) and isset($_GET['category_name'])) {
                $categoryId = $_GET['category_id'];
                $categoryName = $_GET['category_name'];
              
          ?>
          <h2><?php echo $category_name; ?></h2>
          <div class="main-row">
            <ul class="row text-center list-unstyled">
              <?php
                
                require 'database-config.php';
                $msg = '';
                $q = "SELECT item_table.item_id, item_table.item_name, item_table.stock, item_table.price, item_table.discount, item_table.sale_price, images.image FROM item_table INNER JOIN images ON item_table.item_id=images.item_id WHERE item_table.category_id=".$categoryId." AND images.image_number = '0'";

                $query = $dbh->prepare($q);

                $query->execute(array(':item_id', ':item_name', ':stock', ':price', ':discount', ':sale_price', ':image'));

                if($query->rowCount() > 0){
                  while($row=$query->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
              ?>
              <li class="img-size col-6 col-md-2">
                <img src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" alt="" class="img-fluid">
                <a href="#" id="viewBtn"><?php echo $item_name; ?></a>
                <a href="edit_item.php?edit_id=<?php echo $row['item_id']; ?>" title="click for edit" onclick="return confirm('sure to edit? It will affect the item data!!')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
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
        </main>
      </div>
    </div>
    
    <!-- JavaScripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script type="text/javascript">
      if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
    var msViewportStyle = document.createElement('style')
    msViewportStyle.appendChild(
      document.createTextNode(
        '@-ms-viewport{width:auto!important}'
      )
    )
    document.head.appendChild(msViewportStyle)
  }

    </script>
</body>
</html>