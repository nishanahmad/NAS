function refreshMaxLoss()
{
	var ce_main_price = document.getElementById("ce_main_price").value;
	var ce_hedge_price = document.getElementById("ce_hedge_price").value;
	var ce_main_strike = document.getElementById("ce_main_strike").value;
	var ce_hedge_strike = document.getElementById("ce_hedge_strike").value;		
	var pe_main_price = document.getElementById("pe_main_price").value;
	var pe_hedge_price = document.getElementById("pe_hedge_price").value;
	var pe_main_strike = document.getElementById("pe_main_strike").value;
	var pe_hedge_strike = document.getElementById("pe_hedge_strike").value;		
	var lot_size = document.getElementById("lot_size").value;
	var usableMargin = document.getElementById("usableMarginHidden").value;
	var capital = document.getElementById("capital").value;
	var selectedIndex = document.getElementById("selectedIndex").value;
	
	var wing_width = ce_hedge_strike - ce_main_strike;
	var net_premium = (ce_main_price - ce_hedge_price) + (pe_main_price - pe_hedge_price)

	max_loss = Math.round((wing_width - net_premium) * lot_size);
	max_profit = Math.round(net_premium * lot_size);
																	
	$.ajax({
		url: 'calculate_margin_ajax.php',
		type: 'post',
		data: {ce_main_strike: ce_main_strike, ce_hedge_strike: ce_hedge_strike, 
			   pe_main_strike: pe_main_strike, pe_hedge_strike: pe_hedge_strike, 
			   ce_main_price: ce_main_price, ce_hedge_price: ce_hedge_price, 
			   pe_main_price: pe_main_price, pe_hedge_price: pe_hedge_price,
			   selectedIndex: selectedIndex},
		success: function(response){
			if(response.Success != null && response.Status == 200)
			{
				let marginPerLotSpan = document.getElementById("marginPerLot");
				marginPerLot = Math.round(response.Success.span_margin_required);
				marginPerLotSpan.textContent = marginPerLot.toLocaleString('en-IN', {maximumFractionDigits: 0,style: 'currency',currency: 'INR'});
				
				let noOfLotsSpan = document.getElementById("noOfLots");
				noOfLots = Math.floor(usableMargin/marginPerLot);
				noOfLotsSpan.textContent = noOfLots;			
				
				max_loss = max_loss * noOfLots;
				max_profit = max_profit * noOfLots;
				
				let profit_span = document.getElementById("maxProfit");
				profit_span.textContent = max_profit.toLocaleString('en-IN',{maximumFractionDigits: 0,style: 'currency',currency: 'INR'});
				let loss_span = document.getElementById("maxLoss");
				loss_span.textContent = max_loss.toLocaleString('en-IN', {maximumFractionDigits: 0,style: 'currency',currency: 'INR'});																					
				
				totalRequiredMargin = marginPerLot * noOfLots;
				let totalRequiredMargin_span = document.getElementById("totalRequiredMargin");
				totalRequiredMargin_span.textContent = totalRequiredMargin.toLocaleString('en-IN', {maximumFractionDigits: 0,style: 'currency',currency: 'INR'});				
				
				maxProfitMarginPercentage = max_profit/totalRequiredMargin * 100;
				let maxProfitMarginPercentage_span = document.getElementById("maxProfitMarginPercentage");
				maxProfitMarginPercentage_span.textContent = (Math.round((maxProfitMarginPercentage + Number.EPSILON) * 10) / 10) + '%';
				
				maxProfitCapitalPercentage = max_profit/capital * 100;
				let maxProfitCapitalPercentage_span = document.getElementById("maxProfitCapitalPercentage");
				maxProfitCapitalPercentage_span.textContent = (Math.round((maxProfitCapitalPercentage + Number.EPSILON) * 10) / 10) + '%';				
				
				let maxLossMarginPercentage_span = document.getElementById("maxLossMarginPercentage");
				maxLossMarginPercentage = max_loss/totalRequiredMargin * 100;
				maxLossMarginPercentage_span.textContent = (Math.round((maxLossMarginPercentage + Number.EPSILON) * 10) / 10) + '%';
				
				maxLossCapitalPercentage = max_loss/capital * 100;
				let maxLossCapitalPercentage_span = document.getElementById("maxLossCapitalPercentage");
				maxLossCapitalPercentage_span.textContent = (Math.round((maxLossCapitalPercentage + Number.EPSILON) * 10) / 10) + '%';								
			}
			else
			{
				bootbox.alert('Margin refresh was not successfull');
				console.log(response);
			}																				
		},
		error: function(response){
			console.log(response);
		}				
	});	  
}