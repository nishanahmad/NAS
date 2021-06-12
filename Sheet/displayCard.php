<?php
function displayCard($sheet)
{
	$card = $sheet['area'].'<br/>';
	$card = $card.$sheet['customer_name'].', '.$sheet['customer_phone'].'<br/><b>';
	$card = $card.$sheet['bags'].' bags<br/>';
	$card = $card.$sheet['requested_by'].'<br/>';
	$card = $card.$sheet['shop'].'</b><br/>';
	if($sheet['coveringBlock'])
		$card = $card.'<font style="color:red;font-weight:bold">Covering Block</font> <br/>';
	if(isset($sheet['remarks']) && $sheet['remarks'] != '')
		$card = $card.$sheet['remarks'].'<br/>';
	$card = $card.'<font color="limegreen">Created On: '.date('d-M h:i A',strtotime($sheet['created_on'])).'</font><br/>';
	if($sheet['driver_remarks'] != null)
		$card = $card.'<font style="font-weight:bold;color:red">'.$sheet['driver_remarks'].'</font><br/>';	
	else if($sheet['driver_read'] == 1)
		$card = $card.'<i class="fa fa-eye" style="color:#4285F4"></i>'.'<br/>';			
	
	return $card;
}
?>
