<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if($role != "admin"){
      header('Location: index.php?err=2');
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
      <a class="navbar-brand" href="adminHome.php">Dashboard</a>
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
      <div class="">
        <main class="" role="main">
          <nav class="breadcrumb">
            <a class="breadcrumb-item" href="adminHome.php">Dashboard</a>
            <span class="breadcrumb-item active">Add Item</span>
          </nav>
		  <div class="row justify-content-center">
		        <h1 class="col-12 col-md-8">Add Item</h1>
	            <?php if(!empty($_GET["msg"])) { ?> 
	                <div class="alert alert-success col-12 col-md-4"><?php echo $_GET["msg"]; ?></div>
	            <?php } ?>
          		<form action="addItemImage.php" id="addItemForm" method="post" class="col-11 col-md-8" role="form" enctype="multipart/form-data">
					  <div class="form-group">
					    <!-- <label for="name">Name</label> -->
					    <!-- <input type="text" class="form-control" id="name" name="name"  placeholder="Name"/> -->
					    <select class="form-control" name="category_id">
				          <?php
				          	require 'database-config.php';
							$msg = '';
							$q = 'SELECT category_id, category_name FROM categories ORDER BY category_id DESC';

							$query = $dbh->prepare($q);

							$query->execute(array(':category_id', ':category_name'));

							if($query->rowCount() > 0){
								while($row=$query->fetch(PDO::FETCH_ASSOC)){
									extract($row);
						  ?>
					      <option value="<?php echo $category_id; ?>"><?php echo $category_name; ?></option>    
						  <?php
								}
							}else{
						  ?>
					  	  <option>No Category Found!</option>
		        	      <?php }session_write_close(); ?>
					    </select>
					  </div>
					  <div class="form-group">
					    <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Item Name"/>
					  </div>
					  <div class="form-group">
					    <input type="file" class="form-control" id="image" name="images[]" multiple="multiple"/>
					  </div>
					  <div class="form-group">
					    <label for="stock">Stock</label>
					    <input type="text" class="form-control" name="stock" placeholder="Stock"/>
					  </div>
					  <div class="form-group">
					    <!-- <label for="price">Price</label> -->
					    <input type="text" class="form-control" id="price" name="price" placeholder="Price">
					  </div>
					  <div class="form-group">
					    <!-- <label for="discount">Discount</label> -->
					    <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount">
					  </div>
					  <input type="submit" name="add-item" value="Add new Item" class="btn btn-info">
			            <!-- <button type="submit" name="add-item" class="btn btn-info">Add new Item</button> -->
			            
	                    
				</form>
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