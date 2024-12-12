<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
	require '../connect.php';
	
	$id = $_GET['id'];
	$passQuery = mysqli_query($con, "SELECT * FROM gate_pass WHERE id = '$id'") or die(mysqli_error($con).'Line 11');
	$pass = mysqli_fetch_array($passQuery, MYSQLI_ASSOC);	
	$passId = $pass['id'];
	
	$consignorsMap = array();
	$consignors = mysqli_query($con,"SELECT * FROM consignors") or die(mysqli_error($con));	
	foreach($consignors as $consignor)
		$consignorsMap[$consignor['id']] = $consignor;	
		
	$vehiclesMap = array();
	$vehicles = mysqli_query($con,"SELECT * FROM vehicles") or die(mysqli_error($con));	
	foreach($vehicles as $vehicle)
		$vehiclesMap[$vehicle['id']] = $vehicle;
		
	$usersMap = array();
	$users = mysqli_query($con,"SELECT * FROM users") or die(mysqli_error($con));	
	foreach($users as $user)
		$usersMap[$user['user_id']] = $user['user_name'];		
	
	?>
		
	<html>
	<head>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous"/>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
		<style>
			.bordered {
			   border-collapse: collapse;
			   table-layout: fixed;
			   width:98%;
			}
			.bordered td,  .bordered tr {
			   border: 1px solid;
			   padding: 5px;
			}
			
			.container {
			  width:20%;
			  border: 1px solid #ccc;
			  padding: 10px; /* Add padding to the inside of the border */
			  margin: 10px; /* Add margin to the outside of the border */
			}	
		</style>
	</head>
	<body onload="PrintPreview()">
		<div style="margin-left:2%">
			<table class="bordered">
				<tbody>
					<tr>
						<td colspan="3" style="text-align:center">
							<img style="float:right;margin-top:1%;margin-right:5%" src='../images/original_logo.jpg' height="5%" width="30%"/>
							<div style="margin-left:38%" class="container"><strong>LORRY RECEIPT</strong></div>
							<div style="margin-left:65%">
								<p align="left">
									Opp.Kerala Water Authority Water Quality Testing Lab,THANA, KANNUR 670012<br/>State: Kerala, Code: 32, Mobile: 9847086862
								</p>
							</div>
						</td>
					</tr>
					<tr>
						<td>LR No : KNR/24-25/<strong><?php echo $pass['id'];?></strong></td>
						<td>Token No : <strong><?php echo $pass['token_no'];?></strong></td>
						<td>Date : <strong><?php echo date('d.m.Y',strtotime($pass['date']));?></strong></td>
					</tr>
					<tr>
						<td>SL NO : <strong><?php echo $pass['sl_no'];?></strong></td>
						<td>Order No : <strong><?php echo $pass['order_no'];?></strong></td>
						<td>Time : <strong><?php echo $pass['time'];?></strong></td>
					</tr>		
					<tr>
						<td>TRANSPORTER CODE : <strong><?php echo $pass['time'];?></strong>
						</td>
						<td>Vehicle No : <strong><?php echo $vehiclesMap[$pass['vehicle_id']]['number'];?></strong>
						</td>	
						<td rowspan="4">
							<u>TERMS & CONDITIONS:</u>
							<ol style="padding:15px;">
							  <li>Received above quantity of cement in Good Condition.</li>
							  <li>Received the Invoice Copy / Delivery Challan along with Original Copy & promise to hand it over to Consignee at the time of delivery and EPOD to be done.</li>
							  <li>The truck owner & driver are responsible for any theft, accident fire or any calamity during transportation of goods.</li>
							  <li>Truck shall make following free deliveries LCV-10Km one way 2 deliveries other vehicles 15Km one way 3 deliveries</li>
							</ol>
						</td>						
					</tr>
					<tr>	
						<td>
							LICENSE CODE : <strong>2/392383</strong>
						</td>
						<td>
							MOBILE : <strong></strong>
						</td>
					</tr>
					<tr>	
						<td>
							Consigner:<br/>
							&nbsp;<strong><?php echo $consignorsMap[$pass['consignor_id']]['address'];?></strong>
						</td>
						<td>
							Consignee:<br/>
							&nbsp;<strong>NAS AGENCIES, Opp.Kerala Water Authority Water Quality Testing Lab THANA, KANNUR, 670012</strong>
						</td>
					</tr>					
					<tr>	
						<td>
							GST No:- <strong><?php echo $consignorsMap[$pass['consignor_id']]['gst'];?></strong>
						</td>
						<td>
							GST No:- <strong>32AAJFN4692G1Z5</strong>
						</td>
					</tr>	
					<tr>
						<td>
							From : 
							<strong>&nbsp;<?php echo $consignorsMap[$pass['from_godown']]['godown'];?></strong>
						</td>					
						<td>
							Delivery At : <strong>&nbsp;<?php echo $pass['delivery_at'];?></strong>
						</td>
						<td rowspan="2">
							<div style="margin-left:30%;">
								<img src="../images/seal.jpg" height="50%" width="50%">
							</div>
							<div style="float:right;margin-right:5%">
								<strong><?php echo $usersMap[$pass['entered_by']];?></strong>
							</div>							
						</td>	
					</tr>
					<tr>
						<td colspan="2">
							<div class="row">
								<div class="col-7">Particulars:</div>
								<div class="col-2">No of bags</div>
								<div class="col-2">Weight</div>
							</div>
							<br/>
							<?php
							if($pass['ut_qty'] > 0)
							{?>
								<div class="row">
									<div class="col-7"><strong>ULTRATECH PPC LAMINATED</strong></div>
									<div class="col-2"><strong><?php echo $pass['ut_qty'];?></strong></div>
									<div class="col-2"><strong><?php echo $pass['ut_qty'] * 50 / 1000;?> MT</strong></div>
								</div><br/><?php
							}?>
							<?php
							if($pass['super_qty'] > 0)
							{?>
								<div class="row">
									<div class="col-7"><strong>ULTRATECH SUPER PPC LAMINATED</strong></div>
									<div class="col-2"><strong><?php echo $pass['super_qty'];?></strong></div>
									<div class="col-2"><strong><?php echo $pass['super_qty'] * 50 / 1000;?> MT</strong></div>
								</div><?php
							}?>							
						</td>
					</tr>
					<tr>
						<td colspan="2" rowspan="4" style="vertical-align:top;">Payment Terms to be Billed :</td>
						<td style="border-bottom:1px solid white !important">Driver’s Name : <strong><?php echo $pass['driver'];?></strong></td>
					</tr>
					<tr>
						<td style="border-bottom:1px solid white !important">Driver’s Mobile : <strong><?php echo $pass['driver_phone'];?></strong></td>
					</tr>
					<tr>
						<td style="border-bottom:1px solid white !important">Driver Licence No : <strong><?php echo $pass['driver_license_no'];?></strong></td>
					</tr>					
					<tr>
						<td style="text-align:right;"><br/><br/>Signature of Driver</td>
					</tr>
					<tr>
						<td colspan="3">
							<div style="text-align:center">
								<u>FOR SECURITY USE</u><br/><br/>
							</div>
							Checked the Material & Quantity loaded in the truck as per the invoice.<br/><br/>
							<div style="text-align:right">
								Signature of Security<br/>
							</div>							
						</td>
					</tr>	
					<tr>
						<td colspan="3">
							<br/>
							No. of Bags : ____________________&nbsp;&nbsp;&nbsp;
							Brand & Type of Packing : _____________________<br/><br/><br/>
							<div style="text-align:right">
								Sign. of Logistics Assistant
							</div>
						</td>
					</tr>					
					<tr>
						<td colspan="3">
							<div style="text-align:center">
								<u>PACKING PLANT</u><br/><br/><br/>
							</div>						
							No. of Bags Loaded : ____________________&nbsp;&nbsp;&nbsp;
							Grade : _____________________<br/><br/><br/>
							<div style="text-align:right">
								Sign. of Packing Plant Supervisor
							</div>
						</td>
					</tr>										
					<tr>
						<td colspan="3">
							<div style="text-align:center">
								<u>DECLARATION</u><br/><br/>
								Whether tax is payable under reverse charge mechanism – Yes or No – Mentioned whichever is applicable<br/><br/>
							</div>						
						</td>
					</tr>															
				</tbody>
			</table>
		</div>
	</body>
	<script>
	function PrintPreview(){
		window.print();
		var mediaQueryList = window.matchMedia('print');
		mediaQueryList.addListener(function(mql) {
			if (mql.matches) {
				console.log('before print dialog open');
			} else {
				history.back() ;
			}
		});

	}	
	</script>
	</html>																																								<?php
	mysqli_close($con);
}
else
	header("Location:../index/home.php");
