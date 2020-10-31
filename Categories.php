<?php
require_once 'Includes/DB.php';
require_once 'Includes/Functions.php';
require_once 'Includes/Sessions.php';
$_SESSION['TrackingURL'] = $_SERVER['PHP_SELF'];
Confirm_Login();
if (isset($_POST['Submit'])) {
	$Category = $_POST['CategoryTitle'];
	$Admin = $_SESSION['AdminName'];
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	$CurrenTime = time();
	$DateTime = strftime("%d-%m-%Y %H:%M:%S", $CurrenTime);
	if (empty($Category)) {
		$_SESSION['ErrorMessage'] = 'All fields must be filled out';
		Redirect_to('Categories.php');
	} else if (strlen($Category) < 3) {
		$_SESSION['ErrorMessage'] = 'Category title should be greater than 3 character';
		redirect_to('Categories.php');
	} else if (strlen($Category) > 49) {
		$_SESSION['ErrorMessage'] = 'Category title should be less than 50 character';
		Redirect_to('Categories.php');
	} else {
		global $ConnectingDB;
		$sql = 'INSERT INTO category(title, author, datetime) VALUES (:categoryName, :adminName, :dateTime)';
		$stmt = $ConnectingDB->prepare($sql);
		$stmt->bindValue(':categoryName', $Category);
		$stmt->bindValue(':adminName', $Admin);
		$stmt->bindValue(':dateTime', $DateTime);
		$Execute = $stmt->execute();
	} if ($Execute) {
		$_SESSION['SuccessMessage'] = 'Category with id : ' . $ConnectingDB->lastInsertid() . ' Added Successfully.';
		Redirect_to('Categories.php');
	} else {
		$_SESSION['ErrorMessage'] = 'Something went wrong. Try Again!';
		Redirect_to('Categories.php');
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" href="Css/Style.css">
	<title>Categories</title>
</head>
<body>
	<!-- NAVBAR START -->
	<div style="height: 10px; background: #27aae1"></div>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<div class="container">
			<a href="#" class="navbar-brand">TUANPHO.LIVE</a>
			<button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarcollapseCMS">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item"><a href="MyProfile.php" class="nav-link"><i class="fas fa-user text-success"></i> My Profile</a></li>
					<li class="nav-item"><a href="Dashboard.php" class="nav-link">Dashboard</a></li>
					<li class="nav-item"><a href="Posts.php" class="nav-link">Posts</a></li>
					<li class="nav-item"><a href="Categories.php" class="nav-link">Categories</a></li>
					<li class="nav-item"><a href="Admins.php" class="nav-link">Manage Admins</a></li>
					<li class="nav-item"><a href="Comments.php" class="nav-link">Comments</a></li>
					<li class="nav-item"><a href="Blog.php" class="nav-link">Live Blog</a></li>
				</ul>
				<ul class="navbar-nav ml-auto">
					<li class="nav-item"><a href="Logout.php" class="nav-link text-danger"><i class="fas fa-user-times"></i> Logout</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div style="height: 10px; background: #27aae1"></div>
	<!-- NAVBAR END -->
	<!-- HEADER -->
	<header class="bg-dark text-white py-3">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h1><i class="fas fa-edit"></i> Manage Categories</h1>
				</div>
			</div>
		</div>
	</header>
	<!-- HEADER -->
	<!-- Main Area -->
	<section class="container py-2 mb-4">
		<div class="row">
			<div class="offset-lg-1 col-lg-10" style="min-height: 400px;">
				<?php
				echo ErrorMessage();
				echo SuccessMessage();
				?>
				<form action="Categories.php" method="post">
					<div class="card bg-secondary text-light mb-3">
						<div class="card-header">
							<h1>Add New Category</h1>
						</div>
						<div class="card-boy bg-dark">
							<div class="form-group">
								<label for="title"><span class="FieldInfo">Category Title:</span></label>
								<input class="form-control" type="text" name="CategoryTitle" value="" id="title" placeholder="Type title here">
							</div>
							<div class="row">
								<div class="col-lg-6 mb-2">
									<a href="Dashboard.php" class="btn btn-warning btn-block"><i class="fas fa-arrow-left"></i> Back To Dashboard</a>
								</div>
								<div class="col-lg-6 mb-2">
									<button type="submit" class="btn btn-success btn-block" name="Submit">
										<i class="fas fa-check"></i> Publish
									</button>
								</div>
							</div>							
						</div>						
					</div>					
				</form>
				<h2>Existing Categories</h2>
				<table class="table table-striped table-hover">
					<thead class="thead-dark">
						<tr>
							<th>No. </th>
							<th>Date&Time</th>
							<th>Category Name</th>
							<th>Creator Name</th>
							<th>Action</th>
						</tr>
					</thead>
					<?php
					global $ConnectingDB;
					$sql = "SELECT * FROM category ORDER BY id DESC";
					$Execute  = $ConnectingDB->query($sql);
					$SrNo = 0;
					while ($DataRows = $Execute->fetch()) {
					    $CategoryId = $DataRows['id'];
					    $CategoryDate = $DataRows['datetime'];
					    $CategoryName = $DataRows['title'];
					    $CreatorName = $DataRows['author'];
					    $SrNo++;
					?>
					<tbody>
						<tr>
							<td><?= htmlentities($SrNo); ?></td>
							<td><?= htmlentities($CategoryDate); ?></td>
							<td><?= htmlentities($CategoryName); ?></td>						
							<td><?= htmlentities($CreatorName); ?></td>
							<td><a href="DeleteCategory.php?id=<?= $CategoryId; ?>" class="btn btn-danger">Delete</a></td>
						</tr>
					</tbody>
					<?php } ?>
				</table>
			</div>
		</div>
	</section>
	<!-- Main Area -->
	<!-- FOOTER START -->
	<footer class="bg-dark text-white">
		<div class="container">
			<div class="row">
				<div class="col">
					<p class="lead text-center">Theme By | Tuấn Phò | &copy; <span id="year"></span> All right Reserved.</p>
				</div>
			</div>
		</div>
	</footer>
	<div style="height: 10px; background: #27aae1"></div>
	<!-- FOOTER END -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
<script>$('#year').text(new Date().getFullYear());</script>
</body>
</html>