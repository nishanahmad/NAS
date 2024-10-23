<?php
require 'functions.php';
date_default_timezone_set("Asia/Kolkata");
ini_set('max_execution_time', '0');
ini_set('memory_limit', '-1');

$marginFile = fopen('D:\ExpiryStrangle\Icici\AvailableMargin.txt','r');
while ($line = fgets($marginFile))
	$netMargin = floor($line);
fclose($marginFile);

$expiriesFile = fopen('D:\ExpiryStrangle\Icici\expiries.txt','r');
while ($line = fgets($expiriesFile))
	$expiriesMap = json_decode($line, true);

fclose($expiriesFile);

if(isset($_GET['index']))
	$selectedIndex = $_GET['index'];
else
	$selectedIndex = getTodayExpiringIndex();

$indexMap = getIndexDetails($selectedIndex);
$indexMap['expiry_date'] = $expiriesMap[$selectedIndex];

$instrument_details['stock_code'] = $indexMap['stock_code'];
$instrument_details['exchange_code'] = $indexMap['index_exchange_code'];
$instrument_details['expiry_date'] = '';
$instrument_details['product_type'] = '';
$instrument_details['right'] = '';
$instrument_details['strike_price'] = '';

$result = parseResult(fetchData($instrument_details));
$strike = getStrike($result['ltp'],$indexMap['tick_size']);

$iron_fly = generateIronFly($indexMap,$strike);
$result = parseResultIronFly(fetchDataIronFly($iron_fly));

$percentage = 30;
$usableMargin = floor($netMargin * $percentage / 100);
?>
<head>
	<title>Iron butterfly</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/6.0.0/bootbox.min.js" integrity="sha512-oVbWSv2O4y1UzvExJMHaHcaib4wsBMS5tEP3/YkMP6GmkwRJAa79Jwsv+Y/w7w2Vb/98/Xhvck10LyJweB8Jsw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>	
	<link
	  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
	  rel="stylesheet"
	/>
	<link
	  href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
	  rel="stylesheet"
	/>
	<link
	  href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.3.2/mdb.min.css"
	  rel="stylesheet"
	/>
	<style>
	/* Center tables for demo */
	table {
	  margin: 0 auto;
	}

	/* Default Table Style */
	table {
	  color: #333;
	  background: white;
	  border: 1px solid grey;
	  font-size: 12pt;
	  border-collapse: collapse;
	}
	table thead th,
	table tfoot th {
	  color: #777;
	  background: rgba(0,0,0,.1);
	}
	table caption {
	  padding:.5em;
	}
	table th,
	table td {
	  padding: .5em;
	  border: 1px solid lightgrey;
	}
	/* Zebra Table Style */
	[data-table-theme*=zebra] tbody tr:nth-of-type(odd) {
	  background: rgba(0,0,0,.05);
	}
	[data-table-theme*=zebra][data-table-theme*=dark] tbody tr:nth-of-type(odd) {
	  background: rgba(255,255,255,.05);
	}
	/* Dark Style */
	[data-table-theme*=dark] {
	  color: #ddd;
	  background: #333;
	  font-size: 12pt;
	  border-collapse: collapse;
	}
	[data-table-theme*=dark] thead th,
	[data-table-theme*=dark] tfoot th {
	  color: #aaa;
	  background: rgba(0255,255,255,.15);
	}
	[data-table-theme*=dark] caption {
	  padding:.5em;
	}
	[data-table-theme*=dark] th,
	[data-table-theme*=dark] td {
	  padding: .5em;
	  border: 1px solid grey;
	}
	body{
		font-family	: "IBM Plex Sans", sans-serif;
	}
	.spanfont{
		font-size:16px;
	}

	</style>
