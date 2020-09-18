/*
$('#newSaleForm').on('submit', function(event){
	event.preventDefault();
	var product = document.getElementById('product').value;
	var qty = document.getElementById('qty').value;
	if(document.getElementById('truck').value !== null)
		var truck = document.getElementById('truck').value;
	else
		var truck = "";		

	$.ajax({
		url: 'ajax/upsertLoading.php',
		type: 'post',
		data: {product:product, qty:qty, truck:truck},
		success: function(response){
			if(response.status == 'success'){
				$( "#saleModal" ).fadeOut( "slow", function() {
					$("#newSaleForm")[0].submit();
				});
			}else{
				$("#insertError").text(response.value);
			}
		},
		error: function (jqXHR, exception) {
			var msg = '';
			if (jqXHR.status === 0) {
				msg = 'Not connect.\n Verify Network.';
			} else if (jqXHR.status == 404) {
				msg = 'Requested page not found. [404]';
			} else if (jqXHR.status == 500) {
				msg = 'Internal Server Error [500].';
			} else if (exception === 'parsererror') {
				msg = 'Requested JSON parse failed.';
			} else if (exception === 'timeout') {
				msg = 'Time out error.';
			} else if (exception === 'abort') {
				msg = 'Ajax request aborted.';
			} else {
				msg = 'Uncaught Error.\n' + jqXHR.responseText;
			}
			$("#insertError").text(msg);
			return false;
		}				
	});
});
*/

$("#saleModal").on("hidden.bs.modal", function(){
	$("#insertError").text('');
	$("#bill").val('');
	$("#ar").val($("#ar option:first").val()); 
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
});