$(document).ready(function() {	
	var refreshRate = 120000;
	
	//Load json data for the dashboard
	var loadjsondata = function() {
	
	  $.getJSON( "ajax/node.php", function( data ) {			 
		  
		  if(data['server']['cpu']!='') {
		  	  if(data['node']['inbound']!="0") {
				  $('#serverinfopanel').html("<div class='table-responsive'><table class='table table-striped'>"+
				  							 "<tr><td><b>Version:</b></td><td>"+data['gulden']['version']+"</td></tr>"+
				  							 "<tr><td><b>Server uptime:</b></td><td>"+data['gulden']['uptime']+"</td></tr>"+
				  							 "<tr><td><b>Time offset:</b></td><td>"+data['gulden']['timeoffset']+"</td></tr>"+
				  							 "<tr><td><b>Connections:</b></td><td>"+data['node']['connections']+"</td></tr>"+
				  							 "<tr><td><b>Inbound connections:</b></td><td>"+data['node']['inbound']+"</td></tr>"+
				  							 "</table></div>");
				  
				  $('#tablelocation > tbody:last-child').html(data['location']);
				  $('#tableversion > tbody:last-child').html(data['version']);
			  } else {
			  	$('#serverinfopanel').html("No inbound connections. Did you open/forwarded TCP port 9231?<br>"+
			  								"More information on how to set up a node can be found on "+
			  								"<a href='https://developer.gulden.com/nodes/' target='_blank'>developer.gulden.com</a>");
			  	$('#tablelocation > tbody:last-child').html("<tr><td colspan='4'>No incoming connections</td></tr>");
				$('#tableversion > tbody:last-child').html("<tr><td colspan='4'>No incoming connections</td></tr>");
			  }
			  							 
		  } else {
		  	  $('#serverinfopanel').html("GuldenD is not running");
		  }
		  
		  if(data['errors']!='') {
		  	$('#errordiv').html("<div class='alert alert-warning'>"+data['errors']+"</div>")
		  }	  
	   });
	};
	
	loadjsondata();
	
	//Load the json data for the dashboard every x seconds
	setInterval (function () {
		loadjsondata()
	}, refreshRate)	
});