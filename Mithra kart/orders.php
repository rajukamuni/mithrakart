<?php 
    session_start();
    $role = $_SESSION['sess_userrole'];
    if($role != "admin"){
      header('Location: no_access.php');
    }
?>
<?php

	require_once 'database-config.php';
	
	if(isset($_GET['delete_id']))
	{

		// it will insert this record to sales
		$stmt_sales = $dbh->prepare("INSERT INTO `sales` SELECT * FROM `orders` WHERE id =:id");
		$stmt_sales->bindParam(':id',$_GET['delete_id']);
		$stmt_sales->execute();
		if (!empty($stmt_sales)) {
			$message = "Item added to sales!";
		}else{
			$message = "Couldn't add this item to sales!";
		}

		// select row from orders to delete
		$stmt_select = $dbh->prepare('SELECT item_id FROM orders WHERE id =:id');
		$stmt_select->execute(array(':id'=>$_GET['delete_id']));
		$imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
		// unlink("user_images/".$imgRow['userPic']);
		
		// it will delete an actual record from orders
		$stmt_delete = $dbh->prepare('DELETE FROM orders WHERE id =:id');
		$stmt_delete->bindParam(':id',$_GET['delete_id']);
		$stmt_delete->execute();

		
		
		header("Location: orders.php");
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
        	<div class="row">
	          <nav class="breadcrumb col-12 col-md-8">
	            <a class="breadcrumb-item" href="adminHome.php">Dashboard</a>
	            <span class="breadcrumb-item active">Orders</span>
	          </nav>
              <button class="btn btn-primary col-2 col-md-4" id="exportBtn">Export</button>
	      	</div>
          <div class="">
			<table class="table table-hover" id="ordersTable">
			  	<thead>
			    <tr>
			      <th>#</th>
			      <th>Item ID</th>
			      <th>Item Name</th>
			      <th>Stock</th>
			      <th>Sale Price</th>
			      <th>Image</th>
			      <th>Customer</th>
			      <th>Customer ID</th>
			      <th>Email</th>
			      <th>Full Name</th>
			      <th>Mobile</th>
			      <th>Pin Code</th>
			      <th>House no</th>
			      <th>colony</th>
			      <th>landmark</th>
			      <th>city</th>
			      <th>units</th>
			      <th>Amount</th>
			      <th>Delivered</th>
			    </tr>
			  </thead>
			  <tbody>
		        <?php
		          require 'database-config.php';
					$msg = '';
					$q = "SELECT orders.id, item_table.item_id, item_table.item_name, item_table.stock,item_table.sale_price, images.image, orders.customer, orders.customer_id, orders.quantity, orders.amount, address.email, address.full_name, address.delivery_mobile, address.pincode, address.house_no, address.colony, address.landmark, address.city FROM item_table INNER JOIN images INNER JOIN orders INNER JOIN address ON item_table.item_id=images.item_id AND item_table.item_id=orders.item_id AND orders.customer_id = address.customer_id WHERE images.image_number = '0'";

					$query = $dbh->prepare($q);

					$query->execute(array(':id', ':item_id', ':item_name', ':stock', ':sale_price', ':image', ':customer', ':customer_id', ':quantity', ':amount', ':email',':full_name', ':delivery_mobile', ':pincode', ':house_no', ':colony', ':landmark', ':city'));

					if($query->rowCount() > 0){
						while($row=$query->fetch(PDO::FETCH_ASSOC)){
							extract($row);
				?>
			    <tr>
			      <td><?php echo $id; ?></td>
			      <td><?php echo $item_id; ?></td>
			      <td><?php echo $item_name; ?></td>
			      <td><?php echo $stock; ?></td>
			      <td><span>&#8377</span> <?php echo $sale_price; ?></td>
			      <td><img src="data:image/jpeg;base64,<?php echo base64_encode($image); ?>" alt="" class="img-fluid" width="100" height="100"></td>
			      <td><?php echo $customer; ?></td>
			      <td><?php echo $customer_id; ?></td>
			      <td><?php echo $email; ?></td>
			      <td><?php echo $full_name; ?></td>
			      <td><?php echo $delivery_mobile; ?></td>
			      <td><?php echo $pincode; ?></td>
			      <td><?php echo $house_no; ?></td>
			      <td><?php echo $colony; ?></td>
			      <td><?php echo $landmark; ?></td>
			      <td><?php echo $city; ?></td>
			      <td><?php echo $quantity; ?></td>
			      <td><span>&#8377</span> <?php echo $amount; ?></td>
			      <td><a href="?delete_id=<?php echo $row['id']; ?>" title="click when it delivered" onclick="return confirm('Are you sure? is it delivered ?')"><i class="fa fa-truck" aria-hidden="true"></i></i></a></td>
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

        	<?php }session_write_close(); ?>
			
		</div>
        </main>
      </div>
    </div>

	<!-- JavaScripts -->
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
		<script src="js/main.js"></script>
		<script src="js/libs/jquery.table2excel.js"></script>
		<script>
			$("#exportBtn").click(function(){
				$("#ordersTable").table2excel({
					// exclude: ".noExl",
					name: "Orders",
					filename: "orders" //do not include extension
				});
			});
		</script>
</body>
</html>