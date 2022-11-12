<?php
ini_set('error_reporting', E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	require '../functions/monthMap.php';
	require '../SpecialTarget/dropDownGenerator.php';
	require '../navbar.php';
	
	
	$doublePointsAR = array(24,156,163,221);
	$totalMap = array();
	$monthMap = array(1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",5=>"May",6=>"Jun",7=>"Jul",8=>"Aug",9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec");
	$mainMap  = array();
	if(isset($_GET['year']))
		$year = (int)$_GET['year'];		
	else
		$year = (int)date("Y");
	
	$startYearMonthMap = array();
	$goldQuery = mysqli_query($con,"SELECT ar_id, start_year, start_month FROM gold_ar") or die(mysqli_error($con));
	foreach($goldQuery as $gold)
	{
		$startYearMonthMap[$gold['ar_id']]['year'] = (int)$gold['start_year'];
		$startYearMonthMap[$gold['ar_id']]['month'] = (int)$gold['start_month'];
	}
		
	$arList = mysqli_query($con,"SELECT id, name, mobile, whatsapp, shop_name FROM ar_details WHERE id IN (SELECT ar_id FROM gold_ar) ") or die(mysqli_error($con));		 
	foreach($arList as $arObject)
	{
		$arNameMap[$arObject['id']] = $arObject['name'];
		$arShopMap[$arObject['id']] = $arObject['shop_name'];
	}
	
	$array = implode("','",array_keys($arNameMap));	
	

		$sales = mysqli_query($con,"SELECT ar_id,SUM(qty),MONTH(entry_date) FROM nas_sale WHERE 
											deleted IS NULL AND 
											other_purchase = 0 AND
											YEAR(entry_date) = $year AND 
											ar_id IN ('$array')
											GROUP BY ar_id,MONTH(entry_date)")
											or die(mysqli_error($con));												
											
	foreach($sales as $sale)
	{
		$ar = $sale['ar_id'];
		$month = (int)$sale['MONTH(entry_date)'];
		if($month >= $startYearMonthMap[$ar]['month'])
		{
			$qty = (int)$sale['SUM(qty)'];
			$mainMap[$ar][$month] = $qty;
			if(isset($totalMap[$ar]))
			{
				if(in_array($ar,$doublePointsAR))
					$totalMap[$ar] = $totalMap[$ar] + $qty * 2;
				else
					$totalMap[$ar] = $totalMap[$ar] + $qty;
			}
			else
			{
				if(in_array($ar,$doublePointsAR))
					$totalMap[$ar] = $qty * 2;
				else
					$totalMap[$ar] = $qty;
			}
		}

	}	
?>
<html>
<head>
	<link href="../css/styles.css" rel="stylesheet" type="text/css">	
	<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
	<title>Gold Points</title>
</head>
<body>		
	<div id="main" class="main">
		<aside class="sidebar">
			<nav class="nav">
				<ul>
					<li><a href="../ar/list.php">AR List</a></li>
					<li><a href="../Target/list.php?">Target</a></li>
					<li><a href="../SpecialTarget/list.php">Special Target</a></li>
					<li class="active"><a href="#">Gold Points</a></li>
					<li><a href="../redemption/list.php?">Redemption</a></li>
				</ul>
			</nav>
		</aside>
		<div class="container">		
			<nav class="navbar navbar-light bg-light sticky-top bottom-nav" style="margin-left:12.5%;width:100%">
				<span class="navbar-brand" style="margin-left:35%;;font-size:25px;"><i class="fa fa-chart-pie"></i> Gold Points</span>
			</nav>
			<div id="snackbar"><i class="fa fa-chart-pie"></i>&nbsp;&nbsp;Special target list inserted succesfully !!!</div>
			<br><br>
			<div class="row">
				<div style="width:120px;margin-left:49%">
					<div class="input-group">
						<select id="jsYear" name="jsYear" class="form-select" onchange="return refreshYear();">																<?php	
							$yearList = getYears();	
							foreach($yearList as $yr)
							{
								if($yr >= 2022)
								{																																			?>
									<option value="<?php echo $yr;?>" <?php if($year == $yr) echo 'selected';?>><?php echo $yr;?></option>									<?php										
								}
							} 																																				?>		
						</select>
					</div>
				</div>
			</div>			
			<br><br>
			<table class="maintable table table-hover table-bordered" style="width:90%;margin-left:15%;">
				<thead>
					<tr class="table-success">
						<th style="text-align:left;width:20%;">AR</th>
						<th style="text-align:left;width:20%;">SHOP</th><?php
						foreach($monthMap as $monthIndex => $monthName)
						{																																	?>
							<th><?php echo $monthName;?></th>																				<?php
						}																																	?>
						<th>Total</th>
					</tr>																																
				</thead>
				<tbody>				<?php
				$saleTotal = 0;
				foreach($arNameMap as $arId =>$arName)
				{																																			?>
					<tr align="center">
						<td style="text-align:left;"><?php echo $arName;?></td>
						<td style="text-align:left;"><?php echo $arShopMap[$arId];?></td><?php
						foreach($monthMap as $monthIndex => $monthName)
						{																																	?>
							<td><?php 
								if(in_array($arId,$doublePointsAR))
									echo $mainMap[$arId][$monthIndex] * 2;
								else
									echo $mainMap[$arId][$monthIndex];																					?>
							</td>																															<?php
						}																																	?>
						<td><b><?php echo $totalMap[$arId];?></b></td>
					</tr>																																	<?php
				}																																			?>	
				</tbody>
				<!--tfoot>
					<tr style="line-height:50px;background-color:#BEBEBE !important;font-family: Arial Black;">
						<td colspan="3" style="text-align:right;font-size:20px;">Total</td>
						<td style="font-size:15px;"> //echo $targetTotal;?></td>						
					</tr>
				</tfoot-->
			</table>
			<br><br><br><br>
		</div>
	</div>
</body>
<script type="text/javascript" language="javascript" >
$(document).ready(function() {		
	$(".maintable tbody tr").each(function(){
		var extra = $(this).find("td:eq(8)").text();   
		if (extra != '0'){
		$(this).addClass('selected');
		}
	});

	$(".maintable").tablesorter({
		theme : 'bootstrap',
		widgets: ['filter'],
		filter_columnAnyMatch: true
	});

			
	if(window.location.href.includes('success')){
		var x = document.getElementById("snackbar");
		x.className = "show";
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, 2000);					
	}			
} );
function refresh()
{
	var removeToday = $('#removeToday').is(':checked');
	
	var hrf = window.location.href;
	hrf = hrf.slice(0,hrf.indexOf("?"));
	window.location.href = hrf + "?removeToday=" + removeToday;
}

function refreshYear()
{
	var year = document.getElementById("jsYear").options[document.getElementById("jsYear").selectedIndex].value;
	
	var hrf = window.location.href;
	hrf = hrf.slice(0,hrf.indexOf("?"));
	
	window.location.href = hrf +"?year="+ year;
}	

var getUrlParameter = function getUrlParameter(sParam) {
	var sPageURL = decodeURIComponent(window.location.search.substring(1)),
		sURLVariables = sPageURL.split('&'),
		sParameterName,
		i;
	for (i = 0; i < sURLVariables.length; i++) {
		sParameterName = sURLVariables[i].split('=');
		if (sParameterName[0] === sParam) {
			return sParameterName[1] === undefined ? true : sParameterName[1];
		}
	}
};
</script>
</html>
<?php
}
else
	header("Location:../index/home.php");
?>