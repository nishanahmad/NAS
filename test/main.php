<?php
require '../connect.php';

$query ="select * from nas_sale where f2r > 0 AND qty = 0 LIMIT 10000";

$result = mysqli_query($con, $query) or die(mysqli_error($con));				 

$map = array();

$map['2019-06-01']['hi'] = 0;