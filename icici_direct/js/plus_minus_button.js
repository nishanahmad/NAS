const plus_button = document.getElementById('plus_button');
const minus_button = document.getElementById('minus_button');

minus_button.addEventListener('click', event => {
	
	const ce_hedge_strike = Number(document.getElementById('ce_hedge_strike').value);
	const ce_main_strike = Number(document.getElementById('ce_main_strike').value);
	const pe_hedge_strike = Number(document.getElementById('pe_hedge_strike').value);
	const pe_main_strike = Number(document.getElementById('pe_main_strike').value);
	const tick_size = Number(document.getElementById('tick_size').value);

	var selectedIndex = document.getElementById("selectedIndex").value;
	
	event.preventDefault();
	
	$.ajax({
		url: 'all_strike_change_ajax.php',
		type: 'post',
		data: {ce_hedge_strike: ce_hedge_strike - tick_size, ce_main_strike: ce_main_strike - tick_size, 
			   pe_hedge_strike: pe_hedge_strike - tick_size, pe_main_strike: pe_main_strike - tick_size,
			   selectedIndex: selectedIndex},
		success: function(response){
			$("#ce_hedge_price").val(response.ce_hedge);
			$("#ce_main_price").val(response.ce_main);
			$("#pe_hedge_price").val(response.pe_hedge);
			$("#pe_main_price").val(response.pe_main);
			$("#ce_hedge_strike").val(ce_hedge_strike - tick_size);
			$("#ce_main_strike").val(ce_main_strike - tick_size);
			$("#pe_hedge_strike").val(pe_hedge_strike - tick_size);
			$("#pe_main_strike").val(pe_main_strike - tick_size);			
			
			refreshMaxLoss();
		},
		error: function(response){
			console.log(response);
			bootbox.alert('Refresh was lagged. Please refresh once more');
		}				
	});	  
});


plus_button.addEventListener('click', event => {
	event.preventDefault();

	const ce_hedge_strike = Number(document.getElementById('ce_hedge_strike').value);
	const ce_main_strike = Number(document.getElementById('ce_main_strike').value);
	const pe_hedge_strike = Number(document.getElementById('pe_hedge_strike').value);
	const pe_main_strike = Number(document.getElementById('pe_main_strike').value);
	const tick_size = Number(document.getElementById('tick_size').value);

	var selectedIndex = document.getElementById("selectedIndex").value;
	
	$.ajax({
		url: 'all_strike_change_ajax.php',
		type: 'post',
		data: {ce_hedge_strike: ce_hedge_strike + tick_size, ce_main_strike: ce_main_strike + tick_size, 
			   pe_hedge_strike: pe_hedge_strike + tick_size, pe_main_strike: pe_main_strike + tick_size,
			   selectedIndex: selectedIndex},
		success: function(response){
			$("#ce_hedge_price").val(response.ce_hedge);
			$("#ce_main_price").val(response.ce_main);
			$("#pe_hedge_price").val(response.pe_hedge);
			$("#pe_main_price").val(response.pe_main);
			$("#ce_hedge_strike").val(ce_hedge_strike + tick_size);
			$("#ce_main_strike").val(ce_main_strike + tick_size);
			$("#pe_hedge_strike").val(pe_hedge_strike + tick_size);
			$("#pe_main_strike").val(pe_main_strike + tick_size);						
			refreshMaxLoss();
		},
		error: function(response){
			console.log(response);
			bootbox.alert('Refresh was lagged. Please refresh once more');
		}				
	});	  
});