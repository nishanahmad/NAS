function arRefresh(shopNameArray)
{
	var arId = $('#ar').val();
	var shopName = shopNameArray[arId];
	$('#shopName').val(shopName);
}								
	
$(document).ready(function()
{
	$("#ar,#engineer").select2();
	
	var pickerOpts = { dateFormat:"dd-mm-yy"}; 
	$( "#date" ).datepicker(pickerOpts);	
	$( "#sheetDate" ).datepicker(pickerOpts);	
	
	var arId = $('#ar').val();
	var shopName = shopNameArray[arId];
	$('#shopName').val(shopName);	
		

	var date = $("#date").val();
	var product = $("#product").val();
	var client = $("#ar").val();
	
	$.ajax({
		type: "POST",
		url: "ajax/getRate.php",
		data:'date='+date+'&product='+product,
		success: function(data){
			var rate = data;
			$("#rate").val(rate);
			refreshRate();
		}
	});
	$.ajax({
		type: "POST",
		url: "ajax/checkEngineer.php",
		data:'client='+client,
		success: function(data){
			if(data.includes("Engineer"))
			{
				$("#wd").val(0);
				refreshRate();
			}
			else
			{
				$.ajax({
					type: "POST",
					url: "ajax/getWD.php",
					data:'product='+product+'&date='+date,
					success: function(data){
						$("#wd").val(data);
						refreshRate();
					}
				});										
			}
		}
	});
	$.ajax({
		type: "POST",
		url: "ajax/getCD.php",
		data:'date='+date+'&product='+product+'&client='+client,
		success: function(data){
			$("#cd").val(data);
			refreshRate();
		}
	});		


	$("#date").change(function()
	{
		var date = $(this).val();
		var product = $("#product").val();
		var client = $("#ar").val();
		
		$.ajax({
			type: "POST",
			url: "ajax/getRate.php",
			data:'date='+date+'&product='+product,
			success: function(data){
				var rate = data;
				$("#rate").val(rate);
				refreshRate();
			}
		});
		$.ajax({
			type: "POST",
			url: "ajax/checkEngineer.php",
			data:'client='+client,
			success: function(data){
				if(data.includes("Engineer"))
				{
					$("#wd").val(0);
					refreshRate();
				}
				else
				{
					$.ajax({
						type: "POST",
						url: "ajax/getWD.php",
						data:'product='+product+'&date='+date,
						success: function(data){
							$("#wd").val(data);
							refreshRate();
						}
					});										
				}
			}
		});												
		$.ajax({
			type: "POST",
			url: "ajax/getCD.php",
			data:'date='+date+'&product='+product+'&client='+client,
			success: function(data){
				$("#cd").val(data);
				refreshRate();
			}
		});		
	});
	
	
	
	$("#product").change(function()
	{
		date = $("#date").val();
		product = $(this).val();
		client = $("#ar").val();
		
		$.ajax({
			type: "POST",
			url: "ajax/getRate.php",
			data:'product='+product+'&date='+date,
			success: function(data){
				var rate = data;
				$("#rate").val(rate);
				refreshRate();
			}
		});			
		$.ajax({
			type: "POST",
			url: "ajax/getWD.php",
			data:'product='+product+'&date='+date,
			success: function(data){
				$("#wd").val(data);
				refreshRate();
			}
		});										
		$.ajax({
			type: "POST",
			url: "ajax/getCD.php",
			data:'product='+product+'&date='+date+'&client='+client,
			success: function(data){
				$("#cd").val(data);
				refreshRate();
			}
		});				
		$.ajax({
			type: "POST",
			url: "ajax/checkEngineer.php",
			data:'client='+client,
			success: function(data){
				if(data.includes("Engineer"))
				{
					$("#wd").val(0);
					refreshRate();
				}
				else
				{
					$.ajax({
						type: "POST",
						url: "ajax/getWD.php",
						data:'product='+product+'&date='+date,
						success: function(data){
							$("#wd").val(data);
							refreshRate();
						}
					});										
				}
			}
		});															
	});
	
	$("#ar").change(function()
	{
		var date = $("#date").val();
		var product = $("#product").val();
		var client = $(this).val();
		$.ajax({
			type: "POST",
			url: "ajax/getCD.php",
			data:'client='+client+'&date='+date+'&product='+product,
			success: function(data){
				$("#cd").val(data);
				refreshRate();
			}
		});			
		$.ajax({
			type: "POST",
			url: "ajax/checkEngineer.php",
			data:'client='+client,
			success: function(data){
				if(data.includes("Engineer"))
				{
					$("#wd").val(0);
					refreshRate();
				}
				else
				{
					$.ajax({
						type: "POST",
						url: "ajax/getWD.php",
						data:'product='+product+'&date='+date,
						success: function(data){
							$("#wd").val(data);
							refreshRate();
						}
					});										
				}
			}
		});			
	});	
	$("#bd").change(function(){
		refreshRate();
	});	

	$("#sheetMdlBtn").click(function(){
		var truck = $("#sheet_truck").val();
		truck = truck.replace("-", "").toUpperCase();
		$.ajax({
			type: "POST",
			url: "ajax/getDriverName.php",
			data:'truck='+truck,
			success: function(response){
				console.log(response);
				$("#driver_name").val(response);
			}
		});
		$.ajax({
			type: "POST",
			url: "ajax/getDriverPhone.php",
			data:'truck='+truck,
			success: function(response){
				$("#driver_phone").val(response);
			}
		});				
	});				
});

function refreshRate()
{
	var rate=document.getElementById("rate").value;
	var cd=document.getElementById("cd").value;
	var wd=document.getElementById("wd").value;
	var bd=document.getElementById("bd").value;
	
	$('#final').val(rate-cd-wd-bd);
}

