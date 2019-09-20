<?php
session_start();
if(isset($_SESSION["user_name"]))
{
?>	
<!DOCTYPE html>
<html>
	<title>Sales List</title>
	<head>
	<style>
.dataTables_wrapper .dt-buttons {
  float:none;  
  text-align:center;
}
	</style>	
		<link rel="stylesheet" type="text/css" href="../css/glow_box.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css">
		<link rel="stylesheet" type="text/css" href="../css/fixedHeader.css">
		<link rel="stylesheet" type="text/css" href="../css/buttons.css">

		<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" language="javascript" src="../js/jquery.dataTables.js"></script>
		<script type="text/javascript" language="javascript" src="../js/fixedHeader.js"></script>
		<script type="text/javascript" language="javascript" src="../js/buttons.js"></script>
		<script type="text/javascript" language="javascript" src="../js/html5ExportButton.js"></script>
		<script type="text/javascript" language="javascript" src="../js/colVis.js"></script>	
		<script type="text/javascript" language="javascript" src="../js/jsZip.js"></script>		
		<script type="text/javascript" language="javascript">
			$(document).ready(function() {
				var dataTable = $('#sales-table').DataTable( {
					dom: 'lfBrtip',
					buttons: ['excelHtml5','colvis'],									
					"processing": true,
					"serverSide": true,
					"responsive": true,
					"bJQueryUI":true,
					"iDisplayLength": 500,	
					"aaSorting" : [[0, 'desc']],					
					"ajax":{
						url :"list_server.php", // json datasource
						type: "post",  // method  , by default get
						error: function(){  // error handling
							$(".sales-table-error").html("");
							$("#sales-table").append('<tbody class="sales-table-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
							$("#sales-table_processing").css("display","none");
										}
						   }
				} );
				
   dataTable.on( 'xhr', function () {
    var json = dataTable.ajax.json();
	$('.total').html(json.total);
	$('.sql').html(json.sql);
} );				
				
				
				$("#employee-grid_filter").css("display","none");  // hiding global search box
				$('.search-input-text').on( 'keyup click', function () {   // for text boxes
					var i =$(this).attr('data-column');  // getting column index
					var v =$(this).val();  // getting search input value
					dataTable.columns(i).search(v).draw();
				} );
				$('.search-input-select').on( 'change', function () {   // for select box
					var i =$(this).attr('data-column');  
					var v =$(this).val();  
					dataTable.columns(i).search(v).draw();
				} );
				
			} );
		</script>

	</head>
	<body>
		<div align="center">
					<a href="../index.php" class="link"><img alt='home' title='home' src='../images/home.png' width='60px' height='60px'/> </a> &nbsp;&nbsp;&nbsp;
					<a href="new.php" class="link"><img alt='Add' title='Add New' src='../images/addnew.png' width='60px' height='60px'/></a>
		
		</div>
<div align="center" class="gradient">
<font size=5>
<br>
<!--SQL:<span class='sql'></span><br><br-->
</br>
<b>TOTAL : <span class='total'></span>
</b></font>
		<br><br><br><br>
			<input type="text" data-column="0" style="width:50px" class="search-input-text textarea" placeholder="Id">&nbsp&nbsp
			<input type="text" data-column="1"  class="search-input-text textarea" placeholder="Date">&nbsp&nbsp
			<input type="text" data-column="2"  class="search-input-text textarea" placeholder="AR">&nbsp&nbsp
			<input type="text" data-column="3" style="width:50px" class="search-input-text textarea" placeholder="Product">&nbsp&nbsp
			<input type="text" data-column="4" style="width:50px" class="search-input-text textarea" placeholder="Qty">&nbsp&nbsp
			<input type="text" data-column="5"  class="search-input-text textarea" placeholder="Bill">&nbsp&nbsp
			<input type="text" data-column="6"  class="search-input-text textarea" placeholder="Truck">&nbsp&nbsp
			<input type="text" data-column="7"  class="search-input-text textarea" placeholder="Customer">&nbsp&nbsp
			<input type="text" data-column="8"  class="search-input-text textarea" placeholder="Engineer">&nbsp&nbsp
			<input type="text" data-column="9"  class="search-input-text textarea" placeholder="Remarks">

		<br><br>
			<table id="sales-table" class="display cell-border no-wrap" >
					<thead>
						<tr>
							<th>Id</th>
							<th style="min-width:90px !important">Date</th>
							<th style="width:200px !important">AR</th>
							<!--th>Rate</th-->	
							<th>Product</th>
							<th>Qty</th>
							<th>Bill</th>							
							<th>Truck</th>
							<th>Customer</th>							
							<th>Engineer</th>							
							<th>Remarks</th>							
						</tr>
					</thead>
			</table>
		</div>
	</body>
</html>																				<?php
}
else
	header("Location:../index.php");
