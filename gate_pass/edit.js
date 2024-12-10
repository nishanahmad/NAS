$(function(){
	var pickeropts = { dateFormat:"dd-mm-yy"}; 
	$( ".datepicker" ).datepicker(pickeropts);	
});		

$('#dateId').change(function(){
	id = $('#id').val();
	newDate = $(this).val();
	$.ajax({
		type: "POST",
		url: "ajax/updateDate.php",
		data:'date='+newDate+'&id='+id,
		success: function(response){
			if(response != false){
				console.log('Succes!!!');
			}
			else{
				alert('Error!!! Update not done');
				location.reload();
			}
		}
	});	  
})			

$('#vehicle').change(function(){
	id = $('#id').val();
	vehicle = $(this).val();
	$.ajax({
		type: "POST",
		url: "ajax/updateVehicle.php",
		data:'vehicle='+vehicle+'&id='+id,
		success: function(response){
			if(response != false){
				console.log('Succes!!!');
			}
			else{
				alert('Error!!! Update not done');
				location.reload();
			}
		}
	});	  
})

$('#driver_phone').change(function(){
	id = $('#id').val();
	phone = $(this).val();
	$.ajax({
		type: "POST",
		url: "ajax/updatePhone.php",
		data:'phone='+phone+'&id='+id,
		success: function(response){
			if(response != false){
				console.log('Succes!!!');
			}
			else{
				alert('Error!!! Update not done');
				location.reload();
			}
		}
	});	  
})

$('#driver_name').change(function(){
	id = $('#id').val();
	driver_name = $(this).val();
	$.ajax({
		type: "POST",
		url: "ajax/updateDriver.php",
		data:'driver='+driver_name+'&id='+id,
		success: function(response){
			if(response != false){
				console.log('Succes!!!');
			}
			else{
				alert('Error!!! Update not done');
				location.reload();
			}
		}
	});	  
})			

$('#bookingIdButton').click(function(){
	var bookingId = $(this).data('id');
	$('#bookingId').val(bookingId);
	var date = new Date().toISOString().substring(0, 10),
	field = document.querySelector('#date');
	field.value = date;
});

$('#closeIdButton').click(function(){
	var closeId = $(this).data('id');
	$('#closeId').val(closeId);
});			