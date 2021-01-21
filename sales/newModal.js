$("#saleModal").on("hidden.bs.modal", function(){
	$("#insertError").text('');
	$("#bill").val('');
	$("#ar").val(''); 
	$("#truck").val('');
	$("#engineer").val('');
	$("#order_no").val('');
	$("#product").val('');
	$("#godown").val('');
	$("#qty").val('');
	$("#customer").val('');
	$("#bd").val('');
	$("#phone").val('');
	$("#remarks").val('');
	$("#address1").val('');
	document.getElementById('holding-card').innerHTML = "";
});

$("#newSaleForm").submit(function(){
	var bill = $("#bill").val().toUpperCase();
	var godown = $("#godown").val();
	if(bill.includes('BB') || bill.includes('BC') || bill.includes('GB') || bill.includes('GC') || bill.includes('PB') || bill.includes('PC'))
	{
		if(!godown)
		{
			$("#insertError").text('Please select the godown');
			return false;	
		}
	}
});
