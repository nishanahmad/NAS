<?php
	require '../connect.php';
	
	$id = $_GET['id'];
	$sql= "DELETE FROM targetbags_ultra WHERE id = $id";

	$result = mysqli_query($con, $sql) or die(mysqli_error($con));	

	header( "Location: index.php" );
?>