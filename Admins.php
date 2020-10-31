<?php
require_once 'Includes/DB.php';
require_once 'Includes/Functions.php';
require_once 'Includes/Sessions.php';
$_SESSION['TrackingURL'] = $_SERVER['PHP_SELF'];
Confirm_Login();
if (isset($_POST['Submit'])) {
	$Username = $_POST['Username'];
	$Name = $_POST['Name'];
	$Password = $_POST['Password'];
	$ConfirmPassword = $_POST['ConfirmPassword'];
	$Admin = 'TuanPho';
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	$CurrenTime = time();
	$DateTime = strftime("%d-%m-%Y %H:%M:%S", $CurrenTime);
	if (empty($Username) || empty($Password) || empty($ConfirmPassword)) {
		$_SESSION['ErrorMessage'] = 'All fields must be filled out';
		Redirect_to('Admins.php');
	} else if (strlen($Password) < 4) {
		$_SESSION['ErrorMessage'] = 'Password should be greater than 4 character';
		redirect_to('Admnins.php');
	} else if ($Password !== $ConfirmPassword) {
		$_SESSION['ErrorMessage'] = 'Password and Confirm Password should match';
		Redirect_to('Admins.php');
	} else if (CheckUserNameExistsOrNot($Username)) {
		$_SESSION['ErrorMessage'] = 'Username Exists. Try Another One!';
		Redirect_to('Admins.php');
	} else {
		global $ConnectingDB;
		$sql = 'INSERT INTO admins(datetime, username, password, aname, addedby) VALUES (:dateTime, :userName, :password, :aName, :adminName)';
		$stmt = $ConnectingDB->prepare($sql);
		$stmt->bindValue(':dateTime', $DateTime);
		$stmt->bindValue(':userName', $Username);
		$stmt->bindValue(':password', $Password);
		$stmt->bindValue(':aName', $Name);
		$stmt->bindValue(':adminName', $Admin);
		$Execute = $stmt->execute();
	}
	if ($Execute) {
		$_SESSION['SuccessMessage'] = 'New Admin with the name of ' . $Name . ' added successfully';
		Redirect_to('Admins.php');
	} else {
		$_SESSION['ErrorMessage'] = 'Something went wrong. Try Again!';
		Redirect_to('Admins.php');
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
	<title>Admins Page</title>
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
					<h1><i class="fas fa-user"></i> Manage Admins</h1>
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
				<form action="Admins.php" method="post">
					<div class="card bg-secondary text-light mb-3">
						<div class="card-header">
							<h1>Add New Admins</h1>
						</div>
						<div class="card-boy bg-dark">
							<div class="form-group">
								<label for="username"><span class="FieldInfo">Username:</span></label>
								<input class="form-control" type="text" name="Username" id="username">
							</div>
							<div class="form-group">
								<label for="name"><span class="FieldInfo">Name:</span></label>
								<input class="form-control" type="text" name="Name" id="name">
								<small class="text-danger text-muted">* Optional</small>
							</div>
							<div class="form-group">
								<label for="password"><span class="FieldInfo">Password:</span></label>
								<input class="form-control" type="password" name="Password" id="password">
							</div>
							<div class="form-group">
								<label for="confirmpassword"><span class="FieldInfo">Confirm Password:</span></label>
								<input class="form-control" type="password" name="ConfirmPassword" id="confirmpassword">
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
				<h2>Existing Admins</h2>
				<table class="table table-striped table-hover">
					<thead class="thead-dark">
						<tr>
							<th>No. </th>
							<th>Date&Time</th>
							<th>Username</th>
							<th>Admin Name</th>
							<th>Added by</th>
							<th>Action</th>
						</tr>
					</thead>
					<?php
					global $ConnectingDB;
					$sql = "SELECT * FROM admins ORDER BY id DESC";
					$Execute  = $ConnectingDB->query($sql);
					$SrNo = 0;
					while ($DataRows = $Execute->fetch()) {
					    $AdminId = $DataRows['id'];
					    $DateTime = $DataRows['datetime'];
					    $AdminUsername = $DataRows['username'];
					    $AdminName = $DataRows['aname'];
					    $Addedby = $DataRows['addedby'];
					    $SrNo++;
					?>
					<tbody>
						<tr>
							<td><?= htmlentities($SrNo); ?></td>
							<td><?= htmlentities($DateTime); ?></td>
							<td><?= htmlentities($AdminUsername); ?></td>						
							<td><?= htmlentities($AdminName); ?></td>
							<td><?= htmlentities($Addedby); ?></td>
							<td><a href="DeleteAdmin.php?id=<?= $AdminId; ?>" class="btn btn-danger">Delete</a></td>
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