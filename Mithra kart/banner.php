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
		// select image from db to delete
		$stmt_select = $dbh->prepare('SELECT image FROM banner WHERE id =:id');
		$stmt_select->execute(array(':id'=>$_GET['delete_id']));
		$imgRow=$stmt_select->fetch(PDO::FETCH_ASSOC);
		// unlink("user_images/".$imgRow['userPic']);
		
		// it will delete an actual record from db
		$stmt_delete = $dbh->prepare('DELETE FROM banner WHERE iD =:id');
		$stmt_delete->bindParam(':id',$_GET['delete_id']);
		$stmt_delete->execute();
		
		header("Location: banner.php");
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
            <span class="breadcrumb-item active">Banners</span>
          </nav>
          <div class="">
			<table class="table table-hover">
			  	<thead>
			    <tr>
			      <th>#</th>
			      <th>Banner Name</th>
			      <th>Action</th>
			    </tr>
			  </thead>
			  <tbody>
		        <?php
		          require 'database-config.php';
					$q = 'SELECT id, name, image FROM banner ORDER BY id DESC';

					$query = $dbh->prepare($q);

					$query->execute(array(':name', ':image'));

					if($query->rowCount() > 0){
						while($row=$query->fetch(PDO::FETCH_ASSOC)){
							extract($row);
				?>
			    <tr>
			      <th scope="row"><?php echo $id; ?></th>
			      <td><?php echo $name; ?></td>
			      <th><a href="?delete_id=<?php echo $row['id']; ?>" title="click for delete" onclick="return confirm('sure to delete ?')"><i class="fa fa-trash-o" aria-hidden="true"></i></a></th>
			    </tr>    
				<?php
						}
					}else{
				?>
			  </tbody>
			</table>  
	        <div class="col-xs-12">
	        	<div class="alert alert-warning">
	            	<span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Banner. Upload here!! ...
	            </div>
	        </div>

        	<?php }session_write_close(); ?>
			
		</div>
			<div class="">
				<?php
					if($_SERVER['REQUEST_METHOD']=='POST'){
					    $image = $_FILES['image']['tmp_name'];
					    $img = file_get_contents($image);
					    $con = mysqli_connect('localhost','root','','mithrakart_db') or die('Unable To connect');
					    $sql = "insert into banner (name,image) values('" . $_POST["bannerName"] . "',?)";
					 
					    $stmt = mysqli_prepare($con,$sql);
					 
					    mysqli_stmt_bind_param($stmt, "s",$img);
					    mysqli_stmt_execute($stmt);
					 
					    $check = mysqli_stmt_affected_rows($stmt);
					    if($check==1){
					        $msg = 'Successfullly Uploaded';
					    }else{
					        $msg = 'Could not upload';
					    }
					    mysqli_close($con);
					    header("Location: banner.php");
					}
					?>
					<form action="banner.php" method="post" enctype="multipart/form-data">
					  <h4>Add New Banner</h4>
					  <div class="form-group">
					    <input type="text" class="form-control" id="bannerName" name="bannerName"  placeholder="Banner Name" value="<?php if(isset($_POST['bannerName'])) echo $_POST['bannerName']; ?>"/>
					  </div>
					  <div class="form-group">
					    <input type="file" class="form-control" id="banner" name="image"/>
					  </div>
					  <button type="submit" class="btn btn-info">Add Banner</button>
					</form>
					<?php if(isset($msg)) { ?>
					    <div class="alert alert-success col-12 text-center"><?php echo $msg; ?></div>
					<?php } ?>
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