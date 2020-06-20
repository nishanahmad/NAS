<?php
/********************************				MAIN FUNCTION (CALLED EXTERNALLY)				****************************/

function getAvgRate($arList,$product,$startDate,$endDate,$con)
{	
	$rateMap = getRatemap($product,$endDate,$con);
	$wdMap = getWDmap($product,$startDate,$endDate,$con);
	$cdMap = getCDSDmap($arList,$product,$endDate,'cd',$con);
	$sdMap = getCDSDmap($arList,$product,$endDate,'sd',$con);
	
	$totalQty = 0;
	$totalCash = 0;
	$saleList = getSaleList($product,$startDate,$endDate,$con);
	foreach($saleList as $sale)
	{
		$rate = getSingleSaleRate($rateMap,$sale);
		$cd = getSingleSaleCD($cdMap,$sale);
		$sd = getSingleSaleSD($sdMap,$sale);
		$bd = $sale -> bd();
		
		$cash = $sale -> qty() * ($rate - $cd - $sd - $bd);
		
		$totalQty = $totalQty + $sale -> qty();
		$totalCash = $totalCash + $cash;
	}
	
	$avgRate = round($totalCash/$totalQty,2);
	
	return $avgRate;
}




/********************************		INTERNAL FUNCTIONS (CALLED BY THE MAIN FUNCTION getAvgRate)		****************************/
						
function getSaleList($product,$startDate,$endDate,$con)
{	
	$saleList = array();
	$saleQuery = "SELECT * FROM nas_sale WHERE product = $product AND entry_date >= '$startDate' AND entry_date <= '$endDate' ORDER BY entry_date ASC";
	$sales = mysqli_query($con,$saleQuery) or die(mysqli_error($con));				 	 
	foreach($sales as $sale)
	{
		$saleObj = new Sale();
		$saleObj->set_values($sale);
		$saleList[] = $saleObj;
	}
	
	return $saleList;		
}
						
function getRatemap($product,$endDate,$con)
{	
	$rateMap = array();
	$rateQuery = "SELECT date,rate FROM rate WHERE product = $product AND date <= '$endDate' ORDER BY date DESC";
	$rateList = mysqli_query($con,$rateQuery) or die(mysqli_error($con));				 	 
	foreach($rateList as $rate)
	{
		$rateMap[$rate['date']] = $rate['rate'];
	}
	
	return $rateMap;		
}

function getWDmap($product,$startDate,$endDate,$con)
{	
	$discountMap = array();
	$discountQuery = "SELECT date,amount FROM discounts WHERE product = $product AND type = 'wd' AND date >= '$startDate' AND date <= '$endDate' ORDER BY date ASC";
	$discountList = mysqli_query($con,$discountQuery) or die(mysqli_error($con));				 	 
	foreach($discountList as $discount)
	{
		$discountMap[$discount['date']] = $discount['amount'];
	}
	
	return $discountMap;		
}

function getCDSDmap($arList,$product,$endDate,$type,$con)
{		
	$discountMap = array();
	$discountQuery = "SELECT date,client,amount FROM discounts WHERE client IN (".implode(',',$arList).") AND product = $product AND type = '$type' AND date <= '$endDate' ORDER BY date ASC";
	$discountList = mysqli_query($con,$discountQuery) or die(mysqli_error($con));				 	 
	foreach($discountList as $discount)
	{
		$discountMap[$discount['client']][$discount['date']] = $discount['amount'];
	}
	
	return $discountMap;		
}

function getSingleSaleRate($rateMap,$sale)
{	
	$rate = 0;
	$tempDate = date("Y-m-d", strtotime($sale->entry_date()));
	if(isset($rateMap[$tempDate]))
		$rate = $rateMap[$tempDate];
	else
	{
		while($tempDate >= date("Y-m-d", strtotime("2019-06-18")))
		{
			$tempDate = date('Y-m-d', strtotime('-1 day', strtotime($tempDate)));
			if(isset($rateMap[$tempDate]))
			{
				$rate = $rateMap[$tempDate];
				break;
			}
		}
	}
		
	return $rate;		
}

function getSingleSaleCD($cdMap,$sale)
{	
	$cd = 0;
	$tempDate = date("Y-m-d", strtotime($sale->entry_date()));
	if(isset($cdMap[$sale->client()][$tempDate]))
		$cd = $cdMap[$sale->client()][$tempDate];
	else
	{
		while($tempDate >= date("Y-m-d", strtotime("2019-06-18")))
		{
			$tempDate = date('Y-m-d', strtotime('-1 day', strtotime($tempDate)));
			if(isset($cdMap[$sale->client()][$tempDate]))
			{
				$cd = $cdMap[$sale->client()][$tempDate];
				break;
			}
		}
	}
		
	return $cd;		
}

function getSingleSaleSD($sdMap,$sale)
{	
	$sd = 0;
	$tempDate = date("Y-m-d", strtotime($sale->entry_date()));
	if(isset($sdMap[$sale->client()][$tempDate]))
		$sd = $sdMap[$sale->client()][$tempDate];
	else
	{
		while($tempDate >= date("Y-m-d", strtotime("2019-06-18")))
		{
			$tempDate = date('Y-m-d', strtotime('-1 day', strtotime($tempDate)));
			if(isset($sdMap[$sale->client()][$tempDate]))
			{
				$sd = $sdMap[$sale->client()][$tempDate];
				break;
			}
		}
	}
		
	return $sd;		
}

class Sale 
{
  function set_values($sale) 
  {
    $this->entry_date = $sale['entry_date'];
	$this->qty = $sale['qty'] - $sale['return_bag'];
	$this->client = $sale['ar_id'];
	$this->bd = $sale['discount'];
  }

  function entry_date() 
  {
    return $this->entry_date;
  }
  
  function qty() 
  {
    return $this->qty;
  }
  
  function client() 
  {
    return $this->client;
  }    
  
  function bd() 
  {
    return $this->bd;
  }      
}


//function tests. Remove in production

require '../../../connect.php';	

$arList = mysqli_query($con, "SELECT id FROM ar_details ORDER BY name") or die(mysqli_error($con));				 	 
foreach($arList as $ar)
{
	$arIds[] = $ar['id'];
}

//var_dump(getAvgRate($arIds,1,date('Y-m-d', strtotime(date('Y-m-01').' -1 MONTH')),date('Y-m-d')));
var_dump(getAvgRate($arIds,1,date('Y-m-d'),date('Y-m-d'),$con));