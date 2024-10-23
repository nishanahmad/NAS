function place_order()
{	
	var ce_main_strike = document.getElementById("ce_main_strike").value;
	var ce_main_price = document.getElementById("ce_main_price").value;
	
	var ce_hedge_strike = document.getElementById("ce_hedge_strike").value;
	var ce_hedge_price = document.getElementById("ce_hedge_price").value;

	var pe_main_strike = document.getElementById("pe_main_strike").value;
	var pe_main_price = document.getElementById("pe_main_price").value;
	
	var pe_hedge_strike = document.getElementById("pe_hedge_strike").value;
	var pe_hedge_price = document.getElementById("pe_hedge_price").value;	

	var stock_code = document.getElementById("stock_code").value;
	var lot_size = document.getElementById("lot_size").value;
	var noOfLots = document.getElementById("noOfLots").innerHTML;	
	var selectedIndex = document.getElementById("selectedIndex").value;	

	qty = lot_size * noOfLots;
	$.ajax({
		url: 'place_ce_leg_ajax.php',
		type: 'post',
		data: {ce_main_strike: ce_main_strike, ce_main_price : ce_main_price,
			   ce_hedge_strike: ce_hedge_strike, ce_hedge_price : ce_hedge_price, 
			   stock_code: stock_code, qty : qty, selectedIndex: selectedIndex},
		success: function(response){
			if(response.Status == 200)
				bootbox.alert('Orders places successfully');
			else if(response.Status == 500)
				bootbox.alert(response.errMsg);
			else
				console.log('Unknown error. Check server side logs');
		},
		error: function(response){
			console.log('Unknown error. Check server side logs');
		}				
	});	
	
	$.ajax({
		url: 'place_pe_leg_ajax.php',
		type: 'post',
		data: {pe_main_strike: pe_main_strike, pe_main_price : pe_main_price,
			   pe_hedge_strike: pe_hedge_strike, pe_hedge_price : pe_hedge_price, 
			   stock_code: stock_code, qty : qty, selectedIndex: selectedIndex},
		success: function(response){
			if(response.Status == 200)
				bootbox.alert('Orders places successfully');
			else if(response.Status == 500)
				bootbox.alert(response.errMsg);
			else
				console.log('Unknown error. Check server side logs');
		},
		error: function(response){
			console.log('Unknown error. Check server side logs');
		}				
	});	

}