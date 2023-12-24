$(function(){
	
	if(window.location.href.includes('success')){
		var x = document.getElementById("snackbar");
		x.className = "show";
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, 2000);					
	}	
			
	$(".maintable").tablesorter({
		dateFormat : "ddmmyyyy",
		theme : 'bootstrap',
		widgets: ['filter'],
		filter_columnAnyMatch: true
	}); 

	var pickeropts = { dateFormat:"dd-mm-yy"}; 
	$( ".datepicker" ).datepicker(pickeropts);	
});	

function dlt(id){

	bootbox.confirm({
						message: 'This is a confirm with custom button text and color! Do you like it?',
						buttons: {
							confirm: {
								label: 'Yes',
								className: 'btn-success'
							},
						},
						callback: function (result) {
							hrf = 'delete.php?id='+id;
							window.location.href = hrf;		
						}
					});	
}		
