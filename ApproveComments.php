<?php
require_once 'Includes/DB.php';
require_once 'Includes/Functions.php';
require_once 'Includes/Sessions.php';
if (isset($_GET['id'])) {
	$SearchQueryParameter = $_GET['id'];
	global $ConnectingDB;
	$Admin = $_SESSION['AdminName'];
	$sql = "UPDATE comments SET status = 'ON', approvedby = '$Admin' WHERE id = '$SearchQueryParameter'";
	$Execute = $ConnectingDB->query($sql);
	if ($Execute) {
		$_SESSION['SuccessMessage'] = 'Comment Approved Successfully!';
		Redirect_to('Comments.php');
	} else {
		$_SESSION['ErrorMessage'] = 'Something Went Wrong. Try Again!';
		Redirect_to('Comments.php');
	}
}