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
			  $('#guldenglyph').prop('title', 'GuldenD server not up to date');
		  } else {
		  	  $('#gulden').html("GuldenD is not running");
			  $('#node').html("GuldenD is not running");
			  $('#witness').html("GuldenD is not running");
			  $('#server').html("GuldenD is not running");
			  $('#guldenglyph').prop('title', 'GuldenD server up to date');
		  }
		  $('#tableblocks > tbody:last-child').html(data['table']);
		  
		  if(data['gulden']['version']!="") { $('#connectionerror').hide(); }
		  
		  $('#errordiv').html("");
		  if(data['errors']!='') {
		  	$('#errordiv').html("<div class='alert alert-warning'>"+data['errors']+"</div>")
		  }
		  
		  if(data['gulden']['sync']!='100%' && data['server']['cpu']!='' && data['gulden']['sync']!='') {
		  	$('#errordiv').append("<div class='alert alert-warning'>GuldenD is still syncing, G-DASH will respond slower until sync has finished. Please wait...</div>")
		  }
		  
		  if(Number.isInteger(data['gulden']['version'])==false || data['gulden']['sync'] != '100%') { $("#guldendiv").css("background-color","#ffe6e6"); } else { $("#guldendiv").css("background-color","#e6ffe6"); }
		  if(data['node']['inbound']=='' && (data['node']['connections']==0 || data['node']['connections']=='')) { 
		  		$("#nodediv").css("background-color","#ffe6e6");
		  		$('#nodeglyph').prop('title', 'No connections');
		  		
		  	} else if(data['node']['inbound']=='' && data['node']['connections']>0) {
		  		$("#nodediv").css("background-color","#FEE2BA");
		  		$('#nodeglyph').prop('title', 'No inbound connections');
		  	} else { 
		  		$("#nodediv").css("background-color","#e6ffe6");
		  		$('#nodeglyph').prop('title', 'Found inbound and outbound connections');
		  	}
		  if(data['witness']=='') { 
		  		$("#witnessdiv").css("background-color","#ffe6e6"); 
		  		$('#witnessglyph').prop('title', 'No witness activity');
		  	} else { 
		  		$("#witnessdiv").css("background-color","#e6ffe6");
		  		$('#witnessglyph').prop('title', 'Witness activity found');
		  	}
		  if(data['server']['cpu']=='') { 
		  		$("#serverdiv").css("background-color","#ffe6e6");
		  		$('#serverglyph').prop('title', 'GuldenD server is not running');
		  	} else { 
		  		$("#serverdiv").css("background-color","#e6ffe6"); 
		  		$('#serverglyph').prop('title', 'GuldenD server is running fine');
		  	}	  
	   });
	};
	
	loadjsondata();
	
	//Load the json data for the dashboard every x seconds
	setInterval (function () {
		loadjsondata()
	}, refreshRate)
});