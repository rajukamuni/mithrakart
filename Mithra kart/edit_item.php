<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if($role != "admin"){
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
            <a class="breadcrumb-item" href="stocks.php">Stocks</a>
            <span class="breadcrumb-item active">Edit Item</span>
          </nav>
		  <div class="">
			<?php
			if(!empty($_GET["edit_id"])) {
				if(!empty($_POST["edit-item"])) {
					/* Form Required Field Validation */
					foreach($_POST as $key=>$value) {
						if(empty($_POST[$key])) {
						$error_message = "All Fields are required";
						break;
						}
					}

					if(!isset($error_message)) {
						require_once("dbcontroller.php");
						$db_handle = new DBController();
						 
						// discount calculation
						$discount = $_POST["discount"];
						$price = $_POST["price"];
				        $discount_in_rupees = ($discount/100) * $price ;
				        $salePrice = $price - $discount_in_rupees ;

						$query = "UPDATE `item_table` SET `category_id`='" . $_POST["category_id"] . "',`item_name`='" . $_POST["item_name"] . "',`stock`='" . $_POST["stock"] . "',`price`='" . $_POST["price"] . "',`discount`= '" . $_POST["discount"] . "',`sale_price`= '" . $salePrice . "' WHERE item_id='".$_GET['edit_id']."'";

						$result = $db_handle->insertQuery($query);

						if(!empty($result)) {
							if (count($_FILES['images']['name'])) {
								$query_delete_images = "DELETE FROM images WHERE item_id='".$_GET['edit_id']."'";

								$result_delete_images = $db_handle->insertQuery($query_delete_images);
								$con = mysqli_connect('localhost','root','','mithrakart_db') or die('Unable To connect');
								for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
									//Get the temp file path
									$image = $_FILES['images']['tmp_name'][$i];
									$img = file_get_contents($image);
									$sqlImage = "INSERT INTO images (item_id, image, image_number) VALUES
												('".$_GET['edit_id']."', ?, '".$i."')";
									$stmtImage = mysqli_prepare($con,$sqlImage);
										 
									mysqli_stmt_bind_param($stmtImage, "s",$img);
									mysqli_stmt_execute($stmtImage);
												 
									$checkImage = mysqli_stmt_affected_rows($stmtImage);
									if(!empty($checkImage)){
										$msg = 'Updated Successfully. Images changed!';
										header('Location: stocks.php?category_id="'.$_POST["category_id"].'"');
									}else{
										$msg = 'Could not upload images';
									}
								}
							}else{
								$msg = 'Updated Successfully';
								header('Location: stocks.php?category_id="'.$_POST["category_id"].'"');
							}
						} else {
							$error_message = "Problem in Item updating. Try Again!";	
						}
					}
				}
				?>
		            <?php if(!empty($success_message)) { ?> 
                    <div class="success-message"><?php if(isset($success_message)) echo $success_message; ?></div>
                    <?php } ?>
                    <?php if(!empty($error_message)) { ?> 
                    <div class="error-message"><?php if(isset($error_message)) echo $error_message; ?></div>
                    <?php } ?>
                <?php
				          	require 'database-config.php';
							$q = "SELECT item_id, item_name,category_id,  stock, price, discount, sale_price FROM item_table WHERE item_id='".$_GET['edit_id']."'";

							$query = $dbh->prepare($q);

							$query->execute(array(':item_id', ':item_name', ':category_id', ':stock', ':price', ':discount', ':sale_price'));

							if($query->rowCount() > 0){
								while($row=$query->fetch(PDO::FETCH_ASSOC)){
									extract($row);
						  ?>
				<?php if(isset($msg)) { ?>
				    <div class="alert alert-success col-12 text-center"><?php echo $msg; ?></div>
				<?php } ?>
                <form action="" id="editItemForm" method="post" class="col-11 col-md-8" role="form" enctype="multipart/form-data">
					  <div class="form-group">
					    <select class="form-control" name="category_id"">
				          <?php
				          	require 'database-config.php';
							$q = 'SELECT category_id, category_name FROM categories ORDER BY category_id DESC';

							$query = $dbh->prepare($q);

							$query->execute(array(':category_id', ':category_name'));

							if($query->rowCount() >= 0){
								while($row=$query->fetch(PDO::FETCH_ASSOC)){
									extract($row);
						  ?>
					      <option value="<?php echo $category_id; ?> <?php if($category_id== $row['category_id']) echo "selected"; ?>"><?php echo $category_name; ?></option>    
						  <?php
								}
							}else{
						  ?>
					  	  <option>No Category Found!</option>
		        	      <?php }session_write_close(); ?>
					    </select>
					  </div>
					  <div class="form-group">
					    <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Item Name" value="<?php echo $item_name; ?>" />
					  </div>

					  <ul class="list-inline row">
					  <?php
				          	require 'database-config.php';
							$q = "SELECT image FROM images WHERE item_id='".$_GET['edit_id']."'";

							$query = $dbh->prepare($q);

							$query->execute(array(':image'));

							if($query->rowCount() > 0){
								while($row=$query->fetch(PDO::FETCH_ASSOC)){
									extract($row);
						  ?>
						<li class="col">
						    <img src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" alt="" class="img-fluid" height="80" width="80">
					    </li>
					  <?php 
				          	}
				          ?>
					  </ul>
					  <?php 
				          }else{
				          ?>
					    <div class="col-xs-12">
				        	<div class="alert alert-warning">
				            	<span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Image Found. Upload!!
				            </div>
				        </div>

			        	<?php }session_write_close();
			        		?>
					  <div class="form-group">
					    <input type="file" class="form-control" id="image" name="images[]" multiple="multiple"/>
					  </div>
					  <div class="form-group">
					    <label for="stock">Stock</label>
					    <input type="text" class="form-control" id="stock" name="stock" placeholder="Stock" value="<?php echo $stock; ?>"/>
					  </div>
					  <div class="form-group">
					    <input type="text" class="form-control" id="price" name="price" placeholder="Price" value="<?php echo $price; ?>">
					  </div>
					  <div class="form-group">
					    <input type="text" class="form-control" id="discount" name="discount" placeholder="Discount" value="<?php echo $discount; ?>">
					  </div>
					  <input type="submit" name="edit-item" value="Update Item" class="btn btn-info">
	                    
				</form>
		          <?php 
		          	}
		          }else{
		          ?>
		    <div class="col-xs-12">
	        	<div class="alert alert-warning">
	            	<span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Item Found. Try Again!!
	            </div>
	        </div>

        	<?php }session_write_close(); 
        	}else{
        		?>
        	<div class="col-xs-12">
	        	<div class="alert alert-warning">
	            	<span class="glyphicon glyphicon-info-sign"></span> &nbsp; Try Again!
	            </div>
	        </div>
			</div>
            <?php }session_write_close(); ?>
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