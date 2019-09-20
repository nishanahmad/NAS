<?php
?>
<html>
	<script type="text/javascript" language="javascript" src="../../js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="../../js/jquery.dataTables.js"></script>
	<link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.css">	
	<table id="example" class="display" style="width:100%">
		<thead>
			<tr>
				<th>Name</th>
				<th>Date</th>
				<th>Qty</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Name</th>
				<th>Date</th>
				<th>Qty</th>
			</tr>
		</tfoot>
	</table>
	<script>
		$(document).ready(function() {
			$('#example').DataTable( {
				"processing": true,
				"serverSide": true,
				"ajax": "server.php"
			} );
		} );
	</script>	
</html>