</head>
<body onload="refreshMaxLoss()">	
	<div class="container d-grid gap-4">
		<div class="">
			<div class="p-2 bg-light border"><h3 style="margin-left:43%">Iron butterfly</h3></div>
		</div>	
		<div class="col-md-12 offset-5"></div>
		<div class="row">
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								Net margin <br/>
								<span class="badge badge-secondary spanfont" id="netMargin">₹<?php echo moneyFormatIndia($netMargin);?></span><br/><br/>
							</div>
							<div class="col-md-6">
								<?php echo $percentage;?>% margin<br/>
								<span class="badge badge-secondary spanfont" id="usableMargin">₹<?php echo moneyFormatIndia($usableMargin);?></span><br/><br/>
								<input type="hidden" id="usableMarginHidden" value="<?php echo $usableMargin;?>"/>
							</div>
						</div>			
						<div class="row">
							<div class="col-md-6">
								Margin per lot<br/>
								<span class="badge badge-primary spanfont" id="marginPerLot"></span><br/><br/>
							</div>
							<div class="col-md-6">
								Lots<br/>
								<span class="badge badge-primary spanfont" id="noOfLots"></span><br/><br/>					
							</div>
						</div>								
						Total margin required<br/>
						<span class="badge badge-primary spanfont" id="totalRequiredMargin"></span><br/><br/>					
					</div>
				</div>	
			</div>	
			<div class="col-md-6">
				<div class="card text-center">
				  <div class="card-header">
					<div class="row">
						<div class="col-md-6 offset-1">
						   <select name="index" id="index" class="form-control" style="width:250px;"><?php
								foreach($expiriesMap as $index => $expiry)
								{																							?>
									<option value="<?php echo $index;?>" <?php if($index == $selectedIndex) echo 'selected';?>><?php echo getIndexName($index);?></option><?php
								}																							?>
							</select>
						</div>
						<div class="col-md-4">
							<input readonly type="text" style="border:none;" value="<?php echo $expiriesMap[$selectedIndex];?>">
						</div>
					</div>
				  </div>
				  <div class="card-body">
					<table>
						<thead class="bg-light">
							<tr style="text-align:center">
								<th></th>
								<th>Strike</th>
								<th>Type</th>
								<th>Price</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><button type="button" class="btn btn-primary" data-mdb-ripple-init>Buy</button></td>
								<td>
									<div class="input-group">
										<button id="pe_hedge_minus">−</button>
										<input type="number" class="form-control" value="<?php echo $strike - $indexMap['tick_size'] * 4 ;?>" name="pe_hedge_strike" id="pe_hedge_strike"/>
										<button id="pe_hedge_plus">+</button>
									</div>
								</td>
								<td><button type="button" class="btn btn-outline-dark" data-mdb-ripple-init data-mdb-ripple-color="dark">PE</button></td>
								<td><input type="number" class="form-control" value="<?php echo $result['pe_hedge'];?>" name="pe_hedge_price" id="pe_hedge_price"/></td>
							</tr>
							<tr>
								<td><button type="button" class="btn btn-danger" data-mdb-ripple-init>Sell</button></td>
								<td>
									<div class="input-group">
										<button id="pe_main_minus">−</button>
										<input type="number" class="form-control" value="<?php echo $strike;?>" name="pe_main_strike" id="pe_main_strike"/>
										<button id="pe_main_plus">+</button>
									</div>
								</td>
								<td><button type="button" class="btn btn-outline-dark" data-mdb-ripple-init data-mdb-ripple-color="dark">PE</button></td>
								<td><input type="number" class="form-control" value="<?php echo $result['pe_main'];?>" name="pe_main_price" id="pe_main_price"/></td>
							</tr>			
							<tr>
								<td><button type="button" class="btn btn-danger" data-mdb-ripple-init>Sell</button></td>
								<td>
									<div class="input-group">
										<button id="ce_main_minus">−</button>
										<input type="number" class="form-control" value="<?php echo $strike;?>" name="ce_main_strike" id="ce_main_strike"/>
										<button id="ce_main_plus">+</button>
									</div>
								</td>
								<td><button type="button" class="btn btn-outline-dark" data-mdb-ripple-init data-mdb-ripple-color="dark">CE</button></td>
								<td><input type="number" class="form-control" value="<?php echo $result['ce_main'];?>" name="ce_main_price" id="ce_main_price"/></td>
							</tr>						
							<tr>
								<td><button type="button" class="btn btn-primary" data-mdb-ripple-init>Buy</button></td>
								<td>
									<div class="input-group">
										<button id="ce_hedge_minus">−</button>
										<input type="number" class="form-control" value="<?php echo $strike + $indexMap['tick_size'] * 4 ;?>" name="ce_hedge_strike" id="ce_hedge_strike"/>
										<button id="ce_hedge_plus">+</button>
									</div>
								</td>
								<td><button type="button" class="btn btn-outline-dark" data-mdb-ripple-init data-mdb-ripple-color="dark">CE</button></td>
								<td><input type="number" class="form-control" value="<?php echo $result['ce_hedge'];?>" name="ce_hedge_price" id="ce_hedge_price"/></td>
							</tr>			
						</tbody>
					</table>
					<br/><br/>
						<button id="minus_button">-</button>&nbsp;&nbsp;
						<button id="plus_button">+</button>
				  </div>
				  <div class="card-footer">
					<button type="button" class="btn btn-outline-success" data-mdb-ripple-init data-mdb-ripple-color="dark" id="refresh" onclick="refresh()">Refresh</button>
				  </div>
				</div>		
			</div>
			<div class="col-md-3">
				<div class="card">
					<div class="card-body">
						Max profit <br/>
						<span class="badge badge-success spanfont" id="maxProfit"></span>&nbsp;&nbsp;
						<span id="maxProfitMarginPercentage" style="color:green"></span>&nbsp;&nbsp;
						<span id="maxProfitCapitalPercentage" style="color:green"></span><br/><br/>
						
						Max Loss <br/>
						<span class="badge badge-danger spanfont" id="maxLoss"></span>&nbsp;&nbsp;
						<span id="maxLossMarginPercentage" style="color:red"></span>&nbsp;&nbsp;
						<span id="maxLossCapitalPercentage" style="color:red"></span><br/><br/>
						
						<button type="button" class="btn btn-danger" data-mdb-ripple-init data-mdb-ripple-color="dark" id="place_order" onclick="place_order()">Place Order</button>
					</div>
				</div>	
			</div>
		</div>
		<input type="hidden" id="stock_code" value=<?php echo $indexMap['stock_code'];?>>
		<input type="hidden" id="tick_size" value=<?php echo $indexMap['tick_size'];?>>
		<input type="hidden" id="lot_size" value=<?php echo $indexMap['lot_size'];?>>
		<input type="hidden" id="capital" value=<?php echo $netMargin;?>>
		<input type="hidden" id="selectedIndex" value=<?php echo $selectedIndex;?>>
	</div>
	<!--script type="text/javascript" src="js/ce_main.js"></script>
	<script type="text/javascript" src="js/ce_hedge.js"></script>
	<script type="text/javascript" src="js/pe_main.js"></script>
	<script type="text/javascript" src="js/pe_hedge.js"></script-->
	<script type="text/javascript" src="js/refreshMaxLoss.js"></script>
	<script type="text/javascript" src="js/refresh.js"></script>
	<script type="text/javascript" src="js/place_order.js"></script>
	<script type="text/javascript" src="js/plus_minus_button.js"></script>
	<script>
	$('#index').change(function() {

		var $indexOption = $(this).find('option:selected');

		var value = $indexOption.val();
		url = window.location.href.split('?')[0] + '?index=' + value;
		window.location.href = url; 

	});
	/*
	$('#plus_button').click(function(){
		$("#ce_main_plus").click();
		$("#ce_hedge_plus").click();
		$("#pe_main_plus").click();
		$("#pe_hedge_plus").click();
	});	
	$('#minus_button').click(function(){
		$("#ce_main_minus").click();
		$("#ce_hedge_minus").click();
		$("#pe_main_minus").click();
		$("#pe_hedge_minus").click();
	});		
	*/
	</script>
</body>
