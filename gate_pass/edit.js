$("#deletebutton").click(function(){
	var id = $("#id").val();
	var sql = $("#sql").val();
	var range = $("#range").val();
	$.ajax({
		url: 'ajax/delete.php',
		type: 'post',
		dataType: 'JSON',
		data: {id:id},
		success: function(response){
			if(response.status == 'success'){
				window.location.href = 'list.php?success';
			}else if(response.status == 'error'){
				$("#confirmId").text('');
				$("#deleteError").text(response.value);
				return false;
			}
			else{
				$("#deleteError").text('Unknown error. Please contact admin');
				return false;					
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
			$("#displayError").text(msg);
			return false;
		}		
	});
});		