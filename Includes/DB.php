<?php
try {
  	$ConnectingDB = new PDO('mysql:host=127.0.0.1;dbname=cms4.2.1', 'root', '');
  	// set the PDO error mode to exception
  	$ConnectingDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  	//echo "Connected successfully";
} catch(PDOException $e) {
  	//echo "Connection failed: " . $e->getMessage();
}
?>