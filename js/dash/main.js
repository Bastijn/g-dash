$(document).ready(function() {	
	var refreshRate = 10000;
	
	//Load tooltips
	$(function () {
	  $('[data-toggle="tooltip"]').tooltip();
	})
	
	//Load json data for the dashboard
	var loadjsondata = function() {
	  $.getJSON( "ajax/dashboard.php", function( data ) {				 
		  
		  if(data['server']['cpu']!='') {
			  $('#gulden').html("<b>Version:</b> "+data['gulden']['version']+"<br><b>Sync:</b> "+data['gulden']['sync']);
			  $('#node').html("<b>Connections:</b> "+data['node']['connections']+"<br><b>Inbound:</b> "+data['node']['inbound']);
			  $('#witness').html("<b>PoW<sup>2</sup> Phase:</b> "+data['witness']['phase']+"<br><b>Activity: </b>"+data['witness']['lastactive']);
			  $('#server').html("<b>CPU:</b> "+data['server']['cpu']+"%<br><b>MEM:</b> "+data['server']['mem']+"%");
			  $('#guldenglyph').attr('title', 'GuldenD server up to date')
			  				   .tooltip('fixTitle')
		  } else {
		  	  $('#gulden').html("GuldenD is not running");
			  $('#node').html("GuldenD is not running");
			  $('#witness').html("GuldenD is not running");
			  $('#server').html("GuldenD is not running");
			  $('#guldenglyph').attr('title', 'GuldenD server is not up to date')
			  				   .tooltip('fixTitle')
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
		  		$('#nodeglyph').attr('title', 'No connections')
			  				   .tooltip('fixTitle')
		  		
		  	} else if(data['node']['inbound']=='' && data['node']['connections']>0) {
		  		$("#nodediv").css("background-color","#FEE2BA");
		  		$('#nodeglyph').attr('title', 'No inbound connections')
			  				   .tooltip('fixTitle')
		  	} else { 
		  		$("#nodediv").css("background-color","#e6ffe6");
		  		$('#nodeglyph').attr('title', 'Found inbound and outbound connections')
			  				   .tooltip('fixTitle')
		  	}
		  if(data['witness']['phase']=='') { 
		  		$("#witnessdiv").css("background-color","#ffe6e6"); 
		  		$('#witnessglyph').attr('title', 'No witness activity')
			  					  .tooltip('fixTitle')
		  	} else { 
		  		$("#witnessdiv").css("background-color","#e6ffe6");
		  		$('#witnessglyph').attr('title', 'Current witness phase')
			  					  .tooltip('fixTitle')
		  	}
		  if(data['server']['cpu']=='') { 
		  		$("#serverdiv").css("background-color","#ffe6e6");
		  		$('#serverglyph').attr('title', 'GuldenD server is not running')
			  					 .tooltip('fixTitle')
		  	} else { 
		  		$("#serverdiv").css("background-color","#e6ffe6"); 
		  		$('#serverglyph').attr('title', 'GuldenD server is running fine')
			  					 .tooltip('fixTitle')
		  	}
	   });
	};
	
	loadjsondata();
	
	//Load the json data for the dashboard every x seconds
	setInterval (function () {
		loadjsondata()
	}, refreshRate)
});