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