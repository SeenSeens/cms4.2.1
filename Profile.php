<?php
require_once 'Includes/DB.php';
require_once 'Includes/Functions.php';
require_once 'Includes/Sessions.php';
?>
<!-- Fetching Existing Data -->
<?php
$SearchQueryParameter = $_GET['username'];
global $ConnectingDB;
$sql = "SELECT aname, aheadline, abio, aimage FROM admins WHERE username=:userName";
$stmt = $ConnectingDB->prepare($sql);
$stmt->bindValue(':userName', $SearchQueryParameter);
$stmt->execute();
$Result = $stmt->rowcount();
if ($Result == 1) {
	while ($DataRows = $stmt->fetch()) {
		$ExistingName = $DataRows['aname'];
		$ExistingBio = $DataRows['abio'];
		$ExistingImage = $DataRows['aimage'];
		$ExistingHeadline = $DataRows['aheadline'];
	}
} else {
	$_SESSION['ErrorMessage'] = 'Bad Request !!!';
	Redirect_to('Blog.php?page=1');
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
	<title>Profile</title>
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
					<li class="nav-item"><a href="Blog.php" class="nav-link">Home</a></li>
					<li class="nav-item"><a href="#" class="nav-link">About Us</a></li>
					<li class="nav-item"><a href="Blog.php" class="nav-link">Blog</a></li>
					<li class="nav-item"><a href="#" class="nav-link">Contact Us</a></li>
					<li class="nav-item"><a href="#" class="nav-link">Features</a></li>
				</ul>
				<ul class="navbar-nav ml-auto">
					<form action="Blog.php" class="form-inline d-none d-sm-block">
						<div class="form-group">
							<input type="text" class="form-control mr-2" name="Search" placeholder="Search here" value="">
							<button class="btn btn-primary" name="SearchButton">Go</button>
						</div>
					</form>
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
				<div class="col-md-6">
					<h1><i class="fas fa-user text-success mr-2"></i> <?= $ExistingName; ?></h1>
					<h3>Headline</h3>
				</div>
			</div>
		</div>
	</header>
	<!-- HEADER -->
	<section class="container py-2 mb-4">
		<div class="row">
			<div class="col-md-3">
				<img src="Images/<?= $ExistingImage; ?>" alt="" class="d-block img-fluid mb-3 rounded-circle">
			</div>
			<div class="col-md-9" style="min-height: 350px;">
				<div class="card">
					<div class="card-body">
						<p class="lead"><?= $ExistingBio; ?></p>
					</div>
				</div>
			</div>
		</div>
	</section>
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