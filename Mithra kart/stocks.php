<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if($role != "admin"){
      header('Location: no_access.php');
    }
?>
<?php

  require_once 'database-config.php';
  
  if(isset($_GET['delete_id']) && isset($_GET['category_id']))
  {
    // select image from db to delete
    $category = $_GET['category_id'];
    
    // it will delete an actual record from images

    $stmt_delete_two = $dbh->prepare('DELETE FROM images WHERE item_id = :id');
    $stmt_delete_two->bindParam(':id',$_GET['delete_id']);
    $stmt_delete_two->execute();

    // it will delete an actual record from item_table
    $stmt_delete_one = $dbh->prepare('DELETE FROM item_table WHERE item_id = :id');
    $stmt_delete_one->bindParam(':id',$_GET['delete_id']);
    $stmt_delete_one->execute();
    
    header("Location: stocks.php?category=".$category."");
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
      <a class="navbar-brand" href="#">Dashboard</a>
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
          <ul class="nav nav-pills flex-column">
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
              <a class="nav-link" href="stocks.php?category_id=<?php echo $category_id; ?>&category_name=<?php echo $category_name; ?>" ><?php echo $category_name; ?>  <span class="badge badge-secondary"><?php echo $discount; ?> <span>&#37</span></span><span class="sr-only">(current)</span></a>
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
        	<div class="row">
	          <nav class="breadcrumb col-12 col-md-8">
	            <a class="breadcrumb-item" href="adminHome.php">Dashboard</a>
	            <span class="breadcrumb-item active">stocks</span>
	          </nav>
	      	</div>
          <div class="">
          <?php
                
                if (isset($_GET['category_id']) and isset($_GET['category_name'])) {
                $categoryId = $_GET['category_id'];
                $categoryName = $_GET['category_name'];
              
          ?>
          	<h2><?php echo $categoryName; ?></h2>
			<table class="table table-hover">
			  	<thead>
			    <tr>
			      <th>Item ID</th>
            <th>Category ID</th>
			      <th>Item Name</th>
            <th>Image</th>
			      <th>Stock</th>
			      <th>Price</th>
			      <th>Discount</th>
            <th>Sale price</th>
            <th>Action</th>
			    </tr>
			  </thead>
			  <tbody>
              <?php
                
                require 'database-config.php';
                $msg = '';
                $q = "SELECT item_table.item_id, item_table.category_id, item_table.item_name, item_table.stock, item_table.price, item_table.discount, item_table.sale_price, images.image FROM item_table INNER JOIN images ON item_table.item_id=images.item_id WHERE item_table.category_id=".$categoryId." AND images.image_number = '0'";

                $query = $dbh->prepare($q);

                $query->execute(array(':item_id', ':category_id',':item_name', ':stock', ':price', ':discount', ':sale_price', ':image'));

                if($query->rowCount() >= 0){
                  while($row=$query->fetch(PDO::FETCH_ASSOC)){
                    extract($row);
              ?>
			    <tr>
			      <td><?php echo $item_id; ?></td>
            <td><?php echo $category_id; ?></td>
			      <td><?php echo $item_name; ?></td>
            <td>
              <img src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" alt="" class="img-fluid"
               height="80" width="80">
            </td>
			      <td><?php echo $stock; ?></td>
			      <td><?php echo $price; ?></td>
			      <td><?php echo $discount; ?></td>
            <td><?php echo $sale_price; ?></td>
			      <td>
              <a href="edit_item.php?edit_id=<?php echo $row['item_id']; ?>" title="click for edit" onclick="return confirm('sure to edit? It will affect the item data!!')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
              <a href="?delete_id=<?php echo $row['item_id']; ?>&category_id=<?php echo $row['category_id']; ?>" title="click for delete" onclick="return confirm('sure to delete ?')"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
			    </tr>
              <?php
                  }
                }else{
              ?>
			  </tbody>
			</table>  
	        <div class="col-xs-12">
	        	<div class="alert alert-warning">
	            	<span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Data Found ...
	            </div>
	        </div>

        	<?php }session_write_close(); }?>
        	</div>
        </main>
      </div>
    </div>
    
    <!-- JavaScripts -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
</body>
</html>