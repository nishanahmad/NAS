const ce_hedge_minus_button = document.getElementById('ce_hedge_minus');
const ce_hedge_plus_button = document.getElementById('ce_hedge_plus');
const ce_hedge_strike = document.getElementById('ce_hedge_strike');
var selectedIndex = document.getElementById("selectedIndex").value;

ce_hedge_minus_button.addEventListener('click', event => {
	event.preventDefault();
	const currentValue = Number(ce_hedge_strike.value) || 0;
	ce_hedge_strike.value = currentValue - Number(document.getElementById('tick_size').value);

	$.ajax({
		url: 'strike_change_ajax.php',
		type: 'post',
		data: {strike: ce_hedge_strike.value, right: 'call', leg_type: 'ce_hedge', selectedIndex: selectedIndex},
		success: function(response){
			$("#ce_hedge_price").val(response.ltp);
			refreshMaxLoss();
		},
		error: function(response){
			bootbox.alert('Refresh was lagged. Please refresh once more');
		}				
	});	  
});

ce_hedge_plus_button.addEventListener('click', event => {
	event.preventDefault();
	const currentValue = Number(ce_hedge_strike.value) || 0;
	ce_hedge_strike.value = currentValue + Number(document.getElementById('tick_size').value);
  
	$.ajax({
		url: 'strike_change_ajax.php',
		type: 'post',
		data: {strike: ce_hedge_strike.value, right: 'call', leg_type: 'ce_hedge', selectedIndex: selectedIndex},
		success: function(response){
			$("#ce_hedge_price").val(response.ltp);
			refreshMaxLoss();
		},
		error: function(response){
			bootbox.alert('Refresh was lagged. Please refresh once more');
		}				
	});	  	  
});