<?php
function billUpdatedCheck($oldSale,$newSale,$con)
{
	$oldBill = $oldSale['bill_no'];
	$newBill = $newSale['bill_no'];
	
	if($oldBill != $newBill)
	{
		if( fnmatch("BB*",$newBill) || fnmatch("BC*",$newBill) || fnmatch("GB*",$newBill) || fnmatch("GC*",$newBill) || fnmatch("PB*",$newBill) || fnmatch("PC*",$newBill))
			return true;
	}
	else
	{
		return false;
	}
}