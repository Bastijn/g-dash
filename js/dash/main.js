$(document).ready(function() {	
	var refreshRate = 10000;
	
	//Load json data for the dashboard
	var loadjsondata = function() {
	  $.getJSON( "ajax/dashboard.php", function( data ) {				 
		  
		  if(data['server']['cpu']!='') {
			  $('#gulden').html("<b>Version:</b> "+data['gulden']['version']+"<br><b>Sync:</b> "+data['gulden']['sync']);
			  $('#node').html("<b>Connections:</b> "+data['node']['connections']+"<br><b>Inbound:</b> "+data['node']['inbound']);
			  $('#witness').html(data['witness']);
			  $('#server').html("<b>CPU:</b> "+data['server']['cpu']+"%<br><b>MEM:</b> "+data['server']['mem']+"%");
		  } else {
		  	  $('#gulden').html("GuldenD is not running");
			  $('#node').html("GuldenD is not running");
			  $('#witness').html("GuldenD is not running");
			  $('#server').html("GuldenD is not running");
		  }
		  $('#tableblocks > tbody:last-child').html(data['table']);
		  
		  $('#errordiv').html("");
		  if(data['errors']!='') {
		  	$('#errordiv').html("<div class='alert alert-warning'>"+data['errors']+"</div>")
		  }
		  
		  if(data['gulden']['sync']!='100%' && data['server']['cpu']!='' && data['gulden']['sync']!='') {
		  	$('#errordiv').append("<div class='alert alert-warning'>GuldenD is still syncing, G-DASH will respond slower until sync has finished. Please wait...</div>")
		  }
		  
		  if(Number.isInteger(data['gulden']['version'])==false || data['gulden']['sync'] != '100%') { $("#guldendiv").css("background-color","#ffe6e6"); } else { $("#guldendiv").css("background-color","#e6ffe6"); }
		  if(data['node']['inbound']=='') { $("#nodediv").css("background-color","#ffe6e6"); } else { $("#nodediv").css("background-color","#e6ffe6"); }
		  if(data['witness']=='') { $("#witnessdiv").css("background-color","#ffe6e6"); } else { $("#witnessdiv").css("background-color","#e6ffe6"); }
		  if(data['server']['cpu']=='') { $("#serverdiv").css("background-color","#ffe6e6"); } else { $("#serverdiv").css("background-color","#e6ffe6"); }	  
	   });
	};
	
	loadjsondata();
	
	//Load the json data for the dashboard every x seconds
	setInterval (function () {
		loadjsondata()
	}, refreshRate)
});