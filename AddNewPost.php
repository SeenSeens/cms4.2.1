<?php
require_once 'Includes/DB.php';
require_once 'Includes/Functions.php';
require_once 'Includes/Sessions.php';
$_SESSION['TrackingURL'] = $_SERVER['PHP_SELF'];
Confirm_Login();
if (isset($_POST['Submit'])) {
	$PostTitle = $_POST['PostTitle'];
	$Category = $_POST['Category'];
	$Image = $_FILES['Image']['name'];
	$Target = 'Uploads/' . basename($_FILES['Image']['name']);
	$PostText = $_POST['PostDescription'];
	$Admin = $_SESSION['AdminName'];
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	$CurrenTime = time();
	$DateTime = strftime("%d-%m-%Y %H:%M:%S", $CurrenTime);
	if (empty($PostTitle)) {
		$_SESSION['ErrorMessage'] = 'Title cant be empty';
		Redirect_to('AddNewPost.php');
	} else if (strlen($PostTitle) < 5) {
		$_SESSION['ErrorMessage'] = 'Post title should be greater than 5 characters';
		redirect_to('AddNewPost.php');
	} else if (strlen($PostTitle) > 999) {
		$_SESSION['ErrorMessage'] = 'Post Description should be less than 1000 character';
		Redirect_to('AddNewPost.php');
	} else {
		global $ConnectingDB;
		$sql = 'INSERT INTO posts(datetime, title, category, author, image, post) VALUES (:dateTime, :postTitle, :categoryName, :adminName, :imageName, :postDescription)';
		$stmt = $ConnectingDB->prepare($sql);
		$stmt->bindValue(':dateTime', $DateTime);
		$stmt->bindValue(':postTitle', $PostTitle);
		$stmt->bindValue(':categoryName', $Category);
		$stmt->bindValue(':adminName', $Admin);
		$stmt->bindValue(':imageName', $Image);
		$stmt->bindValue(':postDescription', $PostText);
		$Execute = $stmt->execute();
		move_uploaded_file($_FILES['Image']['tmp_name'], $Target);
	}
	if ($Execute) {
		$_SESSION['SuccessMessage'] = 'Post with id : ' . $ConnectingDB->lastInsertid() . ' Added Successfully.';
		Redirect_to('AddNewPost.php');
	} else {
		$_SESSION['ErrorMessage'] = 'Something went wrong. Try Again!';
		Redirect_to('AddNewPost.php');
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
					<h1><i class="fas fa-edit"></i>Add New Post</h1>
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
				<form action="AddNewPost.php" method="post" enctype="multipart/form-data">
					<div class="card bg-secondary text-light mb-3">
						<div class="card-boy bg-dark">
							<div class="form-group">
								<label for="title"><span class="FieldInfo">Post Title:</span></label>
								<input class="form-control" type="text" name="PostTitle" value="" id="title" placeholder="Type title here">
							</div>
							<div class="form-group">
								<label for="CategoryTitle"><span class="FieldInfo">Chose Category:</span></label>
								<select class="form-control" name="Category" id="CategoryTitle">
									<?php
									global $ConnectingDB;
									$sql = 'SELECT id, title FROM category';
									$stmt = $ConnectingDB->query($sql);
									while ($DataRows = $stmt->fetch()) :
									    $Id = $DataRows['id'];
									    $categoryName = $DataRows['title'];
									?>
									<option value="<?= $categoryName; ?>"><?= $categoryName ?></option>
									<?php endwhile; ?>
								</select>
							</div>
							<div class="form-group mb-1">
								<div class="custom-file">
									<input class="custom-file-input" type="file" name="Image" id="imageSelect" value="">
									<label for="imageSelect" class="custom-file-label"><span class="FieldInfo">Select Image:</span></label>
								</div>
							</div>
							<div class="form-group">
								<label for="Post"><span class="FieldInfo">Post:</span></label>
								<textarea class="form-control" name="PostDescription" id="Post" cols="30" rows="10">
									
								</textarea>
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