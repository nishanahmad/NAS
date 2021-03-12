<?php

function billStatus($bill)
{	
	if( fnmatch("BB*",$bill) || fnmatch("BC*",$bill) || fnmatch("GB*",$bill) || fnmatch("GC*",$bill) || fnmatch("PB*",$bill) || fnmatch("PC*",$bill))
		return true;
	else
		return false;
}