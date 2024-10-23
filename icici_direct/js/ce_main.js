const ce_main_minus_button = document.getElementById('ce_main_minus');
const ce_main_plus_button = document.getElementById('ce_main_plus');
const ce_main_strike = document.getElementById('ce_main_strike');
var selectedIndex = document.getElementById("selectedIndex").value;

ce_main_minus_button.addEventListener('click', event => {
	event.preventDefault();
	const currentValue = Number(ce_main_strike.value) || 0;
	ce_main_strike.value = currentValue - Number(document.getElementById('tick_size').value);

	$.ajax({
		url: 'strike_change_ajax.php',
		type: 'post',
		data: {strike: ce_main_strike.value, right: 'call', leg_type: 'ce_main', selectedIndex: selectedIndex},
		success: function(response){
			$("#ce_main_price").val(response.ltp);
			refreshMaxLoss();
		},
		error: function(response){
			bootbox.alert('Refresh was lagged. Please refresh once more');
		}				
	});	  
});

ce_main_plus_button.addEventListener('click', event => {
	event.preventDefault();
	const currentValue = Number(ce_main_strike.value) || 0;
	ce_main_strike.value = currentValue + Number(document.getElementById('tick_size').value);
  
	$.ajax({
		url: 'strike_change_ajax.php',
		type: 'post',
		data: {strike: ce_main_strike.value, right: 'call', leg_type: 'ce_main', selectedIndex: selectedIndex},
		success: function(response){
			$("#ce_main_price").val(response.ltp);
			refreshMaxLoss();
		},
		error: function(response){
			bootbox.alert('Refresh was lagged. Please refresh once more');
		}				
	});	  	  
});