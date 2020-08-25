<?php
require '../connect.php';

  $sql= "DELETE FROM nas_sale WHERE sales_id='" . $_GET["sales_id"] . "'";

  $result = mysqli_query($con, $sql) or die(mysqli_error($con));	
		 
	$url = 'list.php?success&sql='.$_GET['sql'].'&range='.$_GET['range'];

header( "Location: $url" );
?>