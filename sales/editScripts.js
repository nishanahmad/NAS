function arRefresh(shopNameArray)
{
	var arId = $('#ar').val();
	var shopName = shopNameArray[arId];
	$('#shopName').val(shopName);
}								
	
$(document).ready(function()
{	
	$("#ar").select2();
    $("#engineer").select2();
	
	var pickerOpts = { dateFormat:"dd-mm-yy"}; 
	$( "#entryDate" ).datepicker(pickerOpts);	
	$( "#sheetDate" ).datepicker(pickerOpts);	
	
	var arId = $('#ar').val();
	var shopName = shopNameArray[arId];
	$('#shopName').val(shopName);	
		

	var date = $("#entryDate").val();
	var product = $("#product").val();
	var client = $("#ar").val();
	
	$.ajax({
		type: "POST",
		url: "getRate.php",
		data:'date='+date+'&product='+product,
		success: function(data){
			var rate = data;
			$("#rate").val(rate);
			refreshRate();
		}
	});
	$.ajax({
		type: "POST",
		url: "checkEngineer.php",
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
					url: "getWD.php",
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
		url: "getCD.php",
		data:'date='+date+'&product='+product+'&client='+client,
		success: function(data){
			$("#cd").val(data);
			refreshRate();
		}
	});		
	$.ajax({
		type: "POST",
		url: "getSD.php",
		data:'date='+date+'&product='+product+'&client='+client,
		success: function(data){
			$("#sd").val(data);
			refreshRate();
		}
	});	

	$("#entryDate").change(function()
	{
		var date = $(this).val();
		var product = $("#product").val();
		var client = $("#ar").val();
		
		$.ajax({
			type: "POST",
			url: "getRate.php",
			data:'date='+date+'&product='+product,
			success: function(data){
				var rate = data;
				$("#rate").val(rate);
				refreshRate();
			}
		});
		$.ajax({
			type: "POST",
			url: "checkEngineer.php",
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
						url: "getWD.php",
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
			url: "getCD.php",
			data:'date='+date+'&product='+product+'&client='+client,
			success: function(data){
				$("#cd").val(data);
				refreshRate();
			}
		});		
		$.ajax({
			type: "POST",
			url: "getSD.php",
			data:'date='+date+'&product='+product+'&client='+client,
			success: function(data){
				$("#sd").val(data);
				refreshRate();
			}
		});		
	});
	
	
	
	$("#product").change(function()
	{
		date = $("#entryDate").val();
		product = $(this).val();
		client = $("#ar").val();
		
		$.ajax({
			type: "POST",
			url: "getRate.php",
			data:'product='+product+'&date='+date,
			success: function(data){
				var rate = data;
				$("#rate").val(rate);
				refreshRate();
			}
		});			
		$.ajax({
			type: "POST",
			url: "getWD.php",
			data:'product='+product+'&date='+date,
			success: function(data){
				$("#wd").val(data);
				refreshRate();
			}
		});										
		$.ajax({
			type: "POST",
			url: "getCD.php",
			data:'product='+product+'&date='+date+'&client='+client,
			success: function(data){
				$("#cd").val(data);
				refreshRate();
			}
		});				
		$.ajax({
			type: "POST",
			url: "getSD.php",
			data:'product='+product+'&date='+date+'&client='+client,
			success: function(data){
				$("#sd").val(data);
				refreshRate();
			}
		});	
		$.ajax({
			type: "POST",
			url: "checkEngineer.php",
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
						url: "getWD.php",
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
		var date = $("#entryDate").val();
		var product = $("#product").val();
		var client = $(this).val();
		$.ajax({
			type: "POST",
			url: "getCD.php",
			data:'client='+client+'&date='+date+'&product='+product,
			success: function(data){
				$("#cd").val(data);
				refreshRate();
			}
		});			
		$.ajax({
			type: "POST",
			url: "getSD.php",
			data:'client='+client+'&date='+date+'&product='+product,
			success: function(data){
				$("#sd").val(data);
				refreshRate();
			}
		});					
		$.ajax({
			type: "POST",
			url: "checkEngineer.php",
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
						url: "getWD.php",
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
});

function refreshRate()
{
	var rate=document.getElementById("rate").value;
	var cd=document.getElementById("cd").value;
	var sd=document.getElementById("sd").value;
	var wd=document.getElementById("wd").value;
	var bd=document.getElementById("bd").value;
	
	$('#final').val(rate-cd-sd-wd-bd);
	setTooltip(cd);
}

function setTooltip(cd)
{
	console.log(cd);
	$("#final").tooltip({
		title: 'CD : ' + cd,
		html: true,
		delay: {show: 100, hide: 300},
		placement:'right',
		container:'body'
	}); 					
}