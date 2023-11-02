$(function(){
	
	if(window.location.href.includes('success')){
		var x = document.getElementById("snackbar");
		x.className = "show";
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, 2000);		
	}	
				
	$(".ratetable").tablesorter({
		dateFormat : "ddmmyyyy",
		theme : 'bootstrap',
		widgets: ['filter'],
		filter_columnAnyMatch: true
	}); 

	var pickeropts = { dateFormat:"dd-mm-yy"}; 
	$( ".datepicker" ).datepicker(pickeropts);	
	
    // initialize row counter, store it in the table
    updateCurrentRows();

    // upon change to filter, update it
	$("input.tablesorter-filter").keyup(function(){
    //$('input.tablesorter-filter').on('input',function () {
        updateCurrentRows();
    });	
});	

function updateCurrentRows() {
    $('#salesTable').data("rowCount", ($('#salesTable tbody tr').length - $('#salesTable tbody tr.filtered').length));
	var rowCount = $('#salesTable').data("rowCount");
	console.log(rowCount);

    $("#getCurrentRows").text(rowCount);
}