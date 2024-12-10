<?php
	require '../../connect.php';

	session_start();

	$id = $_POST['id'];
	
	$delete = mysqli_query($con, "UPDATE gate_pass SET deleted = 1 WHERE id = $id");
	if($delete)
	{
		$response_array['status'] = 'success';
	}
	else
	{
		$response_array['status'] = 'error';
		$response_array['value'] = mysqli_error($con);
	}				

	echo json_encode($response_array);

	exit;	
?>