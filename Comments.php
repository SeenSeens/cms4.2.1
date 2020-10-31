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
	<title>Comments</title>
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
					<h1><i class="fas fa-comments"></i>Manage Comments</h1>
				</div>
			</div>
		</div>
	</header>
	<!-- HEADER -->
	
	<!-- Main Area Start -->
	<section class="container py-2 mb-4">
		<div class="row" style="min-height: 30px;">
			<div class="col-lg-12" style="min-height: 400px;">
				<?php
				echo ErrorMessage();
				echo SuccessMessage();
				?>
				<h2>Un-Approved Comments</h2>
				<table class="table table-striped table-hover">
					<thead class="thead-dark">
						<tr>
							<th>No. </th>
							<th>Date&Time</th>
							<th>Name</th>
							<th>Comment</th>
							<th>Aprove</th>
							<th>Action</th>
							<th>Details</th>
						</tr>
					</thead>
					<?php
					global $ConnectingDB;
					$sql = "SELECT * FROM comments WHERE status = 'OFF' ORDER BY id DESC";
					$Execute  = $ConnectingDB->query($sql);
					$SrNo = 0;
					while ($DataRows = $Execute->fetch()) {
					    $CommentId = $DataRows['id'];
					    $DateTimeOfComment = $DataRows['datetime'];
					    $CommenterName = $DataRows['name'];
					    $CommentContent = $DataRows['comment'];
					    $CommentPostId = $DataRows['post_id'];
					    $SrNo++;
					    //if (strlen($CommenterName) > 10) { $CommenterName = substr($CommenterName, 0, 10) . '...'; }
					    //if (strlen($DateTimeOfComment) > 11) { $DateTimeOfComment = substr($DateTimeOfComment, 0, 11) . '...'; }
					?>
					<tbody>
						<tr>
							<td><?= htmlentities($SrNo); ?></td>
							<td><?= htmlentities($DateTimeOfComment); ?></td>
							<td><?= htmlentities($CommenterName); ?></td>						
							<td><?= htmlentities($CommentContent); ?></td>
							<td><a href="ApproveComments.php?id=<?= $CommentId; ?>" class="btn btn-success">Approve</a></td>
							<td><a href="DeleteComments.php?id=<?= $CommentId; ?>" class="btn btn-danger">Delete</a></td>
							<td style="min-width: 140px;"><a class="btn btn-primary" href="FullPost.php?id=<?= $CommentPostId; ?>" target="_blank">Live Preview</a></td>
						</tr>
					</tbody>
					<?php } ?>
				</table>
				<h2>Approved Comments</h2>
				<table class="table table-striped table-hover">
					<thead class="thead-dark">
						<tr>
							<th>No. </th>
							<th>Date&Time</th>
							<th>Name</th>
							<th>Comment</th>
							<th>Revert</th>
							<th>Action</th>
							<th>Details</th>
						</tr>
					</thead>
					<?php
					global $ConnectingDB;
					$sql = "SELECT * FROM comments WHERE status = 'ON' ORDER BY id DESC";
					$Execute  = $ConnectingDB->query($sql);
					$SrNo = 0;
					while ($DataRows = $Execute->fetch()) {
					    $CommentId = $DataRows['id'];
					    $DateTimeOfComment = $DataRows['datetime'];
					    $CommenterName = $DataRows['name'];
					    $CommentContent = $DataRows['comment'];
					    $CommentPostId = $DataRows['post_id'];
					    $SrNo++;
					    //if (strlen($CommenterName) > 10) { $CommenterName = substr($CommenterName, 0, 10) . '...'; }
					    //if (strlen($DateTimeOfComment) > 11) { $DateTimeOfComment = substr($DateTimeOfComment, 0, 11) . '...'; }
					?>
					<tbody>
						<tr>
							<td><?= htmlentities($SrNo); ?></td>
							<td><?= htmlentities($DateTimeOfComment); ?></td>
							<td><?= htmlentities($CommenterName); ?></td>						
							<td><?= htmlentities($CommentContent); ?></td>
							<td style="min-width: 140px;"><a href="DisApproveComments.php?id=<?= $CommentId; ?>" class="btn btn-warning">Dis-Approve</a></td>
							<td><a href="DeleteComments.php?id=<?= $CommentId; ?>" class="btn btn-danger">Delete</a></td>
							<td style="min-width: 140px;"><a class="btn btn-primary" href="FullPost.php?id=<?= $CommentPostId; ?>" target="_blank">Live Preview</a></td>
						</tr>
					</tbody>
					<?php } ?>
				</table>
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