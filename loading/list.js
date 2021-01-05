$(function(){
	
	if(window.location.href.includes('success')){
		var x = document.getElementById("snackbar");
		x.className = "show";
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, 2000);					
	}	
	
	$('#truck').select2();
	
	var pickeropts = { dateFormat:"dd-mm-yy"}; 
	$( ".datepicker" ).datepicker(pickeropts);	
	
	var total = 0;
	$('.table').find('tbody tr:visible').each(function(){
		total += parseFloat( $(this).find('td:eq(2)').text() );
	});
	$('.total').text(total);
		
	$('.table').on('initialized filterEnd', function(){
		var total = 0;
		$(this).find('tbody tr:visible').each(function(){
			total += parseFloat( $(this).find('td:eq(2)').text() );
		});
		$('.total').text(total);
	})      
	
	$(".table").tablesorter({
		dateFormat : "ddmmyyyy",
		theme : 'bootstrap',
		widgets: ['filter'],
		filter_columnAnyMatch: true
	}); 
	
	$('.loadId').click(function(){
		var loadId = $(this).data('id');
		bootbox.confirm({
			title: "Empty truck?",
			message: "Do you want to empty this truck now? This cannot be undone.",
			buttons: {
				cancel: {
					label: '<i class="fa fa-times"></i> Cancel'
				},
				confirm: {
					label: '<i class="fa fa-check"></i> Confirm'
				}
			},
			callback: function (result) {
				if(result)
				{
					$.ajax({
						url: 'ajax/unloadTruck.php',
						type: 'post',
						data: {id: loadId},
						success: function(response){
							if(response.status == 'success'){
								location.reload();
							}else{
								alert('Some error occured. Please contact admin');
							}
						}
					});		
				}
			}
		});
	});	
});	