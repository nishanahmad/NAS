function refresh()
{
	
	var ce_main_strike = document.getElementById("ce_main_strike").value;
	var ce_hedge_strike = document.getElementById("ce_hedge_strike").value;		
	var pe_main_strike = document.getElementById("pe_main_strike").value;
	var pe_hedge_strike = document.getElementById("pe_hedge_strike").value;		
	var stock_code = document.getElementById("stock_code").value;	
	var selectedIndex = document.getElementById("selectedIndex").value;	

	$.ajax({
		url: 'refresh_ajax.php',
		type: 'post',
		data: {ce_main_strike: ce_main_strike, ce_hedge_strike: ce_hedge_strike, 
			   pe_main_strike: pe_main_strike, pe_hedge_strike: pe_hedge_strike, 
			   ce_main_stock_code: stock_code, ce_hedge_stock_code: stock_code, 
			   pe_main_stock_code: stock_code, pe_hedge_stock_code: stock_code,
			   selectedIndex: selectedIndex},
		success: function(response){
			console.log('Refresh successfull');
			$("#ce_main_price").val(response.ce_main);
			$("#ce_hedge_price").val(response.ce_hedge);
			$("#pe_main_price").val(response.pe_main);
			$("#pe_hedge_price").val(response.pe_hedge);

			refreshMaxLoss();
		},
		error: function(response){
			console.log(response);
		}				
	});	
}