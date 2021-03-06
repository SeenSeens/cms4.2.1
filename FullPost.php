<?php
require_once 'Includes/DB.php';
require_once 'Includes/Functions.php';
require_once 'Includes/Sessions.php';
$SearchQueryParameter = $_GET['id'];
if (isset($_POST['Submit'])) {
	$Name = $_POST['CommenterName'];
	$Email = $_POST['CommenterEmail'];
	$Comment = $_POST['CommenterThoughts'];
	date_default_timezone_set('Asia/Ho_Chi_Minh');
	$CurrenTime = time();
	$DateTime = strftime("%d-%m-%Y %H:%M:%S", $CurrenTime);
	if (empty($Name) || empty($Email) || empty($Comment)) {
		$_SESSION['ErrorMessage'] = 'All fields must be filled out';
		Redirect_to("FullPost.php?id={$SearchQueryParameter}");
		exit;
	}
	if (strlen($Comment) > 500) {
		$_SESSION['ErrorMessage'] = 'Comment length should be lass than 500 character';
		Redirect_to("FullPost.php?id={$SearchQueryParameter}");
		exit;
	}
	if (!empty($Name) || !empty($Email) || !empty($Comment)) {
		global $ConnectingDB;
		$sql = 'INSERT INTO comments(datetime, name, email, comment, approvedby, status, post_id) VALUES (:dateTime, :name, :email, :comment, "pending", "off", :postIdFromURL)';
		$stmt = $ConnectingDB->prepare($sql);
		$stmt->bindValue(':dateTime', $DateTime);
		$stmt->bindValue(':name', $Name);
		$stmt->bindValue(':email', $Email);
		$stmt->bindValue(':comment', $Comment);
		$stmt->bindValue(':postIdFromURL', $SearchQueryParameter);
		$Execute = $stmt->execute();
	}
	if ($Execute) {
		$_SESSION['SuccessMessage'] = 'Comment Submitted Successfully';
		Redirect_to("FullPost.php?id={$SearchQueryParameter}");
	} else {
		$_SESSION['ErrorMessage'] = 'Something went wrong. Try Again!';
		Redirect_to("FullPost.php?id={$SearchQueryParameter}");
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
	<title>Full Post Page</title>
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
	<div class="container">
		<div class="row mt-4">
			<!-- Main Area Start -->
			<div class="col-sm-8">
				<h1>The Complete Responsive CMS Blog</h1>
				<h1 class="lead">The Complete blog using PHP by Tuấn Phò</h1>
				<?php
				echo ErrorMessage();
				echo SuccessMessage();
				global $ConnectingDB;
				if (isset($_GET['SearchButton'])) {
					$Search = $_GET['Search'];
					$sql = 'SELECT * FROM posts
						WHERE datetime LIKE :search
						OR title LIKE :search
						OR category LIKE :search
						OR author LIKE :search
						OR post LIKE :search';
					$stmt = $ConnectingDB->prepare($sql);
					$stmt->bindValue(':search', '%'.$Search.'%');
					$stmt->execute();
				}
				else {
					$PostIdFromURL = $_GET['id'];
					if (!isset($PostIdFromURL)) {
						$_SESSION['ErrorMessage'] = 'Bad Request!';
						Redirect_to('Blog.php');
					}
					$sql = "SELECT * FROM posts WHERE id = '$PostIdFromURL'";
					$stmt = $ConnectingDB->query($sql);
				}
				while ($DataRows = $stmt->fetch()) {
				    $PostId = $DataRows['id'];
				    $DateTime = $DataRows['datetime'];
				    $PostTitle = $DataRows['title'];
				    $Category = $DataRows['category'];
				    $Admin = $DataRows['author'];
				    $Image = $DataRows['image'];
				    $PostDescription = $DataRows['post'];
				?>
				<div class="card">
					<img src="Uploads/<?= htmlentities($Image); ?>" alt="" class="img-fluid card-img-top" style="max-height: 450px;">
					<div class="card-boby">
						<h4 class="card-title"><?= htmlentities($PostTitle); ?></h4>
						<small class="text-muted">
							Category <span class="text-dark"><?= htmlentities($Category); ?></span>
							& Written by <span class="text-dark"><?= htmlentities($Admin); ?></span>
							On <?= htmlentities($DateTime); ?>
						</small>
						<hr>
						<p class="card-text">
							<?= htmlentities($PostDescription); ?>
						</p>
					</div>
				</div>
				<?php } ?>
				<!-- Comment Part Start -->

				<!-- Fetching existing comment START -->
				<span class="FieldInfo">Comments</span>
				<br><br>
				<?php
				global $ConnectingDB;
				$sql = "SELECT * FROM comments WHERE post_id = '$SearchQueryParameter' AND status = 'ON'";
				$stmt = $ConnectingDB->query($sql);
				while ($DataRows = $stmt->fetch()) {
				    $CommentDate = $DataRows['datetime'];
				    $CommenterName = $DataRows['name'];
				    $CommentContent = $DataRows['comment'];
				?>
				<div>				
					<div class="media CommentBlock">
						<img src="Images/icons8-user-96.png" alt="" class="d-block img-fluid align-self-center">
						<div class="media-body ml-2">
							<h6 class="lead"><?= $CommenterName; ?></h6>
							<p class="small"><?= $CommentDate; ?></p>
							<p><?= $CommentContent; ?></p>
						</div>
					</div>
				</div>
				<hr>
				<?php } ?>
				<!-- Fetching existing comment END -->

				<div>
					<form action="FullPost.php?id=<?= $SearchQueryParameter; ?>" method="post">
						<div class="card mb-3">
							<div class="card-header">
								<h5 class="FieldInfo">Share your thoughts about this post</h5>
							</div>
							<div class="card-body">
								<div class="form-group">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-user"></i></span>
										</div>
										<input type="text" class="form-control" name="CommenterName" placeholder="Name" />
									</div>
								</div>
								<div class="form-group">
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="fas fa-envelope"></i></span>
										</div>
										<input type="text" class="form-control" name="CommenterEmail" placeholder="Email" />
									</div>
								</div>
								<div class="form-group">
									<textarea name="CommenterThoughts" id="" class="form-control" rows="6" cols="80"></textarea>
								</div>
								<div class="">
									<button type="submit" name="Submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<!-- Comment Part End -->
			</div>
			<!-- Main Area End -->

			<!-- Side Area Start -->
			<div class="col-sm-4">
				<div class="card mt-4">
					<div class="card-boby">
						<img src="Images/bulb-5665770_1920.jpg" class="d-block img-fluid mb-3" alt="">
						<div class="text-center">
							Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
						</div>
					</div>
				</div>
				<br>
				<div class="card">
					<div class="card-header bg-dark text-light">
						<h2 class="lead">Sign Up !</h2>
					</div>
					<div class="card-boby" style="margin-top: 5px;">
						<button type="button" class="btn btn-success btn-block text-center text-white mb-4" name="button">Join the Forum</button>
						<button type="button" class="btn btn-danger btn-block text-center text-white mb-4" name="button">Login</button>
						<div class="input-group mb-3" style=" margin-top: 5px;">
							<input type="text" name="" class="form-control" placeholder="Enter your email" value="">
							<div class="input-group-append">
								<button type="button" name="button" class="btn btn-primary btn-sm text-white text-center">Subscribe Now</button>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="card">
					<div class="card-header bg-primary text-light">
						<h2 class="lead">Categories</h2>
					</div>
					<div class="card-boby">
						<?php
						global $ConnectingDB;
						$sql = "SELECT * FROM category ORDER BY id DESC";
						$stmt = $ConnectingDB->query($sql);
						while ($DataRows = $stmt->fetch()) {
							$CategoryId = $DataRows['id'];
							$CategoryName = $DataRows['title'];
							?>
						<a href="Blog.php?category=<?= $CategoryName; ?>"> <span class="heading"> <?= $CategoryName; ?></span></a> <br>
					<?php } ?>
					</div>
				</div>
				<br>
				<div class="card">
					<div class="card-header bg-info text-white">
						<h2 class="lead"> Recent Posts</h2>
					</div>
					<div class="card-body">
						<?php
						global $ConnectingDB;
						$sql = "SELECT * FROM posts ORDER BY id DESC LIMIT 0, 5";
						$stmt = $ConnectingDB->query($sql);
						while ($DataRows = $stmt->fetch()) {
							$Id = $DataRows['id'];
							$Title = $DataRows['title'];
							$DateTime = $DataRows['datetime'];
							$Image = $DataRows['image'];
						?>
						<div class="media">
							<img src="Uploads/<?= htmlentities($Image); ?>" class="d-block img-fluid align-self-start" width="90" height="94">
							<div class="media-body ml-2">
								<a href="FullPost.php?id=<?= htmlentities($Id); ?>" target="_blank">
									<h6 class="lead"><?= htmlentities($Title); ?></h6>
								</a>
								<p class="small"><?= htmlentities($DateTime); ?></p>
							</div>
						</div>
						<hr>
						<?php } ?>
					</div>	
				</div>
			</div>
			<!-- Side Area End -->

		</div>
	</div>
	<!-- HEADER -->
	<br>
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