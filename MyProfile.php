<?php
require_once 'Includes/DB.php';
require_once 'Includes/Functions.php';
require_once 'Includes/Sessions.php';
$_SESSION['TrackingURL'] = $_SERVER['PHP_SELF'];
Confirm_Login();
// Fetching the existing Admin Data Start
$AdminId = $_SESSION['UserId'];
global $ConnectingDB;
$sql = "SELECT * FROM admins WHERE id = '$AdminId'";
$stmt = $ConnectingDB->query($sql);
while ($DataRows = $stmt->fetch()) {
	$ExistingName = $DataRows['aname'];
	$ExistingUsername = $DataRows['username'];
	$ExistingHeadline = $DataRows['aheadline'];
	$ExistingBio = $DataRows['abio'];
	$ExistingImage = $DataRows['aimage'];
}
// Fetching the existing Admin Data End
if (isset($_POST['Submit'])) {
	$AName = $_POST['Name'];
	$AHeadline = $_POST['Headline'];
	$ABio = $_POST['Bio'];
	$Image = $_FILES['Image']['name'];
	$Target = 'Images/' . basename($_FILES['Image']['name']);
	if (strlen($AHeadline) > 30) {
		$_SESSION['ErrorMessage'] = 'Headline Should be less than 30 characters';
		redirect_to('MyProfile.php');
	} else if (strlen($ABio) > 500) {
		$_SESSION['ErrorMessage'] = 'ABio should be less than 500 character';
		Redirect_to('MyProfile.php');
	} else {
		// Query to update admin data in DB everything is fine
		global $ConnectingDB;
		if (!empty($_FILES['Image']['name'])) {
			$sql = "UPDATE admins
					SET aname='$AName', aheadline='$AHeadline', abio='$ABio', aimage='$Image'
					WHERE id='$AdminId'";
		} else {
			$sql = "UPDATE admins
					SET aname='$AName', aheadline='$AHeadline', abio='$ABio'
					WHERE id='$AdminId'";
		}		
		$Execute = $ConnectingDB->query($sql);
		move_uploaded_file($_FILES['Image']['tmp_name'], $Target);
	}
	if ($Execute) {
		$_SESSION['SuccessMessage'] = 'Details Updated Successfully.';
		Redirect_to('MyProfile.php');
	} else {
		$_SESSION['ErrorMessage'] = 'Something went wrong. Try Again!';
		Redirect_to('MyProfile.php');
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
	<title>My Profile</title>
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
					<h1><i class="fas fa-user text-success mr-2"></i>@<?= $ExistingUsername; ?></h1>
					<small><?= $ExistingHeadline; ?></small>
				</div>
			</div>
		</div>
	</header>
	<!-- HEADER -->
	<!-- Main Area -->
	<section class="container py-2 mb-4">
		<div class="row">
			<!-- Left Area -->
			<div class="col-md-3">
				<div class="card">
					<div class="card-header bg-dark text-light">
						<h3><?= $ExistingName; ?></h3>
					</div>
					<div class="card-boy">
						<img src="Images/<?= $ExistingImage; ?>" class="block img-fluid mb-3" alt="">
						<div class="">
							<?= $ExistingBio; ?>
						</div>
					</div>					
				</div>
			</div>
			<!-- Left Area End -->

			<!-- Right Area -->
			<div class="col-md-9" style="min-height: 400px;">
				<?php
				echo ErrorMessage();
				echo SuccessMessage();
				?>
				<form action="MyProfile.php" method="post" enctype="multipart/form-data">
					<div class="card bg-dark text-light">
						<div class="card-header bg-secondary text-light">
							<h4>Edit Profile</h4>
						</div>
						<div class="card-boy">
							<br>
							<div class="form-group">
								<input class="form-control" type="text" name="Name" value="" id="title" placeholder="Your Name">
							</div>
							<div class="form-group">
								<input class="form-control" type="text" name="Headline" value="" id="Headline" placeholder="Headline">
								<small class="text-muted">Add a professional headline like, 'Engineer' at XYZ or 'Architect'</small>
								<span class="text-danger">Not more than 30 characters</span>
							</div>
							<div class="form-group">
								<textarea placeholder="Bio" class="form-control" name="Bio" id="Post" cols="10" rows="8">
									
								</textarea>
							</div>
							<div class="form-group mb-1">
								<div class="custom-file">
									<input class="custom-file-input" type="file" name="Image" id="imageSelect" value="">
									<label for="imageSelect" class="custom-file-label"><span class="FieldInfo">Select Image:</span></label>
								</div>
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
			</div>
			<!-- Right Area End -->
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