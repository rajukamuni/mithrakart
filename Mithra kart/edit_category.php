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
            <a class="breadcrumb-item" href="categories.php">Categories</a>
            <span class="breadcrumb-item active">Edit Category</span>
          </nav>
		  <div class="">
			<?php
			if(!empty($_GET["edit_id"])) {
				if(!empty($_POST["edit-category"])) {
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

						$query = "UPDATE `categories` SET `category_name`='" . $_POST["category_name"] . "',`discount`= '" . $_POST["discount"] . "' WHERE category_id='".$_GET['edit_id']."'";

						$result = $db_handle->insertQuery($query);

						$query_item_table = "UPDATE `item_table` SET `discount`= '" . $_POST["discount"] . "' WHERE category_id='".$_GET['edit_id']."'";

						$result_item_table = $db_handle->insertQuery($query_item_table);

						if(!empty($result) and !empty($result_item_table)) {
							$error_message = "";
							$success_message = "Category Updated successfully!";	
							unset($_POST);
							header('Location: categories.php');
						} else {
							$error_message = "Problem  Category updating. Try Again!";	
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
					$q = "SELECT category_id, category_name, discount FROM categories WHERE category_id='".$_GET['edit_id']."'";

					$query = $dbh->prepare($q);

					$query->execute(array(':category_id', ':category_name', ':discount'));

					if($query->rowCount() > 0){
						while($row=$query->fetch(PDO::FETCH_ASSOC)){
							extract($row);
				?>
                <form action="" id="editCategoryForm" method="POST" class="" role="form">
		            <div class="form-group">
		              <input type="text" class="form-control" id="categoryName" name="category_name" value="<?php echo $category_name; ?>" placeholder="Category Name">
		            </div>
		            <div class="form-group">
		              <input type="text" class="form-control" id="discount" name="discount" value="<?php echo $discount; ?>" placeholder="Discount">
		            </div>
		            <input type="submit" name="edit-category" value="Update Category" class="btn btn-info">
		          </form>
		          <?php 
		          	}
		          }else{
		          ?>
		    <div class="col-xs-12">
	        	<div class="alert alert-warning">
	            	<span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Category Found!
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