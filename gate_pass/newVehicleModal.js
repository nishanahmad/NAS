// AJAX to populate new modal	
$(function(){
	$('#saveNewVehicle').click(function(){
		if(document.getElementById('number').value)
		{
			var number = document.getElementById('number').value;

			$.ajax({
				url: 'ajax/insertVehicleAjax.php',
				type: 'post',
				data: {number: number},
				success: function(response){
					if(response.status == 'success'){
						$('#vehicle_id').append($('<option/>', { 
							value: response.newid,
							text : response.newnumber 
						}));
						$("#vehicle_id").val(response.newid);
						$("#newVehicleModal").modal('hide');
						$('body').removeClass('modal-open');
						$('.modal-backdrop').remove();						
					}else if(response.status == 'error'){
						$("#vehicleInsertError").text(response.value);
					}
					else{
						$("#vehicleInsertError").text('Unknown error. Please contact admin');
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
					$("#vehicleInsertError").text(msg);
					return false;
				}						
			});			
		}
		else
		{
			$("#vehicleInsertError").text('Please enter value for vehicle number');
			return false;
		}
	});
	$("#newVehicleModal").on("hidden.bs.modal", function(){
		$("#vehicleInsertError").text('');
		$("#number").val('');
	});	
});		