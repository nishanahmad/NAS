const pe_hedge_minus_button = document.getElementById('pe_hedge_minus');
const pe_hedge_plus_button = document.getElementById('pe_hedge_plus');
const pe_hedge_strike = document.getElementById('pe_hedge_strike');
var selectedIndex = document.getElementById("selectedIndex").value;

pe_hedge_minus_button.addEventListener('click', event => {
	event.preventDefault();
	const currentValue = Number(pe_hedge_strike.value) || 0;
	pe_hedge_strike.value = currentValue - Number(document.getElementById('tick_size').value);

	$.ajax({
		url: 'strike_change_ajax.php',
		type: 'post',
		data: {strike: pe_hedge_strike.value, right: 'put', leg_type: 'pe_hedge', selectedIndex: selectedIndex},
		success: function(response){
			$("#pe_hedge_price").val(response.ltp);
			refreshMaxLoss();
		},
		error: function(response){
			bootbox.alert('Refresh was lagged. Please refresh once more');
		}				
	});	  
});

pe_hedge_plus_button.addEventListener('click', event => {
	event.preventDefault();
	const currentValue = Number(pe_hedge_strike.value) || 0;
	pe_hedge_strike.value = currentValue + Number(document.getElementById('tick_size').value);
  
	$.ajax({
		url: 'strike_change_ajax.php',
		type: 'post',
		data: {strike: pe_hedge_strike.value, right: 'put', leg_type: 'pe_hedge', selectedIndex: selectedIndex},
		success: function(response){
			$("#pe_hedge_price").val(response.ltp);
			refreshMaxLoss();
		},
		error: function(response){
			bootbox.alert('Refresh was lagged. Please refresh once more');
		}				
	});	  	  
});