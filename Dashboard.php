<?php
require_once 'Includes/DB.php';
require_once 'Includes/Functions.php';
require_once 'Includes/Sessions.php';
$_SESSION['TrackingURL'] = $_SERVER['PHP_SELF'];
Confirm_Login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" href="Css/Style.css">
	<title>Dashboard</title>
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
					<h1><i class="fas fa-cog"></i> Dashboard</h1>
				</div>
				<div class="col-lg-3 mb-2">
					<a href="AddNewPost.php" class="btn btn-primary btn-block"><i class="fas fa-edit"></i> Add New Post</a>
				</div>
				<div class="col-lg-3 mb-2">
					<a href="Categories.php" class="btn btn-info btn-block"><i class="fas fa-folder-plus"></i> Add New Category</a>
				</div>
				<div class="col-lg-3 mb-2">
					<a href="Admins.php" class="btn btn-warning btn-block"><i class="fas fa-user-plus"></i> Add New Admin</a>
				</div>
				<div class="col-lg-3 mb-2">
					<a href="Comments.php" class="btn btn-success btn-block"><i class="fas fa-check"></i> Approve Comments</a>
				</div>
			</div>
		</div>
	</header>
	<!-- HEADER -->

	<!-- MAIN -->
	<section class="container py-2 mb-4">
		<div class="row">
			<?php
			echo ErrorMessage();
			echo SuccessMessage();
			?>

			<!-- Left Side Area Start -->
			<div class="col-lg-2 d-none d-md-block">
				<div class="card text-center bg-dark text-white mb-3">
					<div class="card-body">
						<h1 class="lead">Posts</h1>
						<h4 class="display-5">
							<i class="fab fa-readme"></i>
							<?php TotalPosts(); ?>
						</h4>
					</div>
				</div>
				<div class="card text-center bg-dark text-white mb-3">
					<div class="card-body">
						<h1 class="lead">Categories</h1>
						<h4 class="display-5">
							<i class="fas fa-folder"></i>
							<?php TotalCategories(); ?>
						</h4>
					</div>
				</div>
				<div class="card text-center bg-dark text-white mb-3">
					<div class="card-body">
						<h1 class="lead">Admins</h1>
						<h4 class="display-5">
							<i class="fas fa-users"></i>
							<?php TotalAdmins(); ?>
						</h4>
					</div>
				</div>
				<div class="card text-center bg-dark text-white mb-3">
					<div class="card-body">
						<h1 class="lead">Comments</h1>
						<h4 class="display-5">
							<i class="fas fa-comments"></i>
							<?php TotalComments(); ?>
						</h4>
					</div>
				</div>
			</div>
			<!-- Left Side Area End -->

			<!-- Right Side Area Start -->
			<div class="col-lg-10">
				<h1>Top Posts</h1>
				<table class="table table-striped table-hover">
					<thead class="thead-dark">
						<tr>
							<th>No.</th>
							<th>Title</th>
							<th>Date&Time</th>
							<th>Author</th>
							<th>Comments</th>
							<th>Details</th>
						</tr>
					</thead>
					<?php
					$SrNo = 0;
					global $ConnectingDB;
					$sql = 'SELECT * FROM posts ORDER BY id DESC LIMIT 0,5';
					$stmt = $ConnectingDB->query($sql);
					while ($DataRows = $stmt->fetch()) {
					    $PostId = $DataRows['id'];
					    $DateTime = $DataRows['datetime'];
					    $Author = $DataRows['author'];
					    $Title = $DataRows['title'];
					    $SrNo++;
					?>
					<tbody>
						<tr>
							<td><?= $SrNo; ?></td>
							<td><?= $Title; ?></td>
							<td><?= $DateTime; ?></td>
							<td><?= $Author; ?></td>
							<td>
								<?php
								$Total = ApproveCommentsAccordingtoPost($PostId);
								if ($Total > 0) { ?>
									<span class="badge badge-success">
									<?php echo $Total; ?>
									</span>
								<?php } ?>						
								
								<?php
								$Total = DisApproveCommentsAccordingtoPost($PostId);
								if($Total > 0) { ?>
									<span class="badge badge-danger">
									<?= $Total; ?>
									</span>
								<?php } ?>
							</td>
							<td>
								<a href="FullPost.php?id=<?= $PostId; ?>" target="_blank">
									<span class="btn btn-info">Preview</span>
								</a>
							</td>
						</tr>
					</tbody>
					<?php } ?>
				</table>
			</div>
			<!-- Right Side Area End -->
		</div>
	</section>
	<!-- MAIN -->

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