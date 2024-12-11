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
	
	?>
		
	<html>
	<head>
	<style>
		table.bordered {
		   border-collapse: collapse;
		   table-layout: fixed;
		   width:70%;
		}
		table.bordered td,  table.bordered tr {
		   border: 1px solid;
		   padding: 10px;
		}
		
		.container {
		  width:20%;
		  border: 1px solid #ccc;
		  padding: 10px; /* Add padding to the inside of the border */
		  margin: 10px; /* Add margin to the outside of the border */
		}		
	</style>
	</head>
	<body>
		<div style="margin-left:25%">
			<table class="bordered">
				<tbody>
					<tr>
						<td colspan="3" style="text-align:center">
							<h1>NAS AGENCIES</h1>
							<div style="margin-left:38%" class="container"><strong>LORRY RECEIPT</strong></div>
							<div style="margin-left:70%">
								Opp.Kerala Water Authority<br/>
								Water Quality Testing Lab<br/>
								THANA, KANNUR 670012<br/>
								State: Kerala, Code: 32<br/>
								Mobile: 9847086862
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
						<td>
							<div style="border-bottom:1px solid;">
								TRANSPORTER CODE :<br/><br/>
								&nbsp;&nbsp;<strong><?php echo $pass['time'];?></strong><br/><br/><br/>
							</div>
							<div style="border-bottom:1px solid;">
								<br/>LICENSE CODE : 
								&nbsp;&nbsp;<strong>2/392383</strong><br/><br/><br/>
							</div>							
							<div style="border-bottom:1px solid;">
								<br/>Consigner:<br/><br/>
								&nbsp;<strong><?php echo $consignorsMap[$pass['consignor_id']]['address'];?></strong><br/><br/><br/>
							</div>
							<div>
								<br/>GST No:- <strong><?php echo $consignorsMap[$pass['consignor_id']]['gst'];?></strong><br/><br/>
							</div>							
						</td>
						<td>
							<div style="border-bottom:1px solid;">
								Vehicle No :<br/><br/>
								&nbsp;&nbsp;<strong><?php echo $vehiclesMap[$pass['vehicle_id']]['number'];?></strong><br/><br/><br/>
							</div>
							<div style="border-bottom:1px solid;">
								<br/>MOBILE : 
								&nbsp;&nbsp;<strong></strong><br/><br/><br/>
							</div>							
							<div style="border-bottom:1px solid;">
								<br/>Consignee:<br/><br/>
								&nbsp;<strong>NAS AGENCIES<br/> Opp.Kerala Water Authority Water Quality Testing Lab THANA, KANNUR, 670012</strong><br/><br/><br/>
							</div>
							<div>
								<br/>GST No:- <strong>32AAJFN4692G1Z5</strong><br/><br/>
							</div>							
						</td>
						<td>
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
							<div style="border-bottom:1px solid;">
								From : <br/><br/>
								<strong>&nbsp;<?php echo $consignorsMap[$pass['from_godown']]['godown'];?></strong>
							</div>
							<div style="border-bottom:1px solid;">
								<br/>Particular : <br/>
								&nbsp;&nbsp;<strong>UT</strong><br/><br/><br/>
								&nbsp;&nbsp;<strong>UT SUPER</strong><br/><br/><br/>
							</div>							
						</td>					
						<td>
							<div style="border-bottom:1px solid;">
								Delivery At : <br/><br/>
								<strong>&nbsp;<?php echo $pass['delivery_at'];?></strong>
							</div>
							<div style="border-bottom:1px solid;">
								<table>
									<tr>
										<td>NO OF BAGS :</td>
										<td>WEIGHT</td>
									</tr>
									<tr>
										<td>80</td>
										<td>4MT</td>
									</tr>									
									<tr>
										<td>100</td>
										<td>5MT</td>
									</tr>																		
								</table>
								<br/><br/> 
							</div>							
						</td>					
					</tr>
				</tbody>
			</table>
			<br/><br/><br/><br/><br/>
		</div>
	</body>
	</html>																																								<?php
	mysqli_close($con);
}
else
	header("Location:../index/home.php");
