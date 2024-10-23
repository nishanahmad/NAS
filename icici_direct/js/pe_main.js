const pe_main_minus_button = document.getElementById('pe_main_minus');
const pe_main_plus_button = document.getElementById('pe_main_plus');
const pe_main_strike = document.getElementById('pe_main_strike');
var selectedIndex = document.getElementById("selectedIndex").value;

pe_main_minus_button.addEventListener('click', event => {
	event.preventDefault();
	const currentValue = Number(pe_main_strike.value) || 0;
	pe_main_strike.value = currentValue - Number(document.getElementById('tick_size').value);

	$.ajax({
		url: 'strike_change_ajax.php',
		type: 'post',
		data: {strike: pe_main_strike.value, right: 'put', leg_type: 'pe_main', selectedIndex: selectedIndex},
		success: function(response){
			$("#pe_main_price").val(response.ltp);
			refreshMaxLoss();
		},
		error: function(response){
			bootbox.alert('Refresh was lagged. Please refresh once more');
		}				
	});	  
});

pe_main_plus_button.addEventListener('click', event => {
	event.preventDefault();
	const currentValue = Number(pe_main_strike.value) || 0;
	pe_main_strike.value = currentValue + Number(document.getElementById('tick_size').value);
  
	$.ajax({
		url: 'strike_change_ajax.php',
		type: 'post',
		data: {strike: pe_main_strike.value, right: 'put', leg_type: 'pe_main', selectedIndex: selectedIndex},
		success: function(response){
			$("#pe_main_price").val(response.ltp);
			refreshMaxLoss();
		},
		error: function(response){
			bootbox.alert('Refresh was lagged. Please refresh once more');
		}				
	});	  	  
});