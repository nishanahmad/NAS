<?php
	require '../connect.php';
	
	$id = $_GET['id'];
	$sql= "DELETE FROM redemption WHERE id = $id";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));	

	header( "Location: list.php" );
?>