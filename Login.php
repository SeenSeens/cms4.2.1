<?php
require_once 'Includes/DB.php';
require_once 'Includes/Functions.php';
require_once 'Includes/Sessions.php';
if (isset($_SESSION['UserId'])) {
	Redirect_to('Dashboard.php');
}
if (isset($_POST['Submit'])) {
	$UserName = $_POST['Username'];
	$PassWord = $_POST['Password'];
	if (empty($UserName) || empty($PassWord)) {
		$_SESSION['ErrorMessage'] = 'All fields must be filled out';
		Redirect_to('Login.php');
	} else {
		$Found_Account = Login_Attempt($UserName, $PassWord);
		if ($Found_Account) {
			$_SESSION['UserId'] = $Found_Account['id'];
			$_SESSION['UserName'] = $Found_Account['username'];
			$_SESSION['AdminName'] = $Found_Account['aname'];
			$_SESSION['SuccessMessage'] = 'Wellcom ' . $_SESSION['AdminName'];
			if (isset($_SESSION['TrackingURL'])) {
				Redirect_to($_SESSION['TrackingURL']);
			} else {
				Redirect_to('Dashboard.php');
			}	
		} else {
			$_SESSION['ErrorMessage'] = 'Incorrect Username / Password';
			Redirect_to('Login.php');
		}
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
	<title>Login</title>
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
					
				</div>
			</div>
		</div>
	</header>
	<!-- HEADER -->

	<!-- Main Area Start -->
	<section class="container py-2 mb-4">
		<div class="row">
			<div class="offset-sm-3 col-sm-6" style="min-height: 500px;">
				<br><br><br>
				<?php
				echo ErrorMessage();
				echo SuccessMessage();
				?>
				<div class="card bg-secondary text-light">
					<div class="card-header">
						<h4>Wellcom Back!</h4>
					</div>
					<div class="card-body bg-dark">
						<form action="Login.php" method="post">
							<div class="form-group">
								<label for="username"><span class="FieldInfo">Username:</span></label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text text-while bg-info"><i class="fas fa-user"></i></span>
									</div>
									<input type="text" class="form-control" name="Username" id="username" value="">
								</div>
							</div>
							<div class="form-group">
								<label for="password"><span class="FieldInfo">Password:</span></label>
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text text-while bg-info"><i class="fas fa-lock"></i></span>
									</div>
									<input type="password" class="form-control" name="Password" id="password" value="">
								</div>
							</div>
							<input type="submit" name="Submit" class="btn btn-info btn-block" value="Login">
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Main Area End -->

